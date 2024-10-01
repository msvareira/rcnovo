<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use Illuminate\Http\Request;
use App\Models\Clientes;
use App\Models\Funcionarios;
use App\Models\Servicos;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Helpers\Utils;
use App\Http\Relatorios\OrdemServicoPDF as PDF;
use Illuminate\Support\Facades\Mail;


class OrdemServicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $ordens = OrdemServico::all();
        $ordens->load('servicos');
        $clientes = Clientes::selectraw('id, concat(coalesce(razao,"")," - ", coalesce(fantasia,"")) as razao')->orderby('razao')->get();
        $funcionarios = Funcionarios::orderBy('nome')->get();
        $servicos = Servicos::orderBy('descricao')->get();

        return view('ordem_servico.index', [
            'ordens' => $ordens,
            'clientes' => $clientes,
            'funcionarios' => $funcionarios,
            'servicos' => $servicos,
        ]);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            $data['user_id'] = Auth::user()->id;

            if (isset($data['id'])) {
                $ordem_servico = OrdemServico::find($data['id']);
                if (!$ordem_servico) {
                    return redirect()->route('os.index')->with('error', 'Ordem de serviço não encontrada');
                }
                $ordem_servico->update($data);
                $ordem_servico->servicos()->detach();
            } else {
                $ordem_servico = OrdemServico::create($data);
            }

            foreach ($data['servicos'] as $servico) {
                $ordem_servico->servicos()->attach($servico['id'], ['created_at' => now(), 'valor' => Utils::formatanumerodb($servico['valor']), 'descricao_execucao' => $servico['descricao_execucao'], 'duracao' => $servico['duracao']]);
            }

            DB::commit();

            return redirect()->route('os.index')->with('success', 'Ordem de serviço salva com sucesso');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('os.index')->with('error', 'Erro ao salvar a ordem de serviço: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $ordem_servico = OrdemServico::find($id);
            $ordem_servico->servicos()->detach();
            $ordem_servico->delete();

            DB::commit();
            return redirect()->route('os.index')->with('success', 'Ordem de serviço excluída com sucesso');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('os.index')->with('error', 'Erro ao excluir a ordem de serviço: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $ordem_servico = OrdemServico::with('servicos')->find($id);

        if (!$ordem_servico) {
            return response()->json(['error' => 'Ordem de serviço não encontrada'], 404);
        }

        $response = [
            'cliente_id' => $ordem_servico->cliente_id,
            'funcionario_id' => $ordem_servico->funcionario_id,
            'data' => $ordem_servico->data,
            'solicitante' => $ordem_servico->solicitante,
            'servicos' => $ordem_servico->servicos->map(function ($servico) {
                return [
                    'id' => $servico->id,
                    'descricao' => $servico->descricao,
                    'descricao_execucao' => empty($servico->pivot->descricao_execucao)?'':$servico->pivot->descricao_execucao,
                    'valor' => Utils::formatarnumero($servico->pivot->valor),
                    'duracao' => isset($servico->pivot->duracao)?$servico->pivot->duracao:0,
                ];
            })
        ];

        return response()->json($response);
    }

    public function print($id)
    {
        $ordem_servico = OrdemServico::with('servicos')->find($id);

        if (!$ordem_servico) {
            return redirect()->route('os.index')->with('error', 'Ordem de serviço não encontrada');
        }


        $pdf = new PDF();
        $pdf->AddPage();
        $pdf->OrdemServicoTable($ordem_servico);
        $pdf->Output();
        exit;

    }

    public function sendEmail($id)
    {
        $ordem_servico = OrdemServico::with('servicos')->find($id);

        if (!$ordem_servico) {
            return redirect()->route('os.index')->with('error', 'Ordem de serviço não encontrada');
        }

        $pdf = new PDF();
        $pdf->AddPage();
        $pdf->OrdemServicoTable($ordem_servico);
        $pdfContent = $pdf->Output('S');
        
        $email = $ordem_servico->cliente->email; // Assuming the Cliente model has an email field

        if(empty($email)){
            $email = 'msvareira@gmail.com';
        }
        
        Mail::send([], [], function ($message) use ($email, $pdfContent) {
            $message->to($email)
                ->subject('Ordem de Serviço')
                ->attachData($pdfContent, 'ordem_servico.pdf', [
                    'mime' => 'application/pdf',
                ])
                ->html('Segue em anexo a ordem de serviço.');
        });

        return redirect()->route('os.index')->with('success', 'Ordem de serviço enviada por email com sucesso');
    }
}
