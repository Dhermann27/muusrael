<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

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
