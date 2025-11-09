<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PantryLog extends Model
{
    protected $fillable = [
        'pantry_item_id',
        'user_id',
        'action',
        'quantity',
        'notes',
    ];

    public function pantryItem(): BelongsTo
    {
        return $this->belongsTo(PantryItem::class)->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
