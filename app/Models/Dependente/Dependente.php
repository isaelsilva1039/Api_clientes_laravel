<?php

namespace App\Models\Dependente;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependente extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id', 'nome', 'email', 'cpf', 'data_de_nascimento', 'endereco', 'bairro',
        'cidade', 'estado', 'celular', 'numero'
    ];

    // Relação com Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}


?>