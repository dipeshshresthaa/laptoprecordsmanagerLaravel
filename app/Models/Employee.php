<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Employee extends Model
{
    // Configure the custom string primary key
    protected $keyType = 'string';
    public $incrementing = false;

    // Allow mass assignment for these fields
    protected $guarded = [];

    // Cast database dates to Carbon instances and boolean states
    protected $casts = [
        'joining_date' => 'date',
        'exit_date' => 'date',
        'articleship_completion_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Auto-generate the 8-char uppercase string ID when creating a new record
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = strtoupper(substr(Str::uuid()->toString(), 0, 8));
            }
        });
    }

    // Computed Property: FullName (Equivelant to [NotMapped] in C#)
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => collect([$this->first_name, $this->middle_name, $this->last_name])
                ->filter(fn ($name) => !empty(trim($name)))
                ->implode(' ')
        );
    }

    // Relationship: Principal (Self-referencing BelongsTo)
    public function principal(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'principal_id');
    }

    // Relationship: Trainees (Self-referencing HasMany)
    public function trainees(): HasMany
    {
        return $this->hasMany(Employee::class, 'principal_id');
    }

    // Relationship: User Account
    public function userAccount(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Example Relationship placeholder for LaptopAssignments
    // public function laptopAssignments(): HasMany
    // {
    //     return $this->hasMany(LaptopAssignment::class);
    // }
}