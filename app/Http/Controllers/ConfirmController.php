<?php

namespace App\Http\Controllers;

use App\Http\ThisyearFamily;

class ConfirmController extends Controller
{

    public function all()
    {
        return view('confirm', ['families' => ThisyearFamily::orderBy('familyname')->get()]);

    }
}
