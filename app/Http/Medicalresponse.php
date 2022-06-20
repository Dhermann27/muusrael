<?php

namespace App\Http;

use Illuminate\Database\Eloquent\Model;

class Medicalresponse extends Model
{
    protected $fillable = ['yearattending_id'];
    public function yearattending()
    {
        return $this->hasOne('App\Http\Yearattending');
    }
}
