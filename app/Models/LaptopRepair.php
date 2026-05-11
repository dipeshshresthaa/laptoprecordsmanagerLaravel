<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaptopRepair extends Model
{
    protected $guarded = [];
    protected $casts = [
        'sent_date' => 'date',
        'returned_date' => 'date',
    ];

    public function laptop(): BelongsTo { return $this->belongsTo(Laptop::class); }
    public function sentBy(): BelongsTo { return $this->belongsTo(User::class, 'sent_by_id'); }
    public function returnedBy(): BelongsTo { return $this->belongsTo(User::class, 'returned_by_id'); }
}