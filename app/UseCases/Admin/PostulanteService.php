<?php

namespace App\UseCases\Admin;

use Illuminate\Support\Facades\DB;
use ZipArchive;

class PostulanteService
{
    public function listOffersWithCounts()
    {
        return DB::table('publicaciones_publicas as p')
            ->leftJoin('postulaciones as po', 'p.id_publica', '=', 'po.id_publica')
            ->select('p.id_publica', 'p.nombre_empresa', 'p.titulo_puesto', DB::raw('COUNT(po.id_postulacion) as total_postulados'))
            ->groupBy('p.id_publica', 'p.nombre_empresa', 'p.titulo_puesto')
            ->get();
    }

    public function getPostulantesByOffer(int $publicaId)
    {
        return DB::table('postulaciones as po')
            ->join('postulantes as p', 'po.id_postulante', '=', 'p.id_postulante')
            ->where('po.id_publica', $publicaId)
            ->select('p.*', 'po.id_postulacion', 'po.curriculum_pdf')
            ->get();
    }

    public function getPostulacionById(int $id)
    {
        return DB::table('postulaciones')->where('id_postulacion', $id)->first();
    }

    public function buildZipForOffer(int $publicaId): ?string
    {
        $postulaciones = DB::table('postulaciones')->where('id_publica', $publicaId)->get();

        if ($postulaciones->isEmpty()) {
            return null;
        }

        $zipFileName = 'CV_Postulantes_' . $publicaId . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        if (!is_dir(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return null;
        }

        foreach ($postulaciones as $postulacion) {
            $path = public_path($postulacion->curriculum_pdf);
            if (file_exists($path) && is_file($path)) {
                $zip->addFile($path, basename($path));
            }
        }

        $zip->close();

        return $zipPath;
    }
}
