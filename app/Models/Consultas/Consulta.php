<?php

namespace App\Models\Consultas;

use App\Models\Agenda\Agendamento;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'quantidade_consultas',
        'inicio_data',
        'fim_data',
        'quantidade_realizada',
    ];


    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    

    public function agendamento()
    {
        return $this->belongsTo(Agendamento::class);
    }
}
