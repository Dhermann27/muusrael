<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Yearattending extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'yearsattending';

    public function camper()
    {
        return $this->hasOne('App\Camper');
    }

    public function program()
    {
        return $this->hasOne('App\Program');
    }

    public function room()
    {
        return $this->hasOne('App\Room');
    }

    public function year()
    {
        return $this->hasOne('App\Year');
    }

    public function staffpositions()
    {
        return $this->belongsToMany('App\Staffposition')->using('App\YearattendingStaff');
    }

    public function volunteerpositions()
    {
        return $this->belongsToMany('App\Volunteerposition')->using('App\YearattendingVolunteer');
    }

    public function workshops()
    {
        return $this->belongsToMany('App\Workshop')->using('App\YearattendingWorkshop');
    }
}
