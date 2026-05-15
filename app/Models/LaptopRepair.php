<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaptopRepair extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'laptop_id',
        'vendor_id',          // Changed from vendor_name
        'issue_description',
        'sent_date',          // Ensure this matches your DB column (sent_date vs send_date)
        'sent_by_id',
        'returned_date',
        'repair_cost',
        'repair_notes',
        'returned_by_id',
    ];

    protected $casts = [
        'sent_date' => 'date',
        'returned_date' => 'date',
    ];

    public function laptop(): BelongsTo
    {
        return $this->belongsTo(Laptop::class);
    }

    public function sentBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by_id');
    }

    public function returnedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'returned_by_id');
    }

    public function vendor()
    {
        return $this->belongsTo(SystemLookup::class, 'vendor_id');
    }
}
