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
        $funcionarios = Funcionarios::with('user')->get();

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

    public function storeUser(Request $request)
    {
        $request->validate([
            'funcionario_id' => 'required|exists:funcionarios,id',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:4',
        ], [
            'funcionario_id.required' => 'O campo funcionário é obrigatório.',
            'funcionario_id.exists' => 'O funcionário selecionado é inválido.',
            'username.required' => 'O campo usuário é obrigatório.',
            'username.string' => 'O campo usuário deve ser uma string.',
            'username.max' => 'O campo usuário não pode ter mais que 255 caracteres.',
            'username.unique' => 'O usuário informado já está em uso.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.string' => 'O campo senha deve ser uma string.',
            'password.min' => 'O campo senha deve ter no mínimo 4 caracteres.',
        ]);

        $user = new \App\Models\User();
        $user->funcionario_id = $request->funcionario_id;
        $user->name = Funcionarios::find($request->funcionario_id)->nome;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('funcionarios.index')->with('success', 'Usuário criado com sucesso!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'funcionario_id' => 'required|exists:funcionarios,id',
            'new_password' => 'required|string|min:4',
        ], [
            'funcionario_id.required' => 'O campo funcionário é obrigatório.',
            'funcionario_id.exists' => 'O funcionário selecionado é inválido.',
            'new_password.required' => 'O campo nova senha é obrigatório.',
            'new_password.string' => 'O campo nova senha deve ser uma string.',
            'new_password.min' => 'O campo nova senha deve ter no mínimo 4 caracteres.',
        ]);

        $user = \App\Models\User::where('funcionario_id', $request->funcionario_id)->first();
        if ($user) {
            $user->password = bcrypt($request->new_password);
            $user->save();

            return redirect()->route('funcionarios.index')->with('success', 'Senha atualizada com sucesso!');
        } else {
            return redirect()->route('funcionarios.index')->with('error', 'Usuário não encontrado!');
        }
    }

}
