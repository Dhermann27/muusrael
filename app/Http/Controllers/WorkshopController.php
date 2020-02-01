<?php

namespace App\Http\Controllers;

use App\Enums\Programname;
use App\Enums\Timeslotname;
use App\ThisyearCamper;
use App\Timeslot;
use App\YearattendingWorkshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkshopController extends Controller
{
    public function store(Request $request)
    {
        $campers = $this->getCampers(Auth::user()->camper->family_id);

        foreach ($campers as $camper) {
            $this->validate($request, [$camper->id . '-workshops' => 'nullable|regex:/^\d{0,5}+(,\d{0,5})*$/']);
            if ($request->input($camper->id . '-workshops') != null) {
                $choices = YearattendingWorkshop::where('yearattending_id', $camper->yearattending_id)
                    ->get()->keyBy('workshop_id');

                foreach (explode(',', $request->input($camper->id . '-workshops')) as $choice) {
                    $yw = YearattendingWorkshop::where(['yearattending_id' => $camper->yearattending_id, 'workshop_id' => $choice])->first();
                    if (!$yw) {
                        $yw = new YearattendingWorkshop();
                        $yw->yearattending_id = $camper->yearattending_id;
                        $yw->workshop_id = $choice;
                    }
                    $yw->save();

                    $choices->forget($choice);
                }

                if (count($choices) > 0) {
                    foreach ($choices as $choice) {
                        DB::statement('DELETE FROM yearsattending__workshop WHERE yearattending_id=' .
                            $choice->yearattending_id . ' AND workshop_id=' . $choice->workshop_id);
                    }
                }
            }
        }

        DB::statement('CALL workshops();');

        $request->session()->flash('success', 'Your workshop selections have been updated.');

        return redirect()->action('WorkshopController@index');
    }

    public function index(Request $request)
    {
        $campers = $this->getCampers(Auth::user()->camper->family_id);
        if (count($campers) == 0) {
            $request->session()->flash('warning', 'You have no campers registered for this year.');
            return redirect()->action('CamperController@index');
        }
        return view('workshopchoice', ['timeslots' => Timeslot::with('workshopsview')->get(),
            'campers' => $campers, 'grownups' => [Programname::YoungAdultUnderAge, Programname::YoungAdult, Programname::Adult]]);

    }
//
//    public function write(Request $request, $id)
//    {
//
//        $campers = $this->getCampers($id);
//
//        foreach ($campers as $camper) {
//            $choices = \App\Yearattending__Workshop::where('yearattendingid', $camper->yearattendingid)
//                ->get()->keyBy('workshopid');
//
//            foreach (explode(',', $request->input($camper->id . '-workshops')) as $choice) {
//                if ($choice != '') {
//                    \App\Yearattending__Workshop::updateOrCreate(
//                        ['yearattendingid' => $camper->yearattendingid, 'workshopid' => $choice],
//                        ['yearattendingid' => $camper->yearattendingid, 'workshopid' => $choice]);
//
//                    $choices->forget($choice);
//                }
//            }
//
//            if (count($choices) > 0) {
//                foreach ($choices as $choice) {
//                    DB::statement('DELETE FROM yearattending__workshop WHERE yearattendingid=' .
//                        $choice->yearattendingid . ' AND workshopid=' . $choice->workshopid);
//                }
//            }
//        }
//
//        DB::statement('CALL workshops();');
//        DB::statement('CALL generate_charges(getcurrentyear());');
//
//        $request->session()->flash('success', 'Green means good! Yayyyyyy');
//
//        return redirect()->action('WorkshopController@read', ['i' => 'f', 'id' => $id]);
//    }
//
//    public function read($i, $id)
//    {
//        $readonly = \Entrust::can('read') && !\Entrust::can('write');
//        return view('workshopchoice', ['timeslots' => \App\Timeslot::with('workshops.choices')->get(),
//            'campers' => $this->getCampers($i == 'f' ? $id : \App\Camper::find($id)->familyid),
//            'readonly' => $readonly, 'steps' => $this->getSteps()]);
//    }

    public function display()
    {
        return view('workshops', ['timeslots' => Timeslot::all()->where('id', '!=', Timeslotname::Excursions)]);
    }

    public function excursions()
    {
        return view('excursions', ['timeslot' => Timeslot::findOrFail(Timeslotname::Excursions)]);

    }

    private function getCampers($id)
    {
        return ThisyearCamper::where('family_id', $id)->with('yearattending.workshops')->orderBy('birthdate')->get();
    }

}
