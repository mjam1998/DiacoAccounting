<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction_type extends Model
{
    protected  $fillable = [
        'name',
    ];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
