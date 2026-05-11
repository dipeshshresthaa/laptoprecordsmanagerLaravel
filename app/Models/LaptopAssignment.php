<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaptopAssignment extends Model
{
    protected $guarded = [];
    protected $casts = [
        'assigned_date' => 'date',
        'returned_date' => 'date',
    ];

    public function laptop(): BelongsTo { return $this->belongsTo(Laptop::class); }
    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }
    public function assignedBy(): BelongsTo { return $this->belongsTo(User::class, 'assigned_by_id'); }
    public function returnedBy(): BelongsTo { return $this->belongsTo(User::class, 'returned_by_id'); }
}