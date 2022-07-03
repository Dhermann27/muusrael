<?php

namespace App\Http\Controllers;

use App\Http\Coffeehouseact;
use App\Http\Year;
use App\Models\ThisyearCamper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoffeeController extends Controller
{
    public function store(Request $request)
    {
        $year = $this->getInProgressYear();
        $camper = ThisyearCamper::where('year', $year->year)->where('email', Auth::user()->email)->first();
        if (isset($camper)) {
            foreach ($camper->yearattending->positions as $position) {
                if ($position->staffposition_id == '1117' || $position->staffposition_id == '1103') {
                    $readonly = false;
                }
            }
        }

        if ($readonly === false) {
            foreach ($request->all() as $key => $value) {
                $matches = array();
                if (preg_match('/(\d+)-(delete|order|is_onstage)/', $key, $matches)) {
                    $workshop = Coffeehouseact::find($matches[1]);
                    if ($matches[2] == "delete") {
                        $workshop->delete();
                    } elseif ($workshop) {
                        if ($workshop->{$matches[2]} != $value) {
                            $workshop->{$matches[2]} = $value;
                            $workshop->save();
                        }
                    }
                }
            }

            if ($request->input('name') != '') {
                $workshop = new Coffeehouseact();
                $workshop->year = $year->year;
                $workshop->name = $request->input('name');
                $workshop->equipment = $request->input('equipment');
                $workshop->date = Carbon::createFromFormat('Y-m-d', $year->start_date, 'America/Chicago')
                    ->addDays((int)$request->input('day'))->toDateString();
                $workshop->order = $request->input('order');
                $workshop->save();
            }

            $request->session()->flash('success', 'Laurel. It\'s your conscience. You need to be more truthful in your Tindr profile.');
        } else {
            $request->session()->flash('error', 'No access to this function. How did you get here?');
        }

        return redirect()->action('CoffeeController@index', ['day' => $request->input('day')]);
    }

    public function index($day = null)
    {
        $year = $this->getInProgressYear();
        $firstday = Carbon::createFromFormat('Y-m-d', $year->start_date, 'America/Chicago');
        $acts = Coffeehouseact::where('year', $year->year)->orderBy('order')->get();
        $starttime = Carbon::now('America/Chicago')->hour(21)->minute(50);

        $camper = null;
        $camper = ThisyearCamper::where('year', $year->year)->where('email', Auth::user()->email)->first();
        if (isset($camper)) {
            foreach ($camper->yearattending->positions as $position) {
                if ($position->staffposition_id == '1117' || $position->staffposition_id == '1103') {
                    $readonly = false;
                }
            }
        }

        return view('coffeehouse', ['firstday' => $firstday, 'day' => $day, 'acts' => $acts,
            'starttime' => $starttime, 'readonly' => $readonly]);
    }

    public function getInProgressYear()
    {
        $year = DB::table('years')->whereRaw('NOW() BETWEEN `start_date` and DATE_ADD(`start_date`, INTERVAL 7 DAY)')->first();
        return $year != null ? $year : Year::where('is_current', '1')->first();
    }
}


