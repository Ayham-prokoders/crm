<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function uploadFile(Request $request)
    {
        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'required'
            ]);
            $file = $request->file('file');
            if (!blank($file)) {
                $extension = $file->getClientOriginalExtension();
                $randomName = Str::random(32);
                $fileName = preg_replace('/[^A-Za-z0-9_\-]/', '', $randomName) . '.' . $extension;
    
                return $file->storeAs('media', $fileName, 'public');
            }
        }

    }

    public function uploadFileWithPath(Request $request)
    {
        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'required|file',
            ]);
    
            $file = $request->file('file');
            if (!blank($file)) {
                $extension = $file->getClientOriginalExtension();
                $randomName = Str::random(32);
                $fileName = preg_replace('/[^A-Za-z0-9_\-]/', '', $randomName) . '.' . $extension;
    
                $path = $file->storeAs('media', $fileName, 'public');
                return asset('storage/' . $path);
            }
        }
    
        return null;
    }


    public function deleteFile(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            return File::delete($file);

        }


    }
}
