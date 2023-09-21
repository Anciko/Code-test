<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Responder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return Responder::fail('Validation error!', $validator->errors()->all(), 422);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('Movie')->accessToken;

            return Responder::success(['token' => $token]);
        } else {
            return Responder::fail("Authentication Fail!", 401);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();

        return Responder::success('You have been successfully logged out!');
    
    }
}
