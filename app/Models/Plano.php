<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome_plano', 'descricao', 'fidelidade', 'periodo_fidelidade', 'valor'
    ];

    public function especialidades()
    {
        return $this->hasMany(Especialidade::class);
    }
}
