<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class bankCheck extends Model
{
    protected $fillable = [
      'bankAccount_id',
      'check_amount',
      'check_date',
        'description'
    ];
    public function bankAccount(){
        return $this->belongsTo(Bank_account::class, 'bankAccount_id');
    }
}
