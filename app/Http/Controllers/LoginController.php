<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->all();
        $valid = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required|string|min:8:max:100'
        ]);

        $user = User::where('email', $data['email'])->first();
        $passHash = bcrypt($data['password']);

        if (is_null($user)) {
            return response(null, 403);
        }

        if (Hash::check($user->api_token, $passHash)) {
            return response(null, 403);
        }

        if ($valid->fails()) {
            return response(['errors' => ['message' => $valid->errors()->first()]], 400);
        }

        return response(['token' => $user->api_token], 200);
    }
}

