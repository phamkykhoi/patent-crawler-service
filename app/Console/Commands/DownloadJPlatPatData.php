<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DownloadJPlatPatData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jplatpat:download';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download data from jplatpat';

    protected string $loginApiUrl = 'https://www.j-platpat.inpit.go.jp/app/auth/wsc0101';
    protected string $apiUrl = 'https://www.j-platpat.inpit.go.jp/app/bulk/wsc0701';
    protected string $username = 'PWS72549';
    protected string $password = 'UbNK4BeGVJ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting J-PlatPat login and API fetch...');

        try {
            // Step 1: Call login API
            $this->info('Step 1: Calling login API...');
            $loginResult = $this->login();

            $jwtToken = $loginResult['jwt'];
            $cookies = $loginResult['cookies'];

            // Step 2: Call API with JWT token and cookies
            $this->info('Step 2: Calling API...');

            // Build cookie string
            $cookieString = collect($cookies)
                ->map(fn($value, $key) => "{$key}={$value}")
                ->implode('; ');

            // Build headers according to actual request
            $headers = [
                'Accept' => 'application/json, text/plain, */*',
                'Accept-Language' => 'en-US,en;q=0.9',
                'Connection' => 'keep-alive',
                'Content-Type' => 'application/json',
                'Origin' => 'https://www.j-platpat.inpit.go.jp',
                'Referer' => 'https://www.j-platpat.inpit.go.jp/c1100',
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36',
                'sec-ch-ua' => '"Chromium";v="142", "Google Chrome";v="142", "Not_A Brand";v="99"',
                'sec-ch-ua-mobile' => '?0',
                'sec-ch-ua-platform' => '"macOS"',
                'Sec-Fetch-Dest' => 'empty',
                'Sec-Fetch-Mode' => 'cors',
                'Sec-Fetch-Site' => 'same-origin',
                'JWT' => $jwtToken,
            ];

            // Add cookies to headers if available
            if (!empty($cookieString)) {
                $headers['Cookie'] = $cookieString;
            }

            // Make POST request with cookies and JWT
            $response = Http::withHeaders($headers)
                ->post($this->apiUrl, []); // Empty body

            $this->line('Response status: ' . $response->status());

            if ($response->successful()) {
                $data = $response->json();
                $bulkDataList = $data['BULK_DATA_LIST'];

                foreach ($bulkDataList as $bulkDataItem) {
                    /**
                     * Data of $bulkDataItem
                     * "RELEASE_DATE" => "2024/11/07"
                        "DATA_BUNRUI_NAME" => "Daily_Update_Data"
                        "ACCUMULATION_TIME" => "        "
                        "CHECKSUM_VALUE" => "294a22530f0544221e9942d4cb675512"
                        "BULK_DL_DATA_INFO" => array:1 [
                            0 => array:4 [
                                "FILE_NAME" => "JPDRP_20241107.tar.gz"
                                "FILE_SIZE" => 5688373
                                "DOWNLOAD" => 1
                                "BULKDATA_URL" => "https://www.j-platpat.inpit.go.jp/cache/bulk/bulk1/JPDRP/20241107/CDB9E5F4BF9D391881D5B24D7380B4A64A4C367325B393AA4C0726C4A7081C8B/JPDRP_20241107.tar.gz"
                            ]
                        ]
                     */

                     if ($bulkDataItem['DATA_NAME'] === '[Daily_Update] 出願マスタ（特実）(Daily_Update_Data_Appm_PatentUtility)') {
                        dd($bulkDataItem['BULK_DATA']);
                     }

                    if ($bulkDataItem['DATA_NAME'] === '[Daily_Update] 登録マスタ（特実）(Daily_Update_Data_Registrm_PatentUtility)') {
                        foreach ($bulkDataItem['BULK_DATA'] as $bulkData) {
                            if (count($bulkData['BULK_DL_DATA_INFO']) > 1) {
                                dd('Count for', $bulkData);
                            }

                            if (!empty($bulkData['BULK_DL_DATA_INFO'])) {
                                $listFilesInfo = $bulkData['BULK_DL_DATA_INFO'];

                                foreach ($listFilesInfo as $fileInfo) {
                                    if (isset($fileInfo['BULKDATA_URL'])) {
                                        $this->info("Step 3: Downloading file: {$fileInfo['FILE_NAME']}...");
                                        $this->downloadFile($fileInfo['BULKDATA_URL'], $fileInfo['FILE_NAME'], $jwtToken, $cookies);
                                    }

                                    dd($bulkData);
                                }
                            }
                        }
                    }
                }

                return Command::SUCCESS;
            } else {
                throw new \Exception("API request failed with status: {$response->status()}");
            }
        } catch (\Exception $e) {
            $this->error('');
            $this->error('✗ Error: ' . $e->getMessage());
            $this->error('');
            return Command::FAILURE;
        }
    }

    /**
     * Download file from URL and save to storage/app/private.
     *
     * @param string $url
     * @param string $fileName
     * @param string $jwtToken
     * @param array<string, string> $cookies
     * @return void
     * @throws \Exception
     */
    protected function downloadFile(string $url, string $fileName, string $jwtToken, array $cookies): void
    {
        // Build cookie string
        $cookieString = collect($cookies)
            ->map(fn($value, $key) => "{$key}={$value}")
            ->implode('; ');

        // Build headers for file download
        $headers = [
            'Accept' => '*/*',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Connection' => 'keep-alive',
            'Origin' => 'https://www.j-platpat.inpit.go.jp',
            'Referer' => 'https://www.j-platpat.inpit.go.jp/c1100',
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36',
            'sec-ch-ua' => '"Chromium";v="142", "Google Chrome";v="142", "Not_A Brand";v="99"',
            'sec-ch-ua-mobile' => '?0',
            'sec-ch-ua-platform' => '"macOS"',
            'Sec-Fetch-Dest' => 'empty',
            'Sec-Fetch-Mode' => 'cors',
            'Sec-Fetch-Site' => 'same-origin',
            'JWT' => $jwtToken,
        ];

        // Add cookies to headers if available
        if (!empty($cookieString)) {
            $headers['Cookie'] = $cookieString;
        }

        // Download file
        $response = Http::withHeaders($headers)
            ->get($url);

        if (!$response->successful()) {
            throw new \Exception("Failed to download file: {$fileName} - Status: {$response->status()}");
        }

        // Save file to storage/app/private
        Storage::disk('local')->put($fileName, $response->body());

        $fileSize = Storage::disk('local')->size($fileName);
        $this->info("✓ File downloaded successfully: {$fileName} ({$fileSize} bytes)");
    }

    /**
     * Login to J-PlatPat and get JWT token and cookies.
     *
     * @return array{ jwt: string, cookies: array<string, string> }
     * @throws \Exception
     */
    protected function login(): array
    {
        // Hash password (you may need to implement the actual hashing algorithm)
        // For now, using the provided hash values
        $hashedId = 'c415978d6368a392dccea229ef93bba9e8322efa3570f22527936205b262fed8';
        $hashedPassword = 'acf6c96b59dcd509479a3cdfe7cb9f88b03877b2226ebf08830d9e74ab655893';

        $loginPayload = [
            'DISP_ID' => 'C1000',
            'UUID' => 'UUID',
            'AUTH_TYPE' => 'ID',
            'SERVICE_ID' => 'B',
            'IPADRESS' => '',
            'HASHED_ID' => $hashedId,
            'HASHED_PASSWORD' => $hashedPassword,
            'HASHED_NEW_PASSWORD' => '',
            'JWT' => '',
        ];

        $loginResponse = Http::withHeaders([
            'Accept' => 'application/json, text/plain, */*',
            'Content-Type' => 'application/json',
            'Origin' => 'https://www.j-platpat.inpit.go.jp',
            'Referer' => 'https://www.j-platpat.inpit.go.jp/c1000',
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36',
        ])->post($this->loginApiUrl, $loginPayload);

        if (!$loginResponse->successful()) {
            $this->error('Login response: ' . $loginResponse->body());
            throw new \Exception("Login failed with status: {$loginResponse->status()}");
        }

        $loginData = $loginResponse->json();

        if (!isset($loginData['JWT'])) {
            $this->error('Login response: ' . json_encode($loginData, JSON_PRETTY_PRINT));
            throw new \Exception('JWT token not found in login response');
        }

        $jwtToken = $loginData['JWT'];
        $this->info('✓ Login successful! Got JWT token');

        // Get cookies from response headers
        $cookies = [];
        $cookieHeaders = $loginResponse->headers()['Set-Cookie'] ?? [];

        foreach ($loginResponse->cookies() as $cookie) {
            $cookies[$cookie->getName()] = $cookie->getValue();
        }

        // Also parse Set-Cookie headers manually if needed
        if (empty($cookies) && !empty($cookieHeaders)) {
            foreach ($cookieHeaders as $cookieHeader) {
                if (preg_match('/([^=]+)=([^;]+)/', $cookieHeader, $matches)) {
                    $cookies[$matches[1]] = $matches[2];
                }
            }
        }

        if (!empty($cookies)) {
            $this->info('✓ Got ' . count($cookies) . ' cookies');
        }

        return [
            'jwt' => $jwtToken,
            'cookies' => $cookies,
        ];
    }
}

