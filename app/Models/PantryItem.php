<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PantryItem extends Model
{
    /** @use HasFactory<\Database\Factories\PantryItemFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'category_id',
        'quantity',
        'unit',
        'purchase_date',
        'expiration_date',
        'location_id',
        'notes',
        'photo_path',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'expiration_date' => 'date',
            'quantity' => 'decimal:2',
        ];
    }

    /**
     * Get the user that owns the pantry item.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that the pantry item belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the location that the pantry item belongs to.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Check if the item is expired.
     */
    public function isExpired(): bool
    {
        return $this->expiration_date && $this->expiration_date->isPast();
    }

    /**
     * Check if the item is expiring soon (within 7 days).
     */
    public function isExpiringSoon(): bool
    {
        if (! $this->expiration_date) {
            return false;
        }

        return $this->expiration_date->isFuture() && $this->expiration_date->diffInDays(now()) <= 7;
    }

    /**
     * Get the expiration status for UI indicators.
     */
    public function getExpirationStatusAttribute(): string
    {
        if ($this->isExpired()) {
            return 'expired';
        }

        if ($this->isExpiringSoon()) {
            return 'expiring-soon';
        }

        return 'fresh';
    }
}
