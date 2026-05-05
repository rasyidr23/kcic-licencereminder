<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenceLog extends Model
{
    protected $fillable = ['licence_id', 'vendor_name', 'period_start', 'period_end'];

    public function licence()
    {
        return $this->belongsTo(Licence::class);
    }
}
