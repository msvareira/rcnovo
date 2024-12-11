<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Veiculos;

class ManutencoesVeiculos extends Model
{
    use HasFactory;

    protected $table = 'manutencoes_veiculos';

    protected $fillable = [
        'veiculo_id',
        'data',
        'tipo_servico',
        'custo',
        'proxima_manutencao',
    ];

    public function veiculo()
    {
        return $this->belongsTo(Veiculos::class);
    }
}
