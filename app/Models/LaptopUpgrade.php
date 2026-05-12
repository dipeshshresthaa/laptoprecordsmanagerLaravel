<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaptopUpgrade extends Model
{
    // This allows all fields to be mass-assigned
    protected $guarded = [];

    // Alternatively, you can list them specifically:
    /*
    protected $fillable = [
        'upgrade_type',
        'previous_spec',
        'new_spec',
        'upgrade_date',
        'cost',
        'notes',
        'performed_by_id'
    ];
    */
    
    // It's also good practice to cast the date
    protected $casts = [
        'upgrade_date' => 'date',
    ];
}