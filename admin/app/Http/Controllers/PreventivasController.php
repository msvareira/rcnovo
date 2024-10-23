<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Preventivas;
use App\Models\Clientes;
use App\Models\Funcionarios;
use App\Models\AnexosPreventiva;

class PreventivasController extends Controller
{
    //

    public function index()
    {
        $preventivas = Preventivas::with(['cliente','funcionario'])->orderbyRaw('if(status = "Concluído","B","A"), data_execucao desc')->get();
        return view('preventivas.index', ['preventivas' => $preventivas]);
    }

    public function form(Request $request)
    {
        $preventiva = new Preventivas();

        if ($request->id) {
            $preventiva = Preventivas::find($request->id);
        }       

        $clientes = Clientes::where('tipo_cadastro', 'cliente')->orderby('razao')->get();
        $funcionarios = Funcionarios::orderby('nome')->get();

        return view('preventivas.form', ['preventiva' => $preventiva, 'clientes' => $clientes, 'funcionarios' => $funcionarios]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'cliente_id' => 'required',
            'prazo' => 'required|integer',
            'data_execucao' => 'required|date',
        ], [
            'cliente_id.required' => 'O cliente é obrigatório.',
            'prazo.required' => 'O prazo é obrigatório.',
            'prazo.integer' => 'O prazo deve ser um número inteiro.',
            'data_execucao.required' => 'A data de execução é obrigatória.',
        ]);        

        if ($request->id) {
            $preventiva = Preventivas::find($request->id);
        }else{
            $preventiva = new Preventivas();
        }

        if (!$request->has('status')) {
            $preventiva->status = 'Não Executada';            
        }else{
            $preventiva->status = $request->status;
        }

        $preventiva->cliente_id = $request->cliente_id;
        $preventiva->prazo = $request->prazo;
        $preventiva->descricao = $request->descricao;
        $preventiva->data_execucao = $request->data_execucao;
        $preventiva->save();

        return redirect()->route('preventivas.index')->with('success', 'Preventiva salva com sucesso.');
    }

    public function destroy($id)
    {
        $preventiva = Preventivas::find($id);
        $preventiva->delete();

        return redirect()->route('preventivas.index')->with('success', 'Preventiva excluída com sucesso.');
    }

    public function executar(Request $request)
    {
        $request->validate([
            'preventiva_id' => 'required|exists:preventivas,id',
            'funcionario' => 'required|exists:funcionarios,id',
            'descricao' => 'required|string',
            'data_execucao' => 'required|date',
            'anexos.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'preventiva_id.required' => 'A preventiva é obrigatória.',
            'preventiva_id.exists' => 'A preventiva selecionada não existe.',
            'funcionario.required' => 'O funcionário é obrigatório.',
            'funcionario.exists' => 'O funcionário selecionado não existe.',
            'descricao.required' => 'A descrição é obrigatória.',
            'descricao.string' => 'A descrição deve ser um texto.',
            'data_execucao.required' => 'A data de execução é obrigatória.',
            'data_execucao.date' => 'A data de execução deve ser uma data válida.',
            'anexos.*.required' => 'Os anexos são obrigatórios.',
            'anexos.*.image' => 'Os anexos devem ser imagens.',
            'anexos.*.mimes' => 'Os anexos devem ser do tipo: jpeg, png, jpg, gif, svg.',
            'anexos.*.max' => 'Os anexos não devem ser maiores que 2048 kilobytes.',
        ]);

        $preventiva = Preventivas::find($request->preventiva_id);
        $preventiva->funcionario_id = $request->funcionario;
        $descricao_original = $preventiva->descricao;
        $preventiva->descricao = $request->descricao;
        $preventiva->status = 'Concluído';
        $preventiva->save();

        // Save attachments
        if ($request->hasFile('anexos')) {
            foreach ($request->file('anexos') as $file) {
                $path = $file->store('anexos_preventiva', 'public');
                \App\Models\AnexosPreventiva::create([
                    'preventiva_id' => $preventiva->id,
                    'file_path' => $path,
                ]);
            }
        }

        // Create a new Preventiva with the execution date based on the prazo
        $novaPreventiva = $preventiva->replicate();
        $novaPreventiva->status = 'Não Executada';
        $novaPreventiva->descricao = $descricao_original;
        $novaPreventiva->data_execucao = \Carbon\Carbon::parse($request->data_execucao)->addDays($preventiva->prazo);
        $novaPreventiva->save();

        return response()->json(['success' => 'Preventiva executada com sucesso.']);
    }

    public function print($id)
    {
        $preventiva = Preventivas::with(['cliente', 'funcionario'])->find($id);

        if (!$preventiva) {
            return redirect()->route('preventivas.index')->with('error', 'Preventiva não encontrada.');
        }

        $pdf = new \App\Http\Relatorios\PreventivaPDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->setPreventiva($preventiva);
        $pdf->PreventivaTable();
        $pdf->Output();

        exit;
    }


}
