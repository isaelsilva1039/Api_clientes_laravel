<?php

namespace App\Models;

use App\Models\CadastroMembros\Anexo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profissional extends Model
{
    use HasFactory;

    protected $table = 'profissionais'; // Especificando o nome correto da tabela


           /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'nome', 'email', 'cpf', 'data_nascimento', 'especialidade', 'avatar' , 'fk_anexo', 'updated_at', 'created_at'
    ];


    public function anexo()
    {
        return $this->belongsTo(Anexo::class, 'fk_anexo');
    }

}
