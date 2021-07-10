<?php

namespace App\Http;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Year extends Model
{
    public function byyearcampers()
    {
        return $this->hasMany('App\Http\ByyearCamper', 'year_id', 'id')->orderBy('birthdate');
    }

    public function yearsattending()
    {
        return $this->hasMany('App\Http\Yearattending');
    }

    public function chartdataNewcampers()
    {
        return $this->hasMany('App\Http\ChartdataNewcampers', 'year', 'year');
    }

    public function chartdataOldcampers()
    {
        return $this->hasMany('App\Http\ChartdataOldcampers', 'year', 'year');
    }

    public function chartdataVeryoldcampers()
    {
        return $this->hasMany('App\Http\ChartdataVeryoldcampers', 'year', 'year');
    }

    public function chartdataLostcampers()
    {
        return $this->hasMany('App\Http\ChartdataLostcampers', 'year', 'year');
    }

    public function getBrochureDateAttribute()
    {
        $date = Carbon::createFromFormat('Y-m-d', $this->brochure, 'America/Chicago');
        return $date->format('l F jS');
    }

    public function getDidBrochureAttribute()
    {
        return $this->getDiffInDays($this->brochure);
    }

    public function getDidCheckinAttribute()
    {
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

    public function getNextDayAttribute()
    {
        $lastfirst = Carbon::createFromFormat('Y-m-d', Year::where('year', $this->year - 1)->first()->checkin, 'America/Chicago');
        $now = Carbon::now('America/Chicago');
        if ($now->between($lastfirst, $lastfirst->addDays(7))) {
            return $now;
        }
        return $now->max(Carbon::createFromFormat('Y-m-d', $this->checkin, 'America/Chicago'));
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

    private function getDiffInDays($cardate)
    {
        return Carbon::createFromFormat('Y-m-d', $cardate, 'America/Chicago')->diffInDays();
    }
}
