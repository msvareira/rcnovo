<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicosOSAnexos extends Model
{
    use HasFactory;

    protected $table = 'servicos_os_anexos';

    protected $fillable = [
        'servico_os_id',
        'arquivo',
        'descricao'
    ];

    public function servicoOS()
    {
        return $this->belongsTo(ServicosOS::class, 'servico_os_id');
    }


}
