<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Clientes;

class Contatos extends Model
{
    protected $table = 'contatos';

    protected $fillable = [
        'nome',
        'fone1',
        'fone2',
        'email',
        'cliente_id',
    ];

    public function cliente()
    {
        return $this->belongsTo(Clientes::class);
    }
    

}
