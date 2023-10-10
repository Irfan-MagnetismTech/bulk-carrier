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

    public function deleteFile($oldFile)
    {
        if ($oldFile) {
            $oldPath = public_path($oldFile);
            if (file_exists($oldPath)) {
                File::delete($oldPath);
            }
        }
    }


    
    // note : new data, file storing path, previous data which is get from database and field name is not like as attachment 
    public function handleMultipleFile($path, $newData , $oldData = null, $field='attachment',)
    {
        try {
            $results=[];
            $oldLength= count($oldData);
    
            foreach($newData as $key=>$value){
                $data = $value->except(
                    $field,
                );
                if(isset($value->attachment)){
                    if($key < $oldLength){
                        $this->fileUpload->deleteFile($oldData[$key]->attachment);
                    }
                    $attachment = $this->fileUpload->handleFile($value->attachment, $path);
                    $data[$field] = $attachment;
                }
                $results[$key]= $data;
            }
            return $results;
            
        } catch (ValidationException) {
            return null;
        }
    }
}
