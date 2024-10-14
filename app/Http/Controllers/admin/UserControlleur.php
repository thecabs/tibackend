<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserControlleur extends Controller
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
        $users = User::latest()->paginate(15);
        return response()->json(["utilisateurs"=>$users],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        $user=$request->validate(['email'=>'required|email|unique:users,email',
                                    'tel'=>'string|required|unique:users,tel',
                                    'name'=>'required|string',
                                    "password"=>"string|required",
                                    "role"=>"required|integer"]);

        $user["password"] = Hash::make($request->password);

        User::create($user);
        return response()->json(["message"=> "Utilisateur  cree"],201);
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
        $user=User::findOrFail($id);

        $user->role=2;
        $user->save();
        return response()->json(["message"=> "Drivers valide"],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


}
