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
use App\Models\ServicosOS;
use Illuminate\Support\Facades\Mail;
use App\Models\ServicosOSAnexos;


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

                ServicosOS::
                      where('ordem_servico_id', $ordem_servico->id)
                    ->whereNotIn('id', array_column($data['servicos'], 'id'))                
                    ->delete();
            } else {
                $ordem_servico = OrdemServico::create($data);
            }

            foreach ($data['servicos'] as $servico) {

                $existente = ServicosOS::find($servico['id']);

                if(isset($existente)){
                    continue;
                }

                $ordem_servico->servicos()->attach($servico['id'], [
                    'created_at' => now(),
                    'valor' => Utils::formatanumerodb($servico['valor']),
                    'descricao_execucao' => $servico['descricao_execucao'],
                    'duracao' => $servico['duracao']
                ]);
                
                $servico_os = $ordem_servico
                                ->servicos()
                                ->where('servico_id', $servico['id'])
                                ->where('ordem_servico_id', $ordem_servico->id)
                                ->where('descricao_execucao', $servico['descricao_execucao'])
                                ->first()->pivot;                

                if (isset($servico['anexos'])) {
                    foreach ($servico['anexos'] as $anexo) {

                        $originalName = $anexo->getClientOriginalName();

                        if ($anexo instanceof \Illuminate\Http\UploadedFile) {
                            $path = $anexo->store('anexos', 'public');
                            ServicosOSAnexos::create([
                                'servico_os_id' => $servico_os->id,
                                'arquivo' => $path,
                                'descricao' => $originalName
                            ]);
                        } else {
                            ServicosOSAnexos::create([
                                'servico_os_id' => $servico_os->id,
                                'arquivo' => $anexo['anexo'],
                                'descricao' => $originalName
                            ]);
                        }
                    }
                }                
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
        $ordem_servico = OrdemServico::with(['servicos'])->find($id);

        if (!$ordem_servico) {
            return response()->json(['error' => 'Ordem de serviço não encontrada'], 404);
        }

        $anexos = [];

        foreach ($ordem_servico->servicos as $servico) {            
            $anexoAtual = ServicosOSAnexos::where('servico_os_id', $servico->pivot->id)->get(); 
            $anexoAtual = $anexoAtual->map(function ($anexo) {
                return [
                    'id' => $anexo->id,
                    'arquivo' => $anexo->arquivo,
                    'descricao' => $anexo->descricao,
                    'url' => asset('storage/' . $anexo->arquivo)
                ];
            });
            $anexos[$servico->pivot->id] =  $anexoAtual; 
        }

        $response = [
            'cliente_id' => $ordem_servico->cliente_id,
            'funcionario_id' => $ordem_servico->funcionario_id,
            'data' => $ordem_servico->data,
            'solicitante' => $ordem_servico->solicitante,
            'servicos' => $ordem_servico->servicos->map(function ($servico) use ($anexos) {
                return [
                    'id' => $servico->pivot->id,
                    'descricao' => $servico->descricao,
                    'descricao_execucao' => empty($servico->pivot->descricao_execucao)?'':$servico->pivot->descricao_execucao,
                    'valor' => Utils::formatarnumero($servico->pivot->valor),
                    'duracao' => isset($servico->pivot->duracao)?$servico->pivot->duracao:0,
                    'anexos' => $anexos[$servico->pivot->id],
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
        $pdf->setOrdemServico($ordem_servico);
        $pdf->AddPage();
        $pdf->OrdemServicoTable();
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

    public function concluir($id)
    {
        $ordem_servico = OrdemServico::find($id);

        if (!$ordem_servico) {
            return redirect()->route('os.index')->with('error', 'Ordem de serviço não encontrada');
        }

        $ordem_servico->status = 'Concluída';
        $ordem_servico->save();

        return redirect()->route('os.index')->with('success', 'Ordem de serviço concluída com sucesso');
    }

    public function reabrir($id)
    {
        $ordem_servico = OrdemServico::find($id);

        if (!$ordem_servico) {
            return redirect()->route('os.index')->with('error', 'Ordem de serviço não encontrada');
        }

        $ordem_servico->status = 'Aberta';
        $ordem_servico->save();

        return redirect()->route('os.index')->with('success', 'Ordem de serviço reaberta com sucesso');
    }
}
