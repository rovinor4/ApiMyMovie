<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\AssignOp\Mul;
use Illuminate\Support\Facades\Storage;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Bill::with(["users", "films"])->get();
        if ($request->user) {
            $data = Bill::where("user_id", $request->user)->with(["users", "films"])->get();
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
                "user_id" => "required|exists:users,id",
                "film_id" => "required|exists:films,id",
                "status" => "required",
            ]);

            if ($request->status === "false") {
                $valid["status"] = 0;
            } else {
                $valid["status"] = 1;
            }

            if ($request->file("image")) {
                $valid["image"] = $request->file('image')->store("public");
            }

            $data = Bill::updateOrCreate(["user_id" => $request->user_id, "film_id" => $request->film_id], $valid);

            return Mudah::Respond($data);
        } catch (\Exception $ex) {
            return Mudah::Error($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Bill $Bill)
    {
        $data = $Bill->with(["users", "films"])->first();
        return Mudah::Respond($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bill $Bill)
    {
        try {
            $valid = $request->validate([
                "user_id" => "required|exists:users,id",
                "film_id" => "required|exists:films,id",
                "status" => "required",
            ]);

            if ($request->status === "false") {
                $valid["status"] = 0;
            } else {
                $valid["status"] = 1;
            }

            if ($request->file("image")) {
                if (!empty($Bill->image)) {
                    Storage::delete($Bill->image);
                }
                $valid["image"] = $request->file('image')->store("public");
            }


            $Data = $Bill->update($valid);
            return Mudah::Respond($Bill);
        } catch (\Exception $ex) {
            return Mudah::Error($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bill $Bill)
    {
        try {
            $Bill->delete();
            return Mudah::Respond($Bill);
        } catch (\Exception $ex) {
            return Mudah::Error($ex);
        }
    }


    public function user()
    {
        $data = User::get();
        return Mudah::Respond($data);
    }
}
