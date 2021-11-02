<?php

namespace App\Console\Services;

use App\Exceptions\UploadExternalFileException;
use Illuminate\Http\File;

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

        $path = public_path('storage/tmp_files/');
        if (!is_dir($path)) {
            mkdir($path);
        }

        $pathToFile = 'storage/tmp_files/' . $fileName;
        $isUploaded = file_put_contents(public_path($pathToFile), $content);
        if (!$isUploaded) {
            throw new UploadExternalFileException('Unknown error occurred while uploading the external file.');
        }

        return new File(storage_path() . '/app/public/tmp_files/' . $fileName, $fileName);
    }
}
