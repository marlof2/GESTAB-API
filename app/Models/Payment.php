<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'establishment_id',
        'user_id',
        'payment_id',
        'preference_id',
        'plan_id',
        'amount',
        'status',
        'payment_method',
        'metadata',
        'external_reference',
        'subscription_start',
        'subscription_end',
    ];

    protected $casts = [
        'subscription_start' => 'datetime',
        'subscription_end' => 'datetime',
    ];

    public function isExpired(): bool
    {
        return $this->subscription_end && now()->isAfter($this->subscription_end);
    }

    public function isActive(): bool
    {
        return $this->status === 'approved'
            && $this->subscription_end
            && now()->isBefore($this->subscription_end);
    }
}


