<?php

namespace App\Http\Controllers;

use App\Camper;
use App\Church;
use App\Family;
use App\Medicalresponse;
use App\Yearattending;
use App\YearattendingWorkshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DataController extends Controller
{
//    public function campers(Request $request)
//    {
//        $this->validate($request, ['term' => 'required|between:3,50']);
//        $campers = \App\Camper::search($request->term)->with('family')->get();
//        foreach($campers as $camper) {
//            $camper->term = $request->term;
//        }
//        return $campers;
//    }
//
    public function churches(Request $request)
    {
        $this->validate($request, ['term' => 'required|between:3,50']);
        $churches = Church::select('id', 'name', 'city', 'province_id')->where('name', 'LIKE', '%' . $request->term . '%')
            ->orWhere('city', 'LIKE', '%' . $request->term . '%')->with('province')->get();
        foreach ($churches as $church) {
            $church->term = $request->term;
        }
        return $churches;
    }

    public function loginsearch(Request $request)
    {
        $this->validate($request, ['term' => 'required|email']);
        $camper = Camper::where('email', $request->term)->firstOrFail();
        $campers = Camper::select('id', 'firstname', 'lastname')->where('family_id', $camper->family_id)->orderBy('birthdate')->get();
        return $campers;
    }

    public function steps()
    {
        $family = Family::findOrFail(Auth::user()->camper->family->id)->first()->city != null;
        $workshops = 0;
        $room = 0;
        $nametag = 0;
        $medical = 0;
        $live = $this->year->is_live ? false : $this->year->brochure_date;
        $ya = Yearattending::where('camper_id', Auth::user()->camper->id)->where('year_id', $this->year->id)->first();
        if($ya) {
            $workshops = YearattendingWorkshop::where('yearattending_id', $ya->id)->get()->count() > 0;
            $room = $ya->room_id != null;
            $nametag = $ya->nametag != "222215521";
            $medical = Medicalresponse::where('yearattending_id', $ya->id)->count() > 0;
            $ya = true;
        } else {
            $ya = false;
        }
        return [$family, $ya, Gate::allows('has-paid'), $workshops, $room, $nametag, $medical, Auth::user()->camper->firstname, $live];
    }
}
