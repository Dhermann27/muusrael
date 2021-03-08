<?php

namespace App\Http;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Workshop extends Model
{
    public function getDisplayDaysAttribute()
    {
        $days = "";
        if ($this->m == '1') $days .= 'M';
        if ($this->t == '1') $days .= 'Tu';
        if ($this->w == '1') $days .= 'W';
        if ($this->th == '1') $days .= 'Th';
        if ($this->f == '1') $days .= 'F';
        if ($this->s == '1') $days .= 'Sa';
        return $days;
    }

    public function getBitDaysAttribute()
    {
        return $this->m . $this->t . $this->w . $this->th . $this->f . $this->s;
    }

    public function choices()
    {
        return $this->hasMany(YearattendingWorkshop::class, 'workshop_id', 'id');
    }

    public function room()
    {
        return $this->hasOne('App\Http\Room');
    }

    public function timeslot()
    {
        return $this->hasOne('App\Http\Timeslot');
    }

    public function getEmailsAttribute()
    {
        return DB::table('yearsattending__workshop')
            ->join('yearsattending', 'yearsattending.id', '=', 'yearsattending__workshop.yearattending_id')
            ->join('campers', 'campers.id', '=', 'yearsattending.camper_id')
            ->where('yearsattending__workshop.workshop_id', $this->id)->whereNotNull('campers.email')
            ->implode('email', '; ');
    }
}
