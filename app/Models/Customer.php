<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'customer_id',
        'name',
        'email',
        'phone',
        'address',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    /**
     * Auto-generate customer_id saat membuat customer baru
     * Format: CUST-0001, CUST-0002, dst.
     */
    protected static function booted(): void
    {
        static::creating(function (Customer $customer) {
            if (empty($customer->customer_id)) {
                $last   = self::query()->latest('id')->first();
                $nextId = $last ? ($last->id + 1) : 1;
                $customer->customer_id = 'CUST-' . str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * @return HasMany<Subscription, $this>
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}