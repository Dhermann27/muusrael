<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateCharges;
use App\Roomselection;
use App\ThisyearCamper;
use App\Year;
use App\Yearattending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoomSelectionController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, ['room_id' => 'required|numeric|between:999,99999']);

        $family = ThisyearCamper::where(['is_program_housing' => '0', 'family_id' => Auth::user()->camper->family_id])->get();
        foreach ($family as $item) {
            $ya = Yearattending::find($item->yearattending_id);
            $ya->room_id = $request->room_id;
            $ya->save();
        }

//        GenerateCharges::dispatch($this->year->year)->delay(now()->addSeconds(3));
        DB::statement('CALL generate_charges(' . $this->year . ');');

        $success = 'Room selection complete! Your room is locked in for the ' . count($family) . ' eligible members of your household.';
        if (Year::where('is_current', '1')->first()->is_live) $success .= ' Customize your nametag by clicking <a href="' . url('/nametag') . '">here</a>.';

        $request->session()->flash('success', $success);

        return redirect()->action('RoomSelectionController@index', ['request' => $request]);
    }

    public function index(Request $request)
    {
        $ya = Yearattending::where('camper_id', Auth::user()->camper->id)->where('year_id', $this->year->id)->first();
        $locked = $ya->is_setbyadmin == '1' || $ya->program->is_program_housing == '1';
        $count = ThisyearCamper::where('family_id', Auth::user()->camper->family_id)->where('is_program_housing', '0')->count();
        $rooms = Roomselection::all();

        if ($locked) {
            $request->session()->flash('warning', 'Your room has been locked by the Registrar. Please use the Contact Us form above to request any changes at this point.');
        }

        return view('roomselection', ['ya' => $ya, 'rooms' => $rooms, 'count' => $count, 'locked' => $locked]);
    }

//    public function map()
//    {
//        $empty = new \App\Camper();
//        $rooms = \App\Room::where('xcoord', '>', '0')->where('ycoord', '>', '0')->get();
//        return view('roomselection', ['camper' => $empty, 'rooms' => $rooms]);
//    }

//    public function write(Request $request, $id)
//    {
//
//        $campers = \App\Thisyear_Camper::where('familyid', $id)->get();
//
//        foreach ($campers as $camper) {
//            $ya = \App\Yearattending::find($camper->yearattendingid);
//            $ya->roomid = $request->input($camper->id . '-roomid');
//            if($ya->roomid == 0) $ya->roomid = null;
//            $ya->is_setbyadmin = '1';
//            $ya->save();
//        }
//
//        DB::statement('CALL generate_charges(getcurrentyear());');
//
//        $request->session()->flash('success', 'Awwwwwwww yeahhhhhhhhh');
//
//        return redirect()->action('RoomSelectionController@read', ['i' => 'f', 'id' => $id]);
//    }
//
//    public function read($i, $id)
//    {
//        $readonly = \Entrust::can('read') && !\Entrust::can('write');
//        $family = \App\Family::find($this->getFamilyId($i, $id));
//        $campers = \App\Thisyear_Camper::where('familyid', $family->id)->with('yearsattending.room.building')
//            ->orderBy('birthdate')->get();
//
//        return view('admin.rooms', ['buildings' => \App\Building::with('rooms.occupants')->get(),
//            'campers' => $campers, 'readonly' => $readonly);
//    }

    private function getFamilyId($i, $id)
    {
        return $i == 'c' ? \App\Camper::find($id)->familyid : $id;
    }
}
