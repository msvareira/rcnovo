<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicosOS extends Model
{
    use HasFactory;

    protected $table = 'servicos_os';

    protected $fillable = [
        'ordem_servico_id',
        'valor',
        'descricao_execucao',
        'duracao',
    ];
}
