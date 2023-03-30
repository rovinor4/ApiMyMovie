<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Mudah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email:dns'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials)) {
                $user = auth()->user();
                return Mudah::Respond($user);
            }

            return Mudah::ErrorRespond("Login gagal pastikan username dan password benar");
        } catch (\Exception $ex) {
            return Mudah::Error($ex);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $valid = $request->validate([
                "name" => "required|max:255",
                "email" => "required|email:dns",
                "password" => "required|min:8"
            ]);

            $valid["password"] = Hash::make($request->password);
            $valid["token"] =  Mudah::createToken(true);
            $Data = User::create($valid);
            return Mudah::Respond($Data);
        } catch (\Exception $ex) {
            return Mudah::Error($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user, Request $request)
    {
        try {
            $request->validate([
                "token" => "required"
            ]);

            if ($user->token !== $request->token) {
                return Mudah::ErrorRespond("Token Tidak Sesuai");
            } else {
                return Mudah::Respond($user);
            }
        } catch (\Exception $ex) {
            return Mudah::Error($ex);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    public function GetToken(Request $request)
    {
        try {
            $token = $request->user()->createToken($request->token_name);
            return Mudah::Respond(["token" => $token->plainTextToken]);
        } catch (\Exception $ex) {
            return Mudah::Error($ex);
        }
    }
}
