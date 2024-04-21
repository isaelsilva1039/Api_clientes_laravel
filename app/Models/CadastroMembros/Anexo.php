<?php

namespace App\Models\CadastroMembros;

use App\Models\Igreja\Igreja;
use App\Models\Tipo\Tipo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anexo extends Model
{
    use HasFactory;

       /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'path',
        'url'
    ];

    public $timestamps = false;



    // public function membro()
    // {
    //     return $this->belongsTo(Membro::class, 'id');
    // }
}
