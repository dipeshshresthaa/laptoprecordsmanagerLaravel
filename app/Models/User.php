<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

// MUST extend Authenticatable, not Model
class User extends Authenticatable 
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    // Tell Laravel what column holds the password, even though we check it manually, 
    // it helps internal session logic.
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid()->toString();
            }
        });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}