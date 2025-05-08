<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'membership_requested',
        'is_approved',
        'membership_start_date',
        'membership_end_date',
        'membership_number',
        'notes',
    ];

    protected $casts = [
        'membership_requested' => 'boolean',
        'is_approved' => 'boolean',
        'membership_start_date' => 'date',
        'membership_end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isActive()
    {
        if (!$this->is_approved) {
            return false;
        }

        if ($this->membership_end_date && $this->membership_end_date < now()) {
            return false;
        }

        return true;
    }
}