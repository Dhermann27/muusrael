<?php

namespace App\Http;

use Illuminate\Database\Eloquent\Model;

class ThisyearFamily extends Model
{
    protected $table = "thisyear_families";

    public function campers()
    {
        return $this->hasMany(ThisyearCamper::class, 'family_id', 'id');
    }

    public function charges()
    {
        return $this->hasMany(ThisyearCharge::class, 'family_id', 'id');
    }
}
