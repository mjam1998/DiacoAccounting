<?php

namespace App\Http\Controllers;


use App\Models\Bank_account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index()
    {
        /*User::query()->create([
           'name'=>'جواد اسدی',
            'username'=>'mjam1998',
            'password'=>Hash::make('javad1377'),
        ]);*/

        return view('login');
    }

    public function login()
    {
        return view('login');
}

    public function loginPost(Request $request)
    {
        $user=User::query()->where('username',$request['username'])->first();
        if($user==null){
            return back()->with('loginMessage','اطلاعات نادرست لطفا دوباره تلاش کنید.');
        }
        if(Hash::check($request->password,$user->password)){
            Auth::login($user);
            return redirect()->route('AdminHome');
        }
        return back()->with('loginMessage',' اطلاعات نادرست لطفا دوباره تلاش کنید.');
    }
}
