<?php

namespace App\Models\CadastroMembros;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membro extends Model
{
    use HasFactory;

       /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome_membro',
        'email_dizimista',
        'cidade',
        'barrio',
        'endereco',
        'telefone',
        'batismo_agua',
        'data_nascimento',
        'cargo',
        'situacao',
        'fk_igreja',
        'data_batismo_espirito_santo',
        'sexo'
    ];
}
