<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable=[
        'name',
        'transaction_type_id',
        'commission',
        'tax',
        'logistics',

    ];
    public function transaction_type(){
        return $this->belongsTo(Transaction_type::class);
    }
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
}
