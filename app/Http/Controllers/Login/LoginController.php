<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    


    public function login(Request $request)
    {
        $credemciais = $request->only('cpf', 'password');
        
        if(!auth()->attempt($credemciais)) 
            abort(401, 'Invalido credenciais');
        
        
        $token = $request->user()->createToken("auth_token");

        return Response ([
            'result' => [
                'token' => $token->plainTextToken
            ]
        ]);
    }
}
