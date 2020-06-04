<?php

namespace App\Http;

use Illuminate\Database\Eloquent\Model;

class YearattendingVolunteer extends Model
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
    protected $table = 'yearsattending__volunteer';

    public function yearattending()
    {
        return $this->hasOne('App\Http\Yearattending');
    }

    public function volunteerposition()
    {
        return $this->hasOne('App\Http\Volunteerposition');
    }
}
