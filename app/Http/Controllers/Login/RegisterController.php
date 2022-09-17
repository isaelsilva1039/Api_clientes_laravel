<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    
    public function register(Request $request, User $user)
    {

        $userData = $request->only('name', 'email', 'password');
        $userData['password'] = bcrypt($userData['password']);

        if(!$user = $user->create($userData))
            abort(500, 'Erro ao criar Usuario');


        return Response ([
            'data' => [
                'user' => $user
            ]
        ]);
    }
    
}
