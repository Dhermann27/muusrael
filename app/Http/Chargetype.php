<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chargetype extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function charge()
    {
        return $this->belongsTo('App\Charge');
    }

    public function byyearcharges() {
        return $this->hasMany('App\ByyearCharge');
    }

    public function thisyearcharges() {
        return $this->hasMany('App\ThisyearCharge');
    }

}
