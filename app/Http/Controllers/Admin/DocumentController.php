<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UseCases\Admin\AdminDocumentService;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function __construct(private AdminDocumentService $adminDocumentService)
    {
    }

    public function aprobar(Request $request, $id)
    {
        $this->adminDocumentService->approvePublication(
            $id,
            $request->file('documento_validacion_subgerencia')
        );

        return redirect('admin/validacion-formularios')->with('success', 'Solicitud aprobada correctamente');
    }

    public function rechazar($id)
    {
        $this->adminDocumentService->rejectPublication($id);

        return redirect('admin/validacion-formularios')->with('warning', 'Solicitud rechazada correctamente');
    }

    public function restaurar($id)
    {
        try {
            $this->adminDocumentService->restorePublication($id);
            return back()->with('success', 'Registro restaurado correctamente');
        } catch (\RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function subirDocumento(Request $request, $id)
    {
        if (!$request->hasFile('documento_pdf')) {
            return back();
        }

        $this->adminDocumentService->uploadApprovalDocument($id, $request->file('documento_pdf'));

        return back();
    }

}
