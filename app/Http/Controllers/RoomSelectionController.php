<?php

namespace App\Http\Controllers;

use App\Camper;
use App\Jobs\GenerateCharges;
use App\Roomselection;
use App\ThisyearCamper;
use App\Yearattending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RoomSelectionController extends Controller
{
    public function store(Request $request, $id = null)
    {
        $this->validate($request, ['room_id' => 'required|exists:rooms,id']);

        $family_id = $id && Gate::allows('is-super') ? Camper::findOrFail($id)->family_id : Auth::user()->camper->family_id;

        $family = ThisyearCamper::where(['is_program_housing' => '0', 'family_id' => $family_id])->get();
        foreach ($family as $item) {
            $ya = Yearattending::findOrFail($item->yearattending_id);
            $ya->room_id = $request->room_id;
            if($id && Gate::allows('is-super')) {
                $ya->is_setbyadmin = 1;
            }
            $ya->save();
        }

        GenerateCharges::dispatch($this->year->id);

        $success = 'Room selection complete! Your room is locked in for the ' . count($family) . ' eligible members of your household.';

        $request->session()->flash('success', $success);

        return redirect()->action('RoomSelectionController@index', ['id' => $id]);
    }

    public function index(Request $request, $id = null)
    {
        $camper = 0;
        if ($id != null && Gate::allows('is-council')) {
            $request->session()->flash('camper_id', $id);
            $camper = Camper::findOrFail($id);
        } else {
            $camper = Auth::user()->camper;
        }

        $ya = Yearattending::where('camper_id', $camper->id)->where('year_id', $this->year->id)->first();
        $locked = $ya->is_setbyadmin == '1' || $ya->program->is_program_housing == '1';
        $count = ThisyearCamper::where('family_id', $camper->family_id)->where('is_program_housing', '0')->count();
        $rooms = Roomselection::all();

        if ($locked) {
            $request->session()->flash('warning', 'Your room has been locked by the Registrar. Please use the Contact Us form above to request any changes at this point.');
        }

        return view('roomselection', ['ya' => $ya, 'rooms' => $rooms, 'count' => $count, 'locked' => $locked]);
    }

    public function map()
    {
        $empty = new Camper();
        $rooms = Room::where('xcoord', '>', '0')->where('ycoord', '>', '0')->get();
        return view('roomselection', ['camper' => $empty, 'rooms' => $rooms]);
    }

}
