<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    protected $fillable = [
        'transaction_id',
        'debt1',
        'debt2',
        'debt3',
        'debt4',
        'debt1_time',
        'debt2_time',
        'debt3_time',
        'debt4_time',
        'debt1_isPaid',
        'debt2_isPaid',
        'debt3_isPaid',
        'debt4_isPaid',
    ];
    protected $casts = [
        'debt1_isPaid'=>'boolean',
        'debt2_isPaid'=>'boolean',
        'debt3_isPaid'=>'boolean',
        'debt4_isPaid'=>'boolean'
    ];
    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }
}
