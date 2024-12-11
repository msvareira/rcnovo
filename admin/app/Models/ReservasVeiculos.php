<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Veiculos;

class ReservasVeiculos extends Model
{
    use HasFactory;

    protected $table = 'reservas_veiculos';

    protected $fillable = [
        'veiculo_id',
        'usuario_id',
        'data_reserva',
        'prioridade',
    ];

    public function veiculo()
    {
        return $this->belongsTo(Veiculos::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
