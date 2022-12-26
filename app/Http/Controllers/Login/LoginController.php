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
        $status_code = 500;

        $credemciais = $request->only('cpf', 'password');
        // Caso o usuario não exista
        if(!auth()->attempt($credemciais)) {
            return Response ([
                'result' => [
                    'status_code' => $status_code
                ]
            ]);
        }
        
        
        $token = $request->user()->createToken("auth_token");

  

        return Response ([
            'result' => [
                'token' => $token->plainTextToken,
                'status_code' => $status_code = 200
            ]
        ]);
    }
}
