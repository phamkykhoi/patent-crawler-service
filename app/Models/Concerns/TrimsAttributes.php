<?php

namespace App\Models\Concerns;

trait TrimsAttributes
{
    /**
     * Attributes that should not be trimmed.
     *
     * @var array<string>
     */
    protected $trimExclude = [];

    /**
     * Boot the trait.
     */
    protected static function bootTrimsAttributes(): void
    {
        static::saving(function ($model) {
            $model->trimStringAttributes();
        });
    }

    /**
     * Trim all string attributes.
     */
    protected function trimStringAttributes(): void
    {
        foreach ($this->getAttributes() as $key => $value) {
            // Skip if attribute should be excluded from trimming
            if ($this->shouldSkipTrimming($key)) {
                continue;
            }

            // Only trim string values (not null)
            if (is_string($value)) {
                $this->attributes[$key] = trim($value);
            }
        }
    }

    /**
     * Determine if the attribute should be skipped from trimming.
     *
     * @param string $key
     * @return bool
     */
    protected function shouldSkipTrimming(string $key): bool
    {
        // Default fields to skip
        $defaultSkipFields = ['id', 'created_at', 'updated_at', 'deleted_at'];

        // Merge with custom exclude list
        $skipFields = array_merge($defaultSkipFields, $this->trimExclude ?? []);

        return in_array($key, $skipFields);
    }
}

