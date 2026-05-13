<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Employee extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    // REMOVED: protected $guarded = [];

    // ADDED: Strict mass assignment protection
    protected $fillable = [
        'id', 'emp_code', 'first_name', 'middle_name', 'last_name',
        'phone_number', 'address_state', 'address_district', 'address_municipality',
        'pan_number', 'role', 'designation', 'joining_date',
        'exit_date', 'exit_reason', 'articleship_completion_date',
        'bank_name', 'bank_branch', 'bank_account_number', 'cit_number',
        'is_active', 'principal_id', 'created_by_id', 'modified_by_id',
        'articleship_deed_path', 'completion_certificate_path',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'exit_date' => 'date',
        'articleship_completion_date' => 'date',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = strtoupper(substr(Str::uuid()->toString(), 0, 8));
            }
        });
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => implode(' ', array_filter([
                $this->first_name,
                $this->middle_name,
                $this->last_name,
            ], fn ($value) => ! empty(trim($value ?? ''))))
        );
    }

    protected function roleDisplay(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->role) {
                'ArticleTrainee' => 'Article trainee',
                'Partner' => 'Partner',
                'Other' => 'Other',
                default => $this->role,
            }
        );
    }

    protected function roleBadgeClasses(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->role) {
                'ArticleTrainee' => 'bg-blue-100 text-blue-800 border-blue-200',
                'Partner' => 'bg-purple-100 text-purple-800 border-purple-200',
                default => 'bg-slate-100 text-slate-800 border-slate-200',
            }
        );
    }

    public function principal(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'principal_id');
    }

    public function trainees(): HasMany
    {
        return $this->hasMany(Employee::class, 'principal_id');
    }

    public function userAccount()
    {
        return $this->hasOne(User::class, 'employee_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function modifier()
    {
        return $this->belongsTo(User::class, 'modified_by_id');
    }
}
