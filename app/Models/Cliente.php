<?php

namespace App\Models;

use App\Models\Consultas\Consulta;
use App\Models\Dependente\Dependente;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'email',
    'phone',
    'mobilePhone', 'cpfCnpj',
    'postalCode',
    'address',
    'addressNumber',
    'complement',
    'province',
    'externalReference',
    'notificationDisabled',
    'observations',
    'id_cliente_assas',
    'date_of_birth',
    'plano',
    'total',
    'updated_at',
    'created_at'
  ];


  // Relação com Dependentes
  public function dependentes()
  {
    return $this->hasMany(Dependente::class);
  }

  // Definindo a relação com User
  public function user()
  {
    return $this->belongsTo('App\Models\User', 'user_id', 'id');
  }


  // Definição da relação com Consultas
  public function consultas()
  {
    return $this->hasMany(Consulta::class, 'user_id');
  }
}
