<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laptop extends Model
{
    protected $guarded = [];

    protected $casts = [
        'purchase_date' => 'date',
        'disposal_date' => 'date',
    ];

    public function getIsDisposedAttribute(): bool
    {
        return $this->status === 'Disposed';
    }

    // NEW: Converts the raw binary database data into an image for the browser
    // NEW: Converts the raw binary database data into an image for the browser
    public function getPhotoDataUrlAttribute(): ?string
    {
        if ($this->laptop_photo) {
            // 1. PostgreSQL returns bytea columns as stream resources.
            // We must convert the resource stream back into a string.
            $photoData = is_resource($this->laptop_photo)
                ? stream_get_contents($this->laptop_photo)
                : $this->laptop_photo;

            // 2. Because the controller is now saving the file as a base64 string,
            // it is already safely encoded. We can just attach it directly to the Data URI!
            return 'data:image/jpeg;base64,'.$photoData;
        }

        return null;
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(SystemLookup::class, 'brand_id');
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(SystemLookup::class, 'model_id');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(SystemLookup::class, 'processor_id');
    }

    public function ramSize(): BelongsTo
    {
        return $this->belongsTo(SystemLookup::class, 'ram_size_id');
    }

    public function storageSize(): BelongsTo
    {
        return $this->belongsTo(SystemLookup::class, 'storage_size_id');
    }

    public function screenSize(): BelongsTo
    {
        return $this->belongsTo(SystemLookup::class, 'screen_size_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function modifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'modified_by_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(LaptopAssignment::class);
    }

    // Helper to get the currently active assignment
    public function currentAssignment()
    {
        return $this->hasOne(LaptopAssignment::class)->whereNull('returned_date')->latestOfMany();
    }

    public function repairs(): HasMany
    {
        return $this->hasMany(LaptopRepair::class);
    }

    public function upgrades(): HasMany
    {
        return $this->hasMany(LaptopUpgrade::class);
    }
}
