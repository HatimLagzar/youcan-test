<?php

namespace App\Console\Services;

use App\Exceptions\UploadExternalFileException;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class UploadService
{
    /**
     * Write the content of the file in the url temporarily
     *
     * @param string $url
     * @return File
     * @throws UploadExternalFileException
     */
    public function uploadExternalResource(string $url): File
    {
        $pathInfo = pathinfo($url);
        if (!isset($pathInfo['extension']) || empty($pathInfo['extension'])) {
            throw new UploadExternalFileException('Enter a valid file URL');
        }

        $extension = $pathInfo['extension'];
        $fileName = uniqid() . '-' . now()->timestamp . '.' . $extension;
        $content = file_get_contents($url);
        if (!$content) {
            throw new UploadExternalFileException('Invalid URL');
        }

        if (!Storage::exists('public/tmp_files/')) {
            Storage::makeDirectory('public/tmp_files');
        }

        $pathToFile = 'public/tmp_files/' . $fileName;
        $isUploaded = Storage::put($pathToFile, $content);
        if (!$isUploaded) {
            throw new UploadExternalFileException('Unknown error occurred while uploading the external file.');
        }
        return new File(storage_path() . '/app/' . $pathToFile, $fileName);
    }
}
