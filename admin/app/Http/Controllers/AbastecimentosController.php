<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Abastecimentos;
use Illuminate\Support\Facades\Validator;
use App\Http\Helpers\Utils;
use App\Models\Veiculos;
use Illuminate\Support\Facades\DB;

class AbastecimentosController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'veiculo_id' => 'required|exists:veiculos,id',
            'quilometragem' => 'required|integer|min:0',
            'litros' => 'required|min:0',
            'custo' => 'required|min:0',
        ], [
            'veiculo_id.required' => 'O veículo é obrigatório.',
            'veiculo_id.exists' => 'O veículo selecionado não existe.',
            'quilometragem.required' => 'A quilometragem é obrigatória.',
            'quilometragem.integer' => 'A quilometragem deve ser um número inteiro.',
            'litros.required' => 'A quantidade de litros é obrigatória.',
            'custo.required' => 'O custo é obrigatório.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $request->merge([
            'litros' => Utils::formatanumerodb($request->input('litros')),
            'custo' => Utils::formatanumerodb($request->input('custo')),
        ]);

        try
        {
            DB::beginTransaction();
            Abastecimentos::create($request->all());
            DB::commit();
            return response()->json(['success' => 'Abastecimento registrado com sucesso.']);
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->json(['errors' => ['Erro ao registrar abastecimento.']], 422);
        }        
    }
}
