<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Licence extends Model
{
    protected $fillable = [
        'name',
        'vendor_name',
        'period_start',
        'period_end',
        'licence_type',
        'reminder_days',
        'description',
    ];

    protected $casts = [
        'reminder_days' => 'array',
    ];

    public function logs()
    {
        return $this->hasMany(LicenceLog::class)->orderBy('created_at', 'desc');
    }
}
