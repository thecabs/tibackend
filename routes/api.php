<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnnonceControlleur;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\admin\UserControlleur;
use App\Http\Controllers\SignalementController;

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

Route::post('/send-otp', [AuthController::class, 'sendOtpEmail']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/login', [AuthController::class, 'login']);

//Liste des Users
Route::get('/users',[UserControlleur::class,'index']);
//Creer directement le user
Route::post('/createUser', [UserControlleur::class,'store']);


//Devenir chauffeur
Route::post('/becomeDriver/{id}', [AuthController::class, 'becomeDriver']);

//Create une annonce
Route::post('/annonce',[AnnonceControlleur::class,'store']);
//Afficher les annonces
Route::get('/annonce',[AnnonceControlleur::class,'index']);
//Voir une annonce
Route::get('/annonce/{id}',[AnnonceControlleur::class,'show']);
//Mettre  jour une annonces
Route::put('/annonce/{id}',[AnnonceControlleur::class,'update']);
//Supprimer une annonce
Route::delete('/annonce/{id}',[AnnonceControlleur::class,'destroy']);


//Faire une reservation
Route::post('/reservation',[ReservationController::class,'store']);
//Annuler une reservation
Route::delete('/reservation/{id}',[ReservationController::class,'destroy']);

//Annonces faites par un user
Route::get('/annonceByUser/{user}',[UserController::class,'annonces']);
//Reservations faite par un user
Route::get('/reservationByUser/{user}',[UserController::class,'reservations']);
//Notifications d'un user
Route::get('/notificationByUser/{user}',[UserController::class,'notifications']);
//Signalement recu par un user
Route::get('/signalementsRecus/{user}',[UserController::class,'signalementsRecus']);
//Signalement envoye
Route::get('/signalementsEnvoyes/{user}',[UserController::class,'signalementsEnvoyes']);

//Creer un signalement
Route::post('signalement',[SignalementController::class,'store']);
