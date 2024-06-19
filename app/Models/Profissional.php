<?php

namespace App\Models;

use App\Models\CadastroMembros\Anexo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profissional extends Model
{
    use HasFactory;
    use SoftDeletes; 

    protected $table = 'profissionais';


           /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'nome', 'email', 'cpf', 'data_nascimento', 'especialidade', 'avatar' , 'fk_anexo','user_id', 'updated_at','fk_especialidade' , 'link_sala', 'created_at, deleted_at'
    ];


    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function especialidade()
    {
        return $this->belongsTo(Especialidade::class, 'fk_especialidade');
    }

    public function anexo()
    {
        return $this->belongsTo(Anexo::class, 'fk_anexo');
    }

}
