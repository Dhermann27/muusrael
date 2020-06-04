<?php

namespace App\Http;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function rooms()
    {
        return $this->hasMany('App\Http\Room');
    }
    public function getImageArrayAttribute()
    {
        return explode(';', $this->image);
    }
}
