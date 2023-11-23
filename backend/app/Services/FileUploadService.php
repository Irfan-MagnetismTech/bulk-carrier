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
                $fileName = $path . '/' . $myRandomString . time() . '.' . $file->getClientOriginalExtension();
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

    // note : new data, new attachment filse, file storing path, previous data which is get from database and, field name if it's not attachment
    public function handleMultipleFiles(string $path, array $newData, array $attachments, array $oldData = null, string $field = 'attachment'): array|null
    {
        try {
            $results = [];
            $oldLength =0;
            if($oldData != null){
                $oldLength = count($oldData);
            }

            foreach ($newData as $key => $data) {

                if (isset($attachments[$key])) {
                    if ($key < $oldLength) {
                        $this->deleteFile($oldData[$key]->attachment);
                    }
                    $attachment = $this->handleFile($attachments[$key], $path);
                    $data[$field] = $attachment;
                }
                $results[$key] = $data;
            }

            return $results;
        } catch (ValidationException) {

            return null;
        }
    }
}
