<?php

namespace App\Http\Controllers;


use App\Models\Bank_account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function adminHome()
    {
        return view('adminPanel.user.adminHome');
    }
    public function adminList(){
        $users=User:: query()->withTrashed()->get();
        return view('adminPanel.user.adminList',compact('users'));
    }
    public function adminAdd(){
        return view('adminPanel.user.adminAdd');
    }
    public  function  store(Request $request)
    {
        $data=$request->all();
        $data['password']=Hash::make($data['password']);
        User::query()->create($data);
        return redirect(route('AdminList'));

    }

    public function editAdmin($id)
    {
        $user=User::query()->find($id);
        return view('adminPanel.user.adminEdit',compact('user'));
    }

    public function edit(request $request)
    {
        $data=$request->all();
        $data['password']=Hash::make($data['password']);
        $user=User::query()->find($data['id']);
        $user->update($data);
        return redirect(route('AdminList'));
    }

    public function delete($id)
    {
        $user=User::query()->find($id);
        $user->delete();
        return redirect(route('AdminList'));
    }
    public function restore($id)
    {
        $user = User::withTrashed()->find($id);
        $user->restore();
        return redirect(route('AdminList'));
    }

    public function logOut(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function bankAccountPrimary()
    {
        $bank=Bank_account::query()->where('status',1)->first();

        return view('adminPanel.bankAccount.primaryAccount',compact('bank'));
    }

    public function editPrimary( Request $request)
    {
        $bank=Bank_account::query()->where('status',1)->first();
        $bank->update([
            'name'=>$request['name'],
            'bank_name'=>$request['bank_name'],
            'account_number'=>$request['account_number'],
            'account_card'=>$request['account_card'],
            'account_shaba'=>$request['account_shaba'],
        ]);
        return redirect(route('bankAccount.primary'))->with('editAccountPrimary','اطلاعات با موفقیت بروز شد.');
    }

    public function bankAccountList()
    {
        $banks=Bank_account::query()->where('status',2)->get();
        return view('adminPanel.bankAccount.accountsList',compact('banks'));
    }

    public function addBankAccount(Request $request)
    {
        Bank_account::query()->create([
            'name'=>$request['name'],
            'bank_name'=>$request['bank_name'],
            'account_number'=>$request['account_number'],
            'account_card'=>$request['account_card'],
            'account_shaba'=>$request['account_shaba'],
            'status'=>2
        ]);
        return redirect(route('bankAccount.list'))->with('addAccount','اطلاعات با موفقیت افزوده شد.');
    }

}
