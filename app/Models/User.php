<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'qr_code_path',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function member()
    {
        return $this->hasOne(Member::class);
    }

    public function clockRecords()
    {
        return $this->hasMany(ClockRecord::class);
    }

    public function serviceRecordsAsCustomer()
    {
        return $this->hasMany(ServiceRecord::class, 'user_id');
    }

    public function serviceRecordsAsEmployee()
    {
        return $this->hasMany(ServiceRecord::class, 'employee_id');
    }

    public function benefits()
    {
        return $this->belongsToMany(Benefit::class)->withPivot('assigned_date', 'expiry_date', 'is_active')->withTimestamps();
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function isEmployee()
    {
        return $this->role === 'employee' || $this->role === 'manager';
    }

    public function isClockIn()
    {
        return $this->clockRecords()->whereNull('clock_out')->exists();
    }

    public function getLatestClockRecord()
    {
        return $this->clockRecords()->whereNull('clock_out')->latest()->first();
    }
}