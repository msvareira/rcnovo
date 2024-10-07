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

    public function ordemServico()
    {
        return $this->belongsTo(OrdemServico::class);
    }

    public function servico()
    {
        return $this->belongsTo(Servicos::class);
    }

    public function anexos()
    {
        return $this->hasMany(ServicosOSAnexos::class);
    }
}
