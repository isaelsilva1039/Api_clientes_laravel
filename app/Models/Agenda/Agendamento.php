<?php

namespace App\Models\Agenda;

use App\Models\Cliente;
use App\Models\Consultas\Consulta;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agendamento extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array
     */
    protected $fillable = [
        'medico_id',
        'cliente_id',
        'start_time',
        'end_time',
        'status'
    ];

    /**
     * Retorna o usuário que é o médico deste agendamento.
     */
    public function medico()
    {
        return $this->belongsTo(User::class, 'medico_id');
    }

    
    /**
     * Retorna o usuário que é o cliente deste agendamento.
     */
    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    public function consultas()
    {
        return $this->hasMany(Consulta::class);
    }
}


?>