<?php

namespace App\Http;

use Illuminate\Database\Eloquent\Model;

class Pronoun extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function camper()
    {
        return $this->belongsTo('App\Http\Camper');
    }

}
