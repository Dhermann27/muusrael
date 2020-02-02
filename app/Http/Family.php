<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'families';

    public function province()
    {
        return $this->hasOne('App\Province', 'id', 'province_id');
    }

    public function campers()
    {
        return $this->hasMany('App\Camper');
    }
}
