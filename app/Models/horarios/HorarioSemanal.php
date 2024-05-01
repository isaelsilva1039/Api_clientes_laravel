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
        'id',
        'user_id',
        'horarios',
    ];

    protected $casts = [
        'horarios' => 'array' // Isso garante que o Laravel trate o campo como JSON
    ];


    /**
     * Relacionamento com o model User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Outros métodos úteis para o model podem ser definidos aqui
}