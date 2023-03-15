<?php

namespace App\Models\CadastroMembros;

use App\Models\Igreja\Igreja;
use App\Models\Tipo\Tipo;
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
        'sexo',
        'fk_anexo'
    ];


    public function igreja()
    {
        return $this->belongsTo(Igreja::class, 'fk_igreja');
    }

    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'cargo');
    }

    public function anexo()
    {
        return $this->belongsTo(Anexo::class, 'fk_anexo');
    }

   /**
     * Get the member's name.
     *
     * @return string
     */
    public function getNomeMembroAttribute()
    {
        return $this->attributes['nome_membro'];
    }

    /**
     * Set the member's name.
     *
     * @param  string  $value
     * @return void
     */
    public function setNomeMembroAttribute($value)
    {
        $this->attributes['nome_membro'] = $value;
    }

}
