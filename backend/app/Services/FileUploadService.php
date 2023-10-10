<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class FileUploadService
{
    public function handleFile($file, $path, $previousFile = null)
    {
        try {
            if (is_string($file)) return $file;
            $fileName = null;
            if ($file) {
                $myRandomString = Str::random(10);
                $fileName = $path . '/' . $myRandomString . '.' . $file->getClientOriginalExtension();
                $file->move($path, $fileName);
            }

            if ($previousFile) {
                $this->deleteFile($previousFile);
            }
            return $fileName;
        } catch (ValidationException) {
            return null;
        }
    }

    // note : new files, file storing path, previous files which is get from database and field name is not like as attachment 
    public function handleMultipleFile($files, $path, $previousFiles = null, $field='attachment',)
    {
        try {
            if (is_string($files)) return $files;
            $fileNames = null;
            if(is_array($files)){
                $fileNames=[];
                $newData = $files;
                $previousFilesLength= count($previousFiles);
        
                foreach($newData as $key=>$value){
                    $data = $value->except(
                        $field,
                    );
                    if(isset($value->attachment)){
                        if($key < $previousFilesLength){
                            $this->deleteFile($previousFiles[$key]->attachment);
                        }
                        $attachment = $this->handleFile($value->attachment, $path);
                        $data[$field] = $attachment;
                    }
                    $fileNames[$key]= $data;
                }
                return $fileNames;
            }
            return $fileNames;
        } catch (ValidationException) {
            return null;
        }
    }


    public function deleteFile($oldFile)
    {
        if ($oldFile) {
            $oldPath = public_path($oldFile);
            if (file_exists($oldPath)) {
                File::delete($oldPath);
            }
        }
    }
}
