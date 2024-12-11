<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Veiculos;
use Illuminate\Support\Facades\Validator;

class VeiculosController extends Controller
{
    //

    public function index()
    {
        $veiculos = Veiculos::with(['manutencoes', 'reservas', 'abastecimentos'])->get();
        return view('veiculos.index', compact('veiculos'));
    }

    public function create()
    {
        return view('veiculos.form');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'placa' => 'required|string|max:10|unique:veiculos',
            'modelo' => 'required|string|max:255',
            'ano' => 'required|integer|min:1900|max:' . date('Y'),
            'quilometragem_inicial' => 'required|integer|min:0',
            'status' => 'required|in:Disponível,Em uso,Em manutenção,Aguardando inspeção',
        ], [
            'placa.required' => 'A placa é obrigatória.',
            'placa.unique' => 'Esta placa já está cadastrada.',
            'modelo.required' => 'O modelo é obrigatório.',
            'ano.required' => 'O ano é obrigatório.',
            'ano.integer' => 'O ano deve ser um número inteiro.',
            'quilometragem_inicial.required' => 'A quilometragem inicial é obrigatória.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser um dos seguintes: Disponível, Em uso, Em manutenção, Aguardando inspeção.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Veiculos::create($request->all());
        return redirect()->route('veiculos.index')->with('success', 'Veículo criado com sucesso!');
    }

    public function show($id)
    {
        $veiculo = Veiculos::with(['manutencoes', 'reservas', 'abastecimentos'])->findOrFail($id);
        return view('veiculos.show', compact('veiculo'));
    }

    public function edit($id)
    {
        $veiculo = Veiculos::findOrFail($id);
        return view('veiculos.form', compact('veiculo'));
    }

    public function update(Request $request, $id)
    {
        $veiculo = Veiculos::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'placa' => 'required|string|max:10|unique:veiculos,placa,' . $veiculo->id,
            'modelo' => 'required|string|max:255',
            'ano' => 'required|integer|min:1900|max:' . date('Y'),
            'quilometragem_inicial' => 'required|integer|min:0',
            'data_inspecao' => 'required|date',
            'status' => 'required|in:Disponível,Em uso,Em manutenção,Aguardando inspeção',
        ], [
            'placa.required' => 'A placa é obrigatória.',
            'placa.unique' => 'Esta placa já está cadastrada.',
            'modelo.required' => 'O modelo é obrigatório.',
            'ano.required' => 'O ano é obrigatório.',
            'ano.integer' => 'O ano deve ser um número inteiro.',
            'quilometragem_inicial.required' => 'A quilometragem inicial é obrigatória.',
            'data_inspecao.required' => 'A data da inspeção é obrigatória.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser um dos seguintes: Disponível, Em uso, Em manutenção, Aguardando inspeção.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $veiculo->update($request->all());
        return redirect()->route('veiculos.index')->with('success', 'Veículo atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $veiculo = Veiculos::findOrFail($id);
        $veiculo->delete();
        return redirect()->route('veiculos.index')->with('success', 'Veículo excluído com sucesso!');
    }
}
