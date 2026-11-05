<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class PublicFile
{
    /** Nama folder dasar di dalam /public */
    public const BASE = 'storage';

    public static function fullPath(string $relative): ?string
    {
        $relative = ltrim(str_replace('\\', '/', $relative), '/');

        if ($relative === '' || str_contains($relative, '..')) {
            return null;
        }

        return public_path(self::BASE . '/' . $relative);
    }

    public static function put(string $relative, string $contents): string
    {
        $full = self::fullPath($relative);
        if ($full === null) {
            throw new \InvalidArgumentException('Path file tidak valid.');
        }

        if (!is_dir(dirname($full))) {
            mkdir(dirname($full), 0755, true);
        }

        file_put_contents($full, $contents);

        return $relative;
    }

    public static function upload(UploadedFile $file, string $folder, ?string $fileName = null): string
    {
        $folder   = trim($folder, '/');
        $fileName = $fileName ?: Str::random(40) . '.' . $file->extension();

        $dir = public_path(self::BASE . '/' . $folder);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file->move($dir, $fileName);

        return $folder . '/' . $fileName;
    }

    public static function exists(?string $relative): bool
    {
        if (!$relative) {
            return false;
        }

        $full = self::fullPath($relative);

        return $full !== null && is_file($full);
    }

    public static function delete(?string $relative): bool
    {
        if (!self::exists($relative)) {
            return false;
        }

        return @unlink(self::fullPath($relative));
    }
}