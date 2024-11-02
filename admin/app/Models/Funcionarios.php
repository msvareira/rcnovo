<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcionarios extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nome',
        'rg',        
        'cpf',
        'carta_motorista',
        'carga_horaria_dia',
        'valor_hora_extra',
        'salario',
        'digital',
        'cod_cracha',
    ];

    public function user()
    {
        return $this->hasOne(User::class,'funcionario_id');
    }


}
