<?php

namespace App\Models\Tipo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    use HasFactory;

       /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'tipo';
    protected $fillable = [
          'id',
          'tipo'
    ];

    public function membros()
    {
        return $this->hasMany(Membros::class, 'cargo');
    }
}
