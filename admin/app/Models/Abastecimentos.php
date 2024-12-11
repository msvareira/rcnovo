<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Veiculos;

class Abastecimentos extends Model
{
    use HasFactory;

    protected $fillable = [
        'veiculo_id',
        'quilometragem',
        'litros',
        'custo',
    ];

    public function veiculo()
    {
        return $this->belongsTo(Veiculos::class);
    }
}
