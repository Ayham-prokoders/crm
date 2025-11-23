<?php


namespace App\Http\Helper;

use Illuminate\Support\Facades\File;

class FileHelper
{
    public static function upload($file)
    {
        if (!blank($file))
            return $file->move('file', time() . '.' .  $file->extension());
    }

    public static function delete($filePath)
    {
        return File::delete($filePath);
    }

    public static function updateUpload($file, $oldFilePath)
    {
        File::delete($oldFilePath);
        return $file->move('file', time() . '.' .  $file->extension());
    }
}
