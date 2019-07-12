<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Church extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'churches';

    public function province()
    {
        return $this->hasOne('App\Province');
    }
}
