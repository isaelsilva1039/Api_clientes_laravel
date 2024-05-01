<?php

namespace App\Models\horarios;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class HorarioSemanal extends Model
{
    use HasFactory;


    protected $table = 'horarios_semanais';

    protected $fillable = [
        'usuario_id',
        'dia_da_semana',
        'hora_inicio',
        'hora_fim'
    ];

    /**
     * Relacionamento com o model User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Outros métodos úteis para o model podem ser definidos aqui
}