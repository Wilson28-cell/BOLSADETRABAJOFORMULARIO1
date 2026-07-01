<?php

namespace App\UseCases\Common;

use Illuminate\Http\UploadedFile;

interface FileUploadInterface
{
    public function storePublicFile(UploadedFile $file, string $directory): string;

    public function deletePublicFile(?string $relativePath): bool;

    public function copyPublicFile(string $sourceRelativePath, string $targetDirectory): ?string;
}
