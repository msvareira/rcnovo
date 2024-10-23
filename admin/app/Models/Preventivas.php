<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preventivas extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'funcionario_id',
        'status',
        'prazo',
        'descricao',
        'data_execucao',
    ];

    public function cliente()
    {
        return $this->belongsTo(Clientes::class);
    }

    public function funcionario()
    {
        return $this->belongsTo(Funcionarios::class);
    }

}
