<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class RegisterController extends Controller
{
    public function index(Request $request)
    {
        try {
            $valid = $request->validate([
                "name" => "required|max:255",
                "email" => "required|email:dns|unique:users,email",
                "password" => "required|min:8"
            ]);

            $valid["password"] = Hash::make($request->password);
            $Data = User::create($valid);
            return Mudah::Respond($Data);
        } catch (\Exception $ex) {
            return Mudah::Error($ex);
        }
    }


    public function Login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email:dns'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $user["token"] = $user->createToken($user->email)->plainTextToken;
                return Mudah::Respond($user);
            }

            return Mudah::ErrorRespond("Login gagal pastikan username dan password benar");
        } catch (\Exception $ex) {
            return Mudah::Error($ex);
        }
    }

    public function GetUser(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email:dns'
            ]);

            $user = User::where('email', $request->email);
            $token =  $user->currentAccessToken()->token;
            dd($token);
            if (PersonalAccessToken::findToken($token->plainTextToken)) {
                return Mudah::Respond([
                    'user' => $user,
                    'token' => $token->plainTextToken
                ]);
            }

            return Mudah::ErrorRespond("Token Not valid");
        } catch (\Exception $ex) {
            return Mudah::Error($ex);
        }
    }
}
