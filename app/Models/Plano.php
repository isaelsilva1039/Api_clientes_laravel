<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome_plano', 'descricao', 'fidelidade', 'periodo_fidelidade', 'valor', 'especialidades'
    ];

    protected $casts = [
        'especialidades' => 'array', // Trata especialidades como array
    ];
    public function especialidadeRelacionada()
    {
        return $this->belongsToMany(Especialidade::class, 'especialidades', 'id', 'especialidades->specialty');
    }
}
