<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;

class ImageUpload
{
    public static function save(UploadedFile $file, string $dir): string
    {
        $name = $file->hashName();        
        $file->move(public_path($dir), $name);
        return $name;
    }

    public static function delete(?string $name, string $dir): void
    {
        if (!$name) return;
        $path = public_path($dir . '/' . basename($name));   
        if (is_file($path)) @unlink($path);
    }
}