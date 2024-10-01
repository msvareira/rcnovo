<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Utils;
use App\Models\Funcionarios;
use Illuminate\Http\Request;

class FuncionariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $funcionarios = Funcionarios::all();

        return view('funcionarios.index', ['funcionarios' => $funcionarios]);
    }

    public function destroy(Request $request)
    {
        try {
            $funcionario = Funcionarios::find($request->id);
            $funcionario->delete();
    
                return redirect()->route('funcionarios.index')->with('success', 'Funcionário excluído com sucesso!');
            } catch (\Exception $e) {
                return redirect()->route('funcionarios.index')->with('error', 'Não foi possível excluir o funcionário!');
        }

    }

    public function form(Request $request)
    {
        $funcionario = new Funcionarios();

        if ($request->id) {
            $funcionario = Funcionarios::find($request->id);
        }       

        return view('funcionarios.form', ['funcionario' => $funcionario]);
    }

    public function store(Request $request)
    {


        $request->validate([
            'nome' => 'required|string|max:255',
            'rg' => 'nullable|string|max:50|unique:funcionarios,rg,' . $request->id,
            'cpf' => 'nullable|string|max:50|unique:funcionarios,cpf,' . $request->id,
            'carta_motorista' => 'nullable|string|max:50|unique:funcionarios,carta_motorista,' . $request->id,
            'carga_horaria_dia' => 'nullable',
            'valor_hora_extra' => 'nullable',
            'salario' => 'nullable',
            'digital' => 'nullable|string',
            'cod_cracha' => 'nullable|string|max:255|unique:funcionarios,cod_cracha,' . $request->id,
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.string' => 'O campo nome deve ser uma string.',
            'nome.max' => 'O campo nome não pode ter mais que 255 caracteres.',
            'rg.string' => 'O campo RG deve ser uma string.',
            'rg.max' => 'O campo RG não pode ter mais que 50 caracteres.',
            'rg.unique' => 'O RG informado já está em uso.',
            'cpf.string' => 'O campo CPF deve ser uma string.',
            'cpf.max' => 'O campo CPF não pode ter mais que 50 caracteres.',
            'cpf.unique' => 'O CPF informado já está em uso.',
            'carta_motorista.string' => 'O campo carta de motorista deve ser uma string.',
            'carta_motorista.max' => 'O campo carta de motorista não pode ter mais que 50 caracteres.',
            'carta_motorista.unique' => 'A carta de motorista informada já está em uso.',
            'digital.string' => 'O campo digital deve ser uma string.',
            'cod_cracha.string' => 'O campo código do crachá deve ser uma string.',
            'cod_cracha.max' => 'O campo código do crachá não pode ter mais que 255 caracteres.',
            'cod_cracha.unique' => 'O código do crachá informado já está em uso.',
        ]);
        


        $funcionario = new Funcionarios();

        if ($request->id) {
            $funcionario = Funcionarios::find($request->id);
        }

        unset($request['_token']);
        
        if(isset($request['valor_hora_extra'])){
            $request['valor_hora_extra'] = Utils::formatanumerodb($request['valor_hora_extra']);
        }

        if(isset($request['salario'])){
            $request['salario'] = Utils::formatanumerodb($request['salario']);            
        }

        $funcionario->fill($request->all());
        $funcionario->save();

        return redirect()->route('funcionarios.index')->with('success', 'Funcionário salvo com sucesso!');
    }

}
