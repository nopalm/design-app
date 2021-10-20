<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Laravel\Socialite\Facades\Socialite;

use App\Models\User;

use Auth;

class GoogleController extends Controller
{
    public function redirectToGoogle(){
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback(){
        try {
            $user = Socialite::driver('google')->stateless()->user();
            // dd($user->getId());
            $findUser = User::where('google_id',$user->getId())->first();
            if ($findUser) {
                Auth::login($findUser);
                return redirect('/home');
            }else{
                $newUser = User::create([
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'google_id' => $user->getId(),
                    'password' => bcrypt('12345678')
                ]);
                
                Auth::login($newUser);
                return redirect('/home');
            }
        } catch (Exception $e) {
           dd($e->getMessage());
        }
    }
}
