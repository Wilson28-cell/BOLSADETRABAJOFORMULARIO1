<?php

namespace App\UseCases\Common;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FileUploadService implements FileUploadInterface
{
    public function storePublicFile(UploadedFile $file, string $directory): string
    {
        $directory = trim($directory, '/');
        $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $destination = public_path($directory);

        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $file->move($destination, $filename);

        return $directory . '/' . $filename;
    }

    public function deletePublicFile(?string $relativePath): bool
    {
        if (empty($relativePath)) {
            return false;
        }

        $path = public_path($relativePath);

        if (File::exists($path)) {
            return File::delete($path);
        }

        return false;
    }

    public function copyPublicFile(string $sourceRelativePath, string $targetDirectory): ?string
    {
        $sourcePath = public_path($sourceRelativePath);
        if (!File::exists($sourcePath) || !is_file($sourcePath)) {
            return null;
        }

        $targetDirectory = trim($targetDirectory, '/');
        $destinationDirectory = public_path($targetDirectory);

        if (!File::exists($destinationDirectory)) {
            File::makeDirectory($destinationDirectory, 0755, true);
        }

        $fileName = basename($sourceRelativePath);
        $targetPath = $destinationDirectory . DIRECTORY_SEPARATOR . $fileName;

        if (!File::copy($sourcePath, $targetPath)) {
            return null;
        }

        return $targetDirectory . '/' . $fileName;
    }
}
