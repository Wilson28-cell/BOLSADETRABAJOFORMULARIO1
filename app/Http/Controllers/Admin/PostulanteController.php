<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UseCases\Admin\PostulanteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class PostulanteController extends Controller
{
    public function __construct(private PostulanteService $postulanteService)
    {
    }

    public function postulantes()
    {
        $ofertas = $this->postulanteService->listOffersWithCounts();

        return view('admin.postulantes', compact('ofertas'));
    }

    public function verPostulantes($id)
    {
        $postulantes = $this->postulanteService->getPostulantesByOffer($id);

        return view('admin.ver-postulantes', compact('postulantes'));
    }

    public function verCV($id)
    {
        $cv = $this->postulanteService->getPostulacionById($id);

        if (!$cv || !file_exists(public_path($cv->curriculum_pdf))) {
            abort(404, 'Currículum no encontrado');
        }

        return response()->file(public_path($cv->curriculum_pdf), ['Content-Disposition' => 'inline']);
    }

    public function visualizarCV($id)
    {
        $cv = $this->postulanteService->getPostulacionById($id);

        if (!$cv || !file_exists(public_path($cv->curriculum_pdf))) {
            abort(404, 'Currículum no encontrado');
        }

        return view('admin.visualizar-cv', compact('cv'));
    }

    public function descargarCV($id)
    {
        $cv = $this->postulanteService->getPostulacionById($id);

        if (!$cv || !file_exists(public_path($cv->curriculum_pdf))) {
            abort(404, 'Currículum no encontrado');
        }

        return response()->download(public_path($cv->curriculum_pdf));
    }

    public function descargarTodosCV($id)
    {
        $zipPath = $this->postulanteService->buildZipForOffer($id);

        if (!$zipPath) {
            return back()->with('error', 'No se encontraron postulaciones para este ID.');
        }

        return response()->download($zipPath, basename($zipPath))->deleteFileAfterSend(true);
    }

}
