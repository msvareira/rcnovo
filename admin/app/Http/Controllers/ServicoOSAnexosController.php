<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServicosOSAnexos;

class ServicoOSAnexosController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        $anexo = ServicosOSAnexos::create($data);
        return response()->json($anexo);
    }

    public function destroy($id)
    {
        $anexo = ServicosOSAnexos::find($id);
        $anexo->delete();
        return response()->json(['message' => 'Anexo removido com sucesso']);
    }

    public function download($id)
    {
        $anexo = ServicosOSAnexos::find($id);
        return response()->download(storage_path('app/' . $anexo->arquivo));
    }

}
