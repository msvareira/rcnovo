<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'razao',
        'fantasia',
        'cpf_cnpj',
        'rg_ie',
        'email',
        'cep',
        'estado',
        'cidade',
        'bairro',
        'rua',
        'numero',
        'complemento',
        'cod_ibge',
        'fone1',
        'fone2',
        'fone3',
        'website',
        'datacad',
        'ult_alteracao',
        'obs',
        'tipo_cadastro'
    ];

    
}
