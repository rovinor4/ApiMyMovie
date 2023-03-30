<?php

namespace App\Http\Controllers;

use App\Models\KategoriFilm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilmKategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = KategoriFilm::all();
        return Mudah::Respond($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $valid = $request->validate([
                "name" => "required|max:255",
                "image" => "required|file|image",
                "deskripsi" => "required",
            ]);

            if ($request->file("image")) {
                $valid["image"] = $request->file('image')->store("public");
            }
            $data = KategoriFilm::create($valid);
            return Mudah::Respond($data);
        } catch (\Exception $ex) {
            return Mudah::Error($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(KategoriFilm $KategoriFilm)
    {
        $KategoriFilm["film"] = $KategoriFilm->Film()->get();
        return Mudah::Respond($KategoriFilm);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriFilm $KategoriFilm)
    {
        try {
            $valid = $request->validate([
                "name" => "required|max:255",
                "deskripsi" => "required",
            ]);


            if ($request->file("image")) {
                if (!empty($KategoriFilm->image)) {
                    Storage::delete($KategoriFilm->image);
                }

                $valid["image"] = $request->file('image')->store("public");
            }

            $KategoriFilm->update($valid);
            return Mudah::Respond($KategoriFilm);
        } catch (\Exception $ex) {
            return Mudah::Error($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriFilm $KategoriFilm)
    {
        try {
            $KategoriFilm->delete();
            return Mudah::Respond($KategoriFilm);
        } catch (\Exception $ex) {
            return Mudah::Error($ex);
        }
    }
}
