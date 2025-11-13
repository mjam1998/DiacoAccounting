<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_type_id',
        'category_id',
        'bank_accounts_id',
        'buyPrice',
        'sellPrice',
        'isDebt',
        'description',
        'profit',
        'commission',
        'logistics',
        'tax',
        'created_at',
        'debt_id'
    ];
    protected  $casts = [
        'isDebt' => 'boolean'
    ];
    public function transaction_type(){
        return $this->belongsTo(Transaction_type::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function bank_account(){
        return $this->belongsTo(Bank_account::class);
    }
    public function debt(){
        return $this->belongsTo(Debt::class);
    }


}
