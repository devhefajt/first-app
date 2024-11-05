<?php

namespace App\Services;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    public function upload($file, $path = 'images')
    {
        return $file->store($path, 'public');
    }

    public function delete($filePath)
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }

}