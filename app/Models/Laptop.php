<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Laptop extends Model
{
    protected $guarded = [];
    protected $casts = [
        'purchase_date' => 'date',
        'disposal_date' => 'date',
    ];

    // Read-only business logic check
    public function getIsDisposedAttribute(): bool
    {
        return $this->status === 'Disposed';
    }

    // Helper to get the public URL of the photo
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->laptop_photo_path ? Storage::url($this->laptop_photo_path) : null;
    }

    // Lookup Relationships
    public function brand(): BelongsTo { return $this->belongsTo(SystemLookup::class, 'brand_id'); }
    public function model(): BelongsTo { return $this->belongsTo(SystemLookup::class, 'model_id'); }
    public function processor(): BelongsTo { return $this->belongsTo(SystemLookup::class, 'processor_id'); }
    public function ramSize(): BelongsTo { return $this->belongsTo(SystemLookup::class, 'ram_size_id'); }
    public function storageSize(): BelongsTo { return $this->belongsTo(SystemLookup::class, 'storage_size_id'); }
    public function screenSize(): BelongsTo { return $this->belongsTo(SystemLookup::class, 'screen_size_id'); }

    // Audit Relationships
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by_id'); }
    public function modifier(): BelongsTo { return $this->belongsTo(User::class, 'modified_by_id'); }
}