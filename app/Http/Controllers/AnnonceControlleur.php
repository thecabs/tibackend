<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnnonceControlleur extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if(  $request->user()->role == 1){
            $annonces = Annonce::latest()->paginate(15);
        }else {
            $annonces = Annonce::latest()->where('etat','!=',0)->paginate(15) ;
        }
        return response($annonces->load("reservations"),200) ;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $annonce=$request->validate([
            'user_id'=>'required|numeric',
            'depart'=>'required|string',
            'arrivee'=>'required|string',
            'date'=>'required|date|after:today',
            'places'=>'required|numeric',
            'commentaire'=>'nullable|string',
            'detailsVoiture'=>'nullable|string'
        ]);

        Annonce::create($annonce);
        return response()->json(["message"=>"annonce reussie"],201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $annonce=Annonce::find($id);
        return response()->json(["annonce"=>$annonce->load("reservations")],200) ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $annonce=Annonce::findOrFail($id);

        $update=$request->validate([
            'depart'=>'nullable|string',
            'arrivee'=>'nullable|string',
            'date'=>'nullable|date|after:today',
            'places'=>'nullable|numeric',
            'commentaire'=>'nullable|string',
            'detailsVoiture'=>'nullable|string'
        ]);

        if(isset($update['places'])){
            $places=$update['places'];
            unset($update['places']);
            if($places>=$annonce->reservation){
                $annonce->places=$places;
                $annonce->save();
            }
        }
        $annonce->update($update);

        return response()->json(['message'=> 'Mise a jour reussie'],200) ;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $annonce=Annonce::findOrFail($id);

        $annonce->etat=0;
        $annonce->save();
        return response()->json(['message'=> 'Suppression reussie'],200) ;
    }
}
