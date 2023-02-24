<?php

namespace App\Models\Igreja;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Igreja extends Model
{
    use HasFactory;

       /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'igrejas';
    protected $fillable = [
          'dirigente',
          'nome_igreja',
          'cidade',
          'barrio', 
          'endereco',
    ];

    public function membros()
    {
        return $this->hasMany(Membros::class, 'fk_igreja');
    }
}
