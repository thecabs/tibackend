<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Notification;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $reservation=$request->validate(["user_id"=>"required|numeric",
                                    "annonce_id"=>"required|numeric",
                                    "places"=>"required|numeric"]);

        $annonce=Annonce::findOrFail($reservation['annonce_id']);


        if (($annonce->places-$annonce->reservation)>=$reservation['places']){
            Reservation::create($reservation);
            $annonce->reservation+=$reservation['places'];
            $annonce->save();



            Notification::create(["user_id"=>$annonce->user_id,
                                                "message"=>"Vous avez une nouvelle reservation!"]);



            return response()->json(['message'=> 'Reservation reussie'],200);
        }else{
            return response()->json(['message'=> 'Une erreur est survenue'],400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reservation=Reservation::findOrFail($id);

        $annonce=$reservation->annonce;
        $annonce->reservation-=$reservation->places;
        $annonce->save();
        $reservation->etat=0;
        $reservation->save();

        Notification::create(["user_id"=>$reservation->user_id,
                                        "message"=>"Vous avez une nouvelle reservation!"]);

        return response()->json(['message'=> 'Reservation annulee'],200);
    }
}
