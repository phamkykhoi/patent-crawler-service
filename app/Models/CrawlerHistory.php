<?php

namespace App\Models;

use App\Models\Concerns\TrimsAttributes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CrawlerHistory extends Model
{
    use HasUuids, TrimsAttributes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'crawler_histories';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Status constants
     */
    public const STATUS_NEW = 1;
    public const STATUS_PROCESSING = 2;
    public const STATUS_COMPLETED = 3;
    public const STATUS_ERROR = 4;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'release_date',
        'data_bunrui_name',
        'accumulation_time',
        'checksum_value',
        'file_name',
        'file_size',
        'download',
        'bulkdata_url',
        'data_group_name',
        'status',
        'error_logs',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'release_date' => 'date',
            'file_size' => 'integer',
            'download' => 'integer',
            'status' => 'integer',
            'error_logs' => 'array',
        ];
    }
}
