<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ManutencoesVeiculos;
use App\Models\ReservasVeiculos;
use App\Models\Abastecimentos;

class Veiculos extends Model
{
    use HasFactory;

    protected $table = 'veiculos';

    protected $fillable = [
        'placa',
        'modelo',
        'ano',
        'quilometragem_inicial',
        'data_seguro',
        'data_inspecao',
        'status',
    ];

    public function manutencoes()
    {
        return $this->hasMany(ManutencoesVeiculos::class, 'veiculo_id');
    }

    public function reservas()
    {
        return $this->hasMany(ReservasVeiculos::class, 'veiculo_id');
    }

    public function abastecimentos()
    {
        return $this->hasMany(Abastecimentos::class, 'veiculo_id');
    }
}
