<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;

class ImageOptimizer
{
    private const MAX_WIDTH = 1920;
    private const MAX_HEIGHT = 1920;
    private const JPEG_QUALITY = 80;
    private const MAX_FILE_SIZE = 2097152; // 2MB

    public static function optimize(UploadedFile $file): UploadedFile
    {
        if (!str_starts_with($file->getMimeType(), 'image/')) {
            return $file;
        }

        if ($file->getSize() < self::MAX_FILE_SIZE) {
            return $file;
        }

        $src = match ($file->getMimeType()) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($file->getPathname()),
            'image/png'               => @imagecreatefrompng($file->getPathname()),
            'image/webp'              => @imagecreatefromwebp($file->getPathname()),
            'image/gif'               => @imagecreatefromgif($file->getPathname()),
            default                   => null,
        };

        if (!$src) {
            return $file;
        }

        $origW = imagesx($src);
        $origH = imagesy($src);

        if ($origW <= self::MAX_WIDTH && $origH <= self::MAX_HEIGHT) {
            imagedestroy($src);
            return $file;
        }

        $ratio = min(self::MAX_WIDTH / $origW, self::MAX_HEIGHT / $origH, 1);
        $newW = (int) round($origW * $ratio);
        $newH = (int) round($origH * $ratio);

        $dst = imagecreatetruecolor($newW, $newH);

        if (in_array($file->getMimeType(), ['image/png', 'image/webp'], true)) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
        imagedestroy($src);

        $tempPath = tempnam(sys_get_temp_dir(), 'img_') . '.jpg';

        imagejpeg($dst, $tempPath, self::JPEG_QUALITY);
        imagedestroy($dst);

        return new UploadedFile(
            $tempPath,
            pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.jpg',
            'image/jpeg',
            null,
            true
        );
    }
}
