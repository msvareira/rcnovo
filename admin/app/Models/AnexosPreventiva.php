<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Preventivas;

class AnexosPreventiva extends Model
{
    use HasFactory;

    protected $table = 'anexos_preventiva';

    protected $fillable = [
        'preventiva_id',
        'file_path',
    ];

    public function preventiva()
    {
        return $this->belongsTo(Preventivas::class);
    }
}
