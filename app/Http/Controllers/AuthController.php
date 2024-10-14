<?php

namespace App\Http\Controllers;


use App\Models\Otp;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function generateOTP()
    {
        return rand(100000, 999999);  // Génère un OTP à 6 chiffres
    }


    public function sendOtpEmail(Request $request)
    {
        $request->validate(['email'=>'required|email|unique:users,email',
                                    'tel'=>'string|required|unique:users,tel',
                                    'name'=>'required|string']);

        $otp = $this->generateOTP();
        $email = $request->email;

        // Envoie l'e-mail avec l'OTP
        //Mail::to($email)->send(new SendOtpMail($otp));



        // Sauvegarde l'OTP dans la base de données
        Otp::create([
            'email' => $email,
            'tel'=>$request->tel,
            'name'=> $request->name,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(10) // Expire dans 10 minutes
        ]);

        return response()->json(['message' => 'OTP envoyé avec succès']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['email'=> 'email|required',"otp"=>"required|numeric"]);
        $email = $request->email;
        $otp = $request->otp;

        // Cherche l'OTP dans la base de données
        $otpRecord = Otp::where('email', $email)->where('otp', $otp)->first();

        if (!$otpRecord) {
            return response()->json(['message' => 'OTP invalide'], 400);
        }

        // Vérifie si l'OTP a expiré
        if (Carbon::now()->greaterThan($otpRecord->expires_at)) {
            $otpRecord->delete();
            return response()->json(['message' => 'OTP expiré'], 400);
        }

        // Si l'OTP est correct et valide
        User::create([
            'email'=> $otpRecord->email,
            'name'=> $otpRecord->name,
            'tel'=> $otpRecord->tel,
            'password'=> Hash::make(config('app.defaultPassword'))
        ]);
        $otpRecord->delete();
        return response()->json(['message' => 'Authentification réussie']);
    }

    public function login(Request $request){
        $request->validate(['email'=> 'email|nullable',
                                    'tel'=> 'string|nullable',
                                    'password'=>'string|nullable']);

        $user=User::where('email', $request->email)
                    ->orWhere('tel',$request->tel)->first();

        if($request->password){
            $credentials = ["email"=>$user->email,"password"=>$request->password];
        }else{
            $credentials = ["email"=>$user->email,"password"=>config('app.defaultPassword')];
        }

        if (Auth::attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('AuthToken')->plainTextToken;

            return response()->json(['user' => $user, 'token' => $token], 200);
        } else {
            return response()->json(['message' => 'Not found'], 401);
        }
    }

    public function becomeDriver(Request $request, string $id){


        $user=User::findOrFail($id);

        $request->validate(["permis"=>"required|string",
                                    "fullName"=> "required|string",
                                    "photo1"=>"required|mimes:jpeg,png,jpg,gif|max:4096",
                                    "photo2"=>"required|mimes:jpeg,png,jpg,gif|max:4096",
                                    "photo3"=>"required|mimes:jpeg,png,jpg,gif|max:4096"
                                  ]);

        $photoName = time() . '.' . $request->photo1->extension();
        $request->photo1->move(public_path('images'), $photoName);
        Photo::create(['user_id'=>$id,"path"=>'images/' . $photoName]);

        $photoName = time() . '.' . $request->photo2->extension();
        $request->photo2->move(public_path('images'), $photoName);
        Photo::create(['user_id'=>$id,"path"=>'images/' . $photoName]);

        $photoName = time() . '.' . $request->photo3->extension();
        $request->photo3->move(public_path('images'), $photoName);
        Photo::create(['user_id'=>$id,"path"=>'images/' . $photoName]);

        $user->update(["permis"=>$request->permis, "fullName"=>$request->fullName]);

        return response()->json(["message"=>"Informations enregistrees"],200);

    }

}
