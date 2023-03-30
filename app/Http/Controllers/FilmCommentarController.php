<?php

namespace App\Http\Controllers;

use App\Models\FilmCommentar;
use Illuminate\Http\Request;

class FilmCommentarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $request->validate([
                "film_id" => "required|exists:films,id",
                "user_id" => "required|exists:users,id"
            ]);

            $commentar = FilmCommentar::where("film_id", $request->film_id)->where("user_id",$request->user_id);
            if (!$commentar->count()) {
                return Mudah::Respond(null);
            } else {
                return Mudah::Respond($commentar->get());
            }
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
                "user_id" => "required|exists:users,id",
                "film_id" => "required|exists:films,id",
                "rating" => "required|max:5|min:1|integer",
                "commentar" => "required",
            ]);


            $Data = FilmCommentar::create($valid);

            return Mudah::Respond($Data);
        } catch (\Exception $ex) {
            return Mudah::Error($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FilmCommentar $filmCommentar)
    {
        try {
            return Mudah::Respond($filmCommentar->get());
        } catch (\Exception $ex) {
            return Mudah::Error($ex);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FilmCommentar $commentar)
    {
        try {
            $valid = $request->validate([
                "rating" => "required|max:5|min:1|integer",
                "commentar" => "required",
            ]);


            $commentar->update($valid);
            return Mudah::Respond($commentar);
        } catch (\Exception $ex) {
            return Mudah::Error($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FilmCommentar $filmCommentar)
    {
        try {
            $filmCommentar->delete();
            return Mudah::Respond($filmCommentar);
        } catch (\Exception $ex) {
            return Mudah::Error($ex);
        }
    }
}
