<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SystemLookup extends Model
{
    protected $guarded = []; // Allows mass assignment

    // Get the parent lookup (e.g., getting the Brand for a Model)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(SystemLookup::class, 'parent_id');
    }

    // Get the child lookups (e.g., getting all Models for a Brand)
    public function children(): HasMany
    {
        return $this->hasMany(SystemLookup::class, 'parent_id');
    }
}