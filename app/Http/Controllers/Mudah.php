<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Mudah extends Controller
{
    public static $Data = [
        0 => "Menunggu Pembayaran",
        1 => "Konfirmasi Pembayaran",
        2 => "Lunas",
        3 => "Dibatalkan",
        4 => "Dibatalkan Dan Uang Di Kembalikan",
    ];

    public static function Error(\Exception $ex)
    {
        $error = [
            "status" => false,
            "message" => $ex->getMessage()
        ];
        return response()->json($error, 500);
    }

    public static function ErrorRespond($message)
    {
        $error = [
            "status" => false,
            "message" => $message
        ];
        return response()->json($error, 500);
    }

    public static function Respond($data)
    {
        $json = [
            "status" => true,
            "data" => $data
        ];
        return response()->json($json, 200);
    }

    public static function createToken($end = false)
    {
        $now = Carbon::now();
        $uuid = Str::uuid();
        $gabung = "$now&$uuid";
        if ($end === true) {
            $gabung = Hash::make($gabung);
        }
        return $gabung;
    }



}
