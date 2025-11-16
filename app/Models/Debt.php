<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    protected $fillable = [
        'category_id',
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
        'created_at'
    ];

    protected $casts = [
        'debt1_isPaid' => 'boolean',
        'debt2_isPaid' => 'boolean',
        'debt3_isPaid' => 'boolean',
        'debt4_isPaid' => 'boolean',
        'debt1_time' => 'date',
        'debt2_time' => 'date',
        'debt3_time' => 'date',
        'debt4_time' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getUnpaidAmountAttribute(): int
    {
        $total = 0;
        for ($i = 1; $i <= 4; $i++) {
            $amountField = "debt{$i}";
            $paidField = "debt{$i}_isPaid";

            if ($this->$amountField !== null && !$this->$paidField) {
                $total += $this->$amountField;
            }
        }
        return $total;
    }
}
