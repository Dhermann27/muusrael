<?php

namespace App\Http\Controllers;

use App\Camper;
use App\Church;
use Illuminate\Http\Request;

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
}
