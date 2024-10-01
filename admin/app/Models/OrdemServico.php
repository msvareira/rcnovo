<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Clientes;
use App\Models\User;
use App\Models\Funcionarios;

class OrdemServico extends Model
{
    use HasFactory;
    
    protected $table = 'ordem_servico';

    protected $fillable = [
        'cliente_id',
        'user_id',
        'funcionario_id',
        'data',
        'tipo',
        'descricao',
        'observacao',
        'Filial',
        'solicitante',
        'status'
    ];

    public function cliente()
    {
        return $this->belongsTo(Clientes::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function funcionario()
    {
        return $this->belongsTo(Funcionarios::class);
    }

    public function servicos()
    {
        return $this->belongsToMany(Servicos::class, 'servicos_os', 'ordem_servico_id', 'servico_id')->withPivot(['valor','descricao_execucao', 'duracao']);
    }


}
