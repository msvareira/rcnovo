<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Clientes::where('tipo_cadastro', 'cliente')->orderby('razao')->get();
        return view('clientes.index', ['clientes' => $clientes]);
    }

    public function form(Request $request)
    {
        $cliente = new Clientes();

        if ($request->id) {
            $cliente = Clientes::find($request->id);
        }       

        return view('clientes.form', ['cliente' => $cliente]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'razao' => 'nullable|string|max:255',
            'fantasia' => 'nullable|string|max:255',
            'cpf_cnpj' => 'nullable|string|max:255|unique:clientes,cpf_cnpj,' . $request->id . ',id,tipo_cadastro,cliente',
            'rg_ie' => 'nullable|string|max:255|unique:clientes,rg_ie,' . $request->id . ',id,tipo_cadastro,cliente',
            'email' => 'nullable|string|email|max:255',
            'cep' => 'nullable|string|max:255',
            'estado' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:255',
            'rua' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:255',
            'complemento' => 'nullable|string|max:255',
            'cod_ibge' => 'nullable|string|max:255',
            'fone1' => 'nullable|string|max:255',
            'fone2' => 'nullable|string|max:255',
            'fone3' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'datacad' => 'nullable|date',
            'ult_alteracao' => 'nullable|string|max:255',
            'obs' => 'nullable|string',
        ], [
            'razao.max' => 'A razão social não pode ter mais que 255 caracteres.',
            'fantasia.max' => 'O nome fantasia não pode ter mais que 255 caracteres.',
            'cpf_cnpj.unique' => 'O CPF/CNPJ já está cadastrado para outro cliente.',
            'rg_ie.unique' => 'O RG/IE já está cadastrado para outro cliente.',
            'email.email' => 'O e-mail deve ser um endereço de e-mail válido.',
            'email.max' => 'O e-mail não pode ter mais que 255 caracteres.',
            'cep.max' => 'O CEP não pode ter mais que 255 caracteres.',
            'estado.max' => 'O estado não pode ter mais que 255 caracteres.',
            'cidade.max' => 'A cidade não pode ter mais que 255 caracteres.',
            'bairro.max' => 'O bairro não pode ter mais que 255 caracteres.',
            'rua.max' => 'A rua não pode ter mais que 255 caracteres.',
            'numero.max' => 'O número não pode ter mais que 255 caracteres.',
            'complemento.max' => 'O complemento não pode ter mais que 255 caracteres.',
            'cod_ibge.max' => 'O código IBGE não pode ter mais que 255 caracteres.',
            'fone1.max' => 'O telefone 1 não pode ter mais que 255 caracteres.',
            'fone2.max' => 'O telefone 2 não pode ter mais que 255 caracteres.',
            'fone3.max' => 'O telefone 3 não pode ter mais que 255 caracteres.',
            'website.max' => 'O website não pode ter mais que 255 caracteres.',
            'datacad.date' => 'A data de cadastro deve ser uma data válida.',
            'ult_alteracao.max' => 'A última alteração não pode ter mais que 255 caracteres.',
            'obs.string' => 'As observações devem ser um texto válido.',
        ]);

        $cliente = new Clientes();

        if ($request->id) {
            $cliente = Clientes::find($request->id);
        }

        unset($request['_token']);
        
        $cliente->fill($request->all());
        $cliente->tipo_cadastro = 'cliente';
        $cliente->save();

        return redirect()->route('clientes.index')->with('success', 'Cliente salvo com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cliente = Clientes::find($id);

        if (!$cliente) {
            return redirect()->route('clientes.index')->with('error', 'Cliente não encontrado.');
        }

        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente deletado com sucesso!');
    }

}
