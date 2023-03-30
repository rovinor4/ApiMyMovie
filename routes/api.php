<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\FilmCommentarController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\FilmKategoriController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get("token", [UserController::class, "GetToken"]);

// Route::resource('user', UserController::class)->except(["index", "update", "destroy"]);

Route::post('login', [RegisterController::class, "Login"]);
Route::post('register', [RegisterController::class, "index"]);
Route::post('user-get', [RegisterController::class, "GetUser"]);

Route::resource('film', FilmController::class);
Route::resource('commentar', FilmCommentarController::class);
Route::resource('kategori', FilmKategoriController::class)->except(["show", "update", "destroy"]);
Route::get('kategori/{KategoriFilm}', [FilmKategoriController::class, "show"]);
Route::post('kategori/{KategoriFilm}', [FilmKategoriController::class, "update"]);
Route::delete('kategori/{KategoriFilm}', [FilmKategoriController::class, "destroy"]);
Route::resource('bill', BillController::class);
Route::post('bill-user', [BillController::class, "user"]);
Route::get("get", function () {
    dd(Route::getRoutes());
});
