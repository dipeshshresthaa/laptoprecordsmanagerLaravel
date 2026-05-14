<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaptopPhoto extends Model
{
    protected $guarded = [];

    public function laptop()
    {
        return $this->belongsTo(Laptop::class);
    }
}
