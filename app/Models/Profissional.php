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
        'nome', 'email', 'cpf', 'data_nascimento', 'especialidade', 'avatar' , 'fk_anexo', 'updated_at', 'created_at, deleted_at'
    ];


    public function anexo()
    {
        return $this->belongsTo(Anexo::class, 'fk_anexo');
    }

}
