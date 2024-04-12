<?php

namespace App\Models;

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
    protected $fillable = ['name', 'email', 'phone', 'mobilePhone', 'cpfCnpj', 'postalCode', 'address', 'addressNumber', 'complement', 'province', 'externalReference', 'notificationDisabled', 'observations', 'updated_at', 'created_at'];


}
