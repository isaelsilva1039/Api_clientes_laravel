<?php

namespace App\Models\horarios;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Mes extends Model
{
    use HasFactory;


    protected $table = 'Mes'; 

    protected $fillable = [
        'id',
        'user_id',
        'mes',
        'isActive',
        'value'
    ];


    /**
     * Relacionamento com o model User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}