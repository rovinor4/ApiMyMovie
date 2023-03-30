<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Film;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\FilmCommentar;
use Illuminate\Support\Facades\Storage;

class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = null;
        if ($request->search) {
            $search = $request->search;
            $data = Film::where("name", "like", "%$search%")->orWhere("deskripsi", "like", "%$search%")->get();
        } else {
            $data = Film::get();
        }

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
                "deskripsi" => "required",
                "image" => "required|file",
                "video" =>  "required|file",
                "price" => "required|integer",
                "kategori_film_id" => "exists:kategori_films,id",
            ]);

            if ($request->file("image")) {
                $valid["image"] = $request->file('image')->store("public");
            }

            if ($request->file("video")) {
                $valid["video"] = $request->file('video')->store("public");
            }


            $Data = Film::create($valid);

            return Mudah::Respond($Data);
        } catch (\Exception $ex) {
            return Mudah::Error($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Film $film, Request $request)
    {
        try {
            if ($request->user) {
                $Find = User::findOrFail($request->user);

                $film["user"] = $Find;
                $bill = Bill::where("user_id", $Find->id)->where("film_id", $film->id);
                if ($bill->count() > 0) {
                    $film["bill"] = $bill->get();
                }
            }
            $film["comments"] = $film->comments()->get();
            $film["jumlah_rating"] = $film["comments"]->sum("rating");
            if ($film["jumlah_rating"] > 0) {
                $film["rating_utama"] = round($film["jumlah_rating"] / $film["comments"]->count(), 2);
            } else {
                $film["rating_utama"] = 0;
            }
            $film["jumlah_ulasan"] = $film->comments()->count();
            $film["kategori"] = $film->kategori;
            return Mudah::Respond($film);
        } catch (\Exception $ex) {
            return Mudah::Error($ex);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Film $film)
    {
        try {
            $valid = $request->validate([
                "name" => "required|max:255",
                "deskripsi" => "required",
                "price" => "required|integer",
                "kategori_film_id" => "exists:kategori_films,id",
            ]);

            if ($request->file("image")) {
                if (!empty($film->image)) {
                    Storage::delete($film->image);
                }

                $valid["image"] = $request->file('image')->store("public");
            }

            if ($request->file("video")) {
                if (!empty($film->video)) {
                    Storage::delete($film->video);
                }

                $valid["video"] = $request->file('video')->store("public");
            }

            $Data = $film->update($valid);
            return Mudah::Respond($film);
        } catch (\Exception $ex) {
            return Mudah::Error($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Film $film)
    {
        try {
            $film->delete();
            return Mudah::Respond($film);
        } catch (\Exception $ex) {
            return Mudah::Error($ex);
        }
    }
}
