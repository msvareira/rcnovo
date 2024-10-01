<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contatos;

class ContatosController extends Controller
{
    //

    public function index()
    {
        $contatos = Contatos::orderby('nome')->get();
        return view('contatos.index', ['contatos' => $contatos]);
    }

    public function form(Request $request)
    {
        $contato = new Contatos();

        if ($request->id) {
            $contato = Contatos::find($request->id);
        }

        $clientes = \App\Models\Clientes::orderby('razao')->get();

        return view('contatos.form', ['contato' => $contato, 'clientes' => $clientes]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'nome' => 'required|string|max:255',
            'fone1' => 'required|string|max:255',
            'fone2' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255',
            'cliente_id' => 'required|integer',
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'nome.max' => 'O nome não pode ter mais que 255 caracteres.',
            'fone1.required' => 'O telefone é obrigatório.',
            'fone1.max' => 'O telefone não pode ter mais que 255 caracteres.',
            'fone2.max' => 'O telefone não pode ter mais que 255 caracteres.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ser um endereço de email válido.',
            'email.max' => 'O email não pode ter mais que 255 caracteres.',
            'cliente_id.required' => 'O cliente é obrigatório.',
            'cliente_id.integer' => 'O cliente deve ser um número inteiro.',
        ]);

        if ($request->id) {
            $contato = Contatos::find($request->id);
        } else {
            $contato = new Contatos();
        }

        $contato->nome = $request->nome;
        $contato->fone1 = $request->fone1;
        $contato->fone2 = $request->fone2;
        $contato->email = $request->email;
        $contato->cliente_id = $request->cliente_id;

        $contato->save();

        return redirect()->route('contatos.index')->with('success', 'Contato salvo com sucesso!');;
    }

    public function destroy($id)
    {
        $contato = Contatos::find($id);

        if ($contato) {
            $contato->delete();
        }

        session()->flash('success', 'Contato deletado com sucesso!');

        return redirect()->route('contatos.index');
    }
    

}
