<?php

namespace App\Http;

use Illuminate\Database\Eloquent\Model;

class CamperStaff extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'camper__staff';

    public function camper()
    {
        return $this->hasOne('App\Http\Camper');
    }

    public function staffposition()
    {
        return $this->hasOne('App\Http\Staffposition');
    }
}
