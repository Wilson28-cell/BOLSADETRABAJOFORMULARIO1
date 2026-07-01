<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FileUploadService
{
    public function storePublicFile(UploadedFile $file, string $directory): string
    {
        $directory = trim($directory, '/');
        $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $destination = public_path($directory);

        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        // Intentar almacenar usando el disco 'public' (usa streams y evita problemas con tmp en Windows)
        try {
            $stored = Storage::disk('public')->putFileAs($directory, $file, $filename);
            if ($stored) {
                return 'storage/' . ltrim($stored, '/');
            }
        } catch (\Exception $e) {
            // intentar fallback con stream
            try {
                $pathname = $file->getPathname();
                $exists = $pathname ? file_exists($pathname) : false;
                $isfile = $pathname ? is_file($pathname) : false;

                // Loguear detalle para diagnóstico
                Log::error('Upload temp file check', [
                    'pathname' => $pathname,
                    'exists' => $exists,
                    'is_file' => $isfile,
                    'upload_tmp_dir' => ini_get('upload_tmp_dir'),
                    'upload_max_filesize' => ini_get('upload_max_filesize'),
                    'post_max_size' => ini_get('post_max_size'),
                ]);

                if (empty($pathname) || !$exists) {
                    throw new \RuntimeException('Archivo temporal no encontrado en el servidor: ' . ($pathname ?: 'ruta vacía') . '. Revisa `upload_tmp_dir` en php.ini, permisos de la carpeta tmp y que el antivirus no borre temporales.');
                }

                $stream = @fopen($pathname, 'r');
                if ($stream) {
                    $path = rtrim($directory, '/') . '/' . $filename;
                    Storage::disk('public')->put($path, $stream);
                    if (is_resource($stream)) {
                        fclose($stream);
                    }
                    return 'storage/' . ltrim($path, '/');
                }
                Log::error('Unable to open upload temp file for reading', ['pathname' => $pathname]);
                throw new \RuntimeException('No se pudo abrir el archivo temporal para lectura: ' . $pathname);
            } catch (\RuntimeException $e) {
                // Re-lanzar con mensaje claro para el usuario
                throw $e;
            } catch (\Exception $_) {
                // convertir a mensaje genérico para no exponer stack de vendor
                throw new \RuntimeException('Error al procesar el archivo subido. Revisa configuraciones de PHP y permisos.');
            }
        }

        if (!method_exists($file, 'isValid') || !$file->isValid()) {
            $error = method_exists($file, 'getError') ? $file->getError() : 'unknown';
            throw new \RuntimeException('Archivo cargado inválido. Código de error: ' . $error);
        }

        // Preferir usar getRealPath o pathname
        $sourcePath = $file->getRealPath() ?: $file->getPathname();
        $targetPath = $destination . DIRECTORY_SEPARATOR . $filename;

        if ($sourcePath && File::exists($sourcePath) && is_file($sourcePath)) {
            if (!File::copy($sourcePath, $targetPath)) {
                throw new \RuntimeException('No se pudo copiar el archivo subido al destino.');
            }
            return $directory . '/' . $filename;
        }

        // Fallback final: intentar mover (método nativo de UploadedFile)
        try {
            $file->move($destination, $filename);
        } catch (\Exception $e) {
            throw new \RuntimeException('No se pudo mover el archivo subido: ' . $e->getMessage());
        }

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
