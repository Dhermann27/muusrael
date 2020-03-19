<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Year extends Model
{
    public function byyearcampers()
    {
        return $this->hasMany('App\ByyearCamper', 'year_id', 'id')->orderBy('birthdate');
    }

    public function yearsattending()
    {
        return $this->hasMany('App\Yearattending');
    }

    public function chartdataNewcampers()
    {
        return $this->hasMany('App\ChartdataNewcampers', 'year', 'year');
    }

    public function chartdataOldcampers()
    {
        return $this->hasMany('App\ChartdataOldcampers', 'year', 'year');
    }

    public function chartdataVeryoldcampers()
    {
        return $this->hasMany('App\ChartdataVeryoldcampers', 'year', 'year');
    }

    public function chartdataLostcampers()
    {
        return $this->hasMany('App\ChartdataLostcampers', 'year', 'year');
    }

    public function getBrochureDateAttribute() {
        $date = Carbon::createFromFormat('Y-m-d', $this->brochure, 'America/Chicago');
        return $date->format('l F jS');
    }

    public function getDidBrochureAttribute() {
        return $this->getDiffInDays($this->brochure);
    }

    public function getDidCheckinAttribute() {
        return $this->getDiffInDays($this->checkin);
    }

    public function getFirstDayAttribute()
    {
        $date = Carbon::createFromFormat('Y-m-d', $this->checkin, 'America/Chicago');
        return $date->format('l F jS');
    }

    public function getLastDayAttribute()
    {
        $date = Carbon::createFromFormat('Y-m-d', $this->checkin, 'America/Chicago');
        return $date->addDays(6)->format('l F jS');
    }

    public function getNextMuseAttribute()
    {
        $now = Carbon::now('America/Chicago');
        if (Storage::disk('local')->exists('public/muses/' . $now->format('Ymd') . '.pdf')) {
            return "Today's Muse";
        } elseif (Storage::disk('local')->exists('public/muses/' . $now->subDay()->format('Ymd') . '.pdf')) {
            return "Yesterday's Muse";
        } elseif (Storage::disk('local')->exists('public/muses/' . $this->year . '0601.pdf')) {
            return "Pre-Camp Muse";
        } else {
            return false;
        }
    }

    private function getDiffInDays($cardate) {
        return Carbon::createFromFormat('Y-m-d', $cardate, 'America/Chicago')->diffInDays();
    }
}
