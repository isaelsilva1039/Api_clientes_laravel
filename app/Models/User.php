<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Agenda\Agendamento;
use App\Models\CadastroMembros\Anexo;
use App\Models\Consultas\Consulta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Cliente[] $clientes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Consultas\Consulta[] $consultas
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'tempo_consulta',
        'password',
        'tipo',
        'cpf',
        'fk_anexo'
    ];


    // Adiciona o campo avatar aos atributos acessíveis no JSON
    protected $appends = ['avatar'];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function anexo()
    {
        return $this->belongsTo(Anexo::class, 'fk_anexo');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Em App/Models/User.php
    public function agendamentosComoCliente()
    {
        return $this->hasMany(Agendamento::class, 'cliente_id');
    }


  // No modelo User
    public function agendamentosComoMedico()
    {
        return $this->hasMany(Agendamento::class, 'medico_id');
    }

       /**
     * Obtenha as consultas associadas ao usuário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany  
     */
    public function consultas():HasMany
    {
        return $this->hasMany('App\Models\Consultas\Consulta', 'user_id', 'id');
    }

     /**
     * Obtenha os clientes associados ao usuário.
     */
    
    public function clientes()
    {
        return $this->hasMany('App\Models\Cliente');
    }




     // Define o accessor para o avatar
     public function getAvatarAttribute()
     {
         return $this->fk_anexo ? route('profissional.avatar', ['id' => $this->fk_anexo]) : null;
     }

}
