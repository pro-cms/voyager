<?php

namespace Voyager\Admin\Traits\Voyager;

use Illuminate\Support\Facades\File;

trait Filesystem
{
    /**
     * Safely parse a string into JSON
     */
    public function getJson(?string $input, mixed $default = false): mixed
    {
        if (is_null($input)) {
            return $default;
        }
        $json = @json_decode($input);
        if (json_last_error() == JSON_ERROR_NONE) {
            return $json;
        }

        return $default;
    }

    /**
     * Ensures that a directory exists.
     */
    public function ensureDirectoryExists(string $path): void
    {
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    /**
     * Ensures that a file exists.
     */
    public function ensureFileExists(string $path, string $content = ''): void
    {
        $this->ensureDirectoryExists(dirname($path));
        if (!file_exists($path)) {
            file_put_contents($path, $content);
        }
    }

    /**
     * Safely write to a file.
     */
    public function writeToFile(string $path, string|bool $content = ''): bool
    {
        // When passing in json_encode(), the result might be false
        if (is_bool($content)) {
            return false;
        }

        return File::put($path, $content) === false ? false : true;
    }
}