<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank_account extends Model
{
    protected  $fillable = [
        'status',
        'name',
        'bank_name',
        'account_number',
        'account_card',
        'account_shaba',
        'wallet'
    ];
   public function transactions(){
       return $this->hasMany(Transaction::class);
   }

    public function bankCheks()
    {
        return $this->hasMany(bankCheck::class);
   }
}
