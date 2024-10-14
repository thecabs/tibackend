<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Notification;
use App\Models\Reservation;
use App\Models\Signalement;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

   public function annonces(Request $request, User $user){
        $annonces = Annonce::latest()->whereBelongsTo($user)->paginate(15);
        return response($annonces->load('reservations'), 200);
   }

   public function reservations(Request $request, User $user){
    $reservations = Reservation::latest()->whereBelongsTo($user)->paginate(15);
    return response($reservations->load('annonce'),200);
   }

   public function notifications(Request $request, User $user){
    $notifications = Notification::latest()->whereBelongsTo($user,'notifie')->paginate(15);
    return response($notifications,200);
   }

   public function signalementsRecus(Request $request, User $user){
    $signalementRecus = Signalement::latest()->whereBelongsTo($user,'signalee')->paginate(15);
    return response($signalementRecus,200);
   }

   public function signalementsEnvoyes(Request $request, User $user){
    $signalementsEnvoyes = Signalement::latest()->whereBelongsTo($user,'signaleur')->paginate(15);
    return response( $signalementsEnvoyes,200);
   }


}
