<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;

class RegisterController extends Controller
{
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'api_token' => Str::random(60),
        ]);
    }

    public function register(Request $request)
    {
        $body = $request->all();
        $valid = Validator::make($body, [
            'name' => 'required|string|min:3|max:15',
            'email' => 'required|email',
            'password' => 'required|string|min:8:max:100'
        ]);

        if ($valid->fails()) {
            return response(['errors' => ['message' => $valid->errors()->first()]], 400);
        }

        $this->create($body);

        return response(null, 200);
    }
}
