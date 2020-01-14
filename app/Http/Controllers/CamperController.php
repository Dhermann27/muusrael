<?php

namespace App\Http\Controllers;

use App\Camper;
use App\Campers_view;
use App\Foodoption;
use App\Mail\Confirm;
use App\Program;
use App\Pronoun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function array_push;

class CamperController extends Controller
{
    private $messages = ['pronounid.*.exists' => 'Please choose a preferred pronoun.',
        'firstname.*.required' => 'Please enter a first name.',
        'lastname.*.required' => 'Please enter a last name.',
        'email.*.email' => 'Please enter a valid email address.',
        'email.*.distinct' => 'Please do not use the same email address for multiple campers.',
        'email.*.unique' => 'This email address has already been taken.',
        'phonenbr.*.regex' => 'Please enter your ten-digit phone number in 800-555-1212 format.',
        'birthdate.*.required' => 'Please enter your eight-digit birthdate in 2016-12-31 format.',
        'birthdate.*.regex' => 'Please enter your eight-digit birthdate in 2016-12-31 format.'];

//    public function store(Request $request)
//    {
//        $logged_in = Auth::user()->camper;
//
//        $this->validate($request, [
//            'days.*' => 'between:0,8',
//            'pronounid.*' => 'exists:pronouns,id',
//            'firstname.*' => 'required|max:255',
//            'lastname.*' => 'required|max:255',
//            'email.*' => 'email|max:255|distinct',
//            'phonenbr.*' => 'regex:/^\d{3}-\d{3}-\d{4}$/',
//            'birthdate.*' => 'required|regex:/^\d{4}-\d{2}-\d{2}$/',
//            'programid.*' => 'required|exists:programs,id',
//            'roommate.*' => 'max:255',
//            'sponsor.*' => 'max:255',
//            'churchid.*' => 'exists:churches,id',
//            'is_handicap.*' => 'in:0,1',
//            'foodoptionid.*' => 'exists:foodoptions,id',
//        ], $this->messages);
//
//
//        $campers = array();
//        for ($i = 0; $i < count($request->input('id')); $i++) {
//            $id = $request->input('id')[$i];
//            if ($id != 999) {
//                $camper = \App\Camper::find($id);
//
//                $this->validate($request, [
//                    'email.' . $i => 'unique:campers,email,' . $id,
//                ], $this->messages);
//
//                if ($id == $logged_in->id) {
//                    $this->validate($request, [
//                        'email.' . $i => 'unique:users,email,' . Auth::user()->id,
//                    ], $this->messages);
//                }
//                if ($camper->familyid == $logged_in->familyid) {
//                    $thiscamper = $this->upsertCamper($request, $i, $logged_in->familyid);
//                    if ((int)$request->input('days')[$i] > 0) {
//                        array_push($campers, $thiscamper);
//                    }
//                }
//            } else {
//                $thiscamper = $this->upsertCamper($request, $i, $logged_in->familyid);
//                if ((int)$request->input('days')[$i] > 0) {
//                    array_push($campers, $thiscamper);
//                }
//            }
//        }
//
//        DB::statement('CALL generate_charges(' . $this->year->year . ');');
//
//        Mail::to(Auth::user()->email)->send(new Confirm($this->year, $campers));
//
//        return 'You have successfully saved your changes and registered. Click <a href="' . url('/payment') . '">here</a> to remit payment.';
//    }
//
    public function index(Request $request)
    {
//            $request->session()->flash('warning', 'You have not yet created your household information.');
//            return redirect()->action('HouseholdController@index');
//        }
        $campers = array();
        if (isset(Auth::user()->camper)) {
            $campers = Campers_view::where('family_id', Auth::user()->camper->family_id)->get();
            if ($request->session()->has('login_campers') && count($request->session()->get('login_campers[]')) > 0) {
                foreach ($campers as $camper) {
                    foreach ($request->session()->get('login_campers') as $login_campers) {
                        if ($camper->id == $login_campers)
                            $camper->currentdays = 6;
                    }
                }
            }
        } else {
            for ($i = 0; $i < $request->session()->get('newcampers'); $i++) {
                $camper = new Camper();
                $camper->id = 100 + $i;
                array_push($campers, $camper);
            }
        }

        $empty = new Camper();
        $empty->id = 999;
        return view('campers', ['pronouns' => Pronoun::all(), 'foodoptions' => Foodoption::all(),
            'campers' => $campers, 'programs' => Program::whereNotNull('title')->orderBy('order')->get(),
            'empty' => $empty, 'readonly' => null]);//, 'steps' => $this->getSteps()]);

    }
//
//    public function write(Request $request, $id)
//    {
//
//
//        $this->validate($request, [
//            'days.*' => 'between:0,8',
//            'pronounid.*' => 'exists:pronouns,id',
//            'firstname.*' => 'required|max:255',
//            'lastname.*' => 'required|max:255',
//            'email.*' => 'email|max:255|distinct',
//            'phonenbr.*' => 'regex:/^\d{3}-\d{3}-\d{4}$/',
//            'birthdate.*' => 'required|regex:/^\d{4}-\d{2}-\d{2}$/',
//            'programid.*' => 'required|exists:programs,id',
//            'roommate.*' => 'max:255',
//            'sponsor.*' => 'max:255',
//            'churchid.*' => 'exists:churches,id',
//            'is_handicap.*' => 'in:0,1',
//            'foodoptionid.*' => 'exists:foodoptions,id',
//        ], $this->messages);
//
//        for ($i = 0; $i < count($request->input('id')); $i++) {
//
//            $this->validate($request, [
//                'email.' . $i => 'unique:campers,email,' . $request->input('id')[$i],
//            ], $this->messages);
//
//            $this->upsertCamper($request, $i, $id);
//        }
//
//        DB::statement('CALL generate_charges(' . $this->year->year . ');');
//
//        return 'You did it! Need to make see their <a href="' . url('/payment/f/' . $id) . '">statement</a> next?';
//    }
//
//    public function read($i, $id)
//    {
//        $readonly = \Entrust::can('read') && !\Entrust::can('write');
//        $family = \App\Family::find($this->getFamilyId($i, $id));
//        $campers = $this->getCampers($family->id);
//
//        $empty = new \App\Camper();
//        $empty->id = 999;
//
//        return view('campers', ['pronouns' => \App\Pronoun::all(), 'foodoptions' => \App\Foodoption::all(),
//            'campers' => $campers, 'programs' => \App\Program::whereNotNull('display')->orderBy('order')->get(),
//            'empty' => $empty, 'readonly' => $readonly, 'steps' => $this->getSteps()]);
//    }
//
//    private function upsertCamper(Request $request, $i, $familyid)
//    {
//        if ($request->input('id')[$i] != '999') {
//            $camper = \App\Camper::findOrFail($request->input('id')[$i]);
//        } else {
//            $camper = new \App\Camper;
//        }
//        $camper->familyid = $familyid;
//        $camper->pronounid = $request->input('pronounid')[$i];
//        $camper->firstname = $request->input('firstname')[$i];
//        $camper->lastname = $request->input('lastname')[$i];
//
//        if ($request->input('email')[$i] != '') {
//            if ($camper->email != $request->input('email')[$i]) {
//                DB::table('users')->where('email', $camper->email)->update(['email' => $request->input('email')[$i]]);
//            }
//            $camper->email = $request->input('email')[$i];
//        }
//        if ($request->input('phonenbr')[$i] != '') {
//            $camper->phonenbr = str_replace('-', '', $request->input('phonenbr')[$i]);
//        }
//        $camper->birthdate = $request->input('birthdate')[$i];
//        $programid = $request->input('programid')[$i];
//        if ($programid == '1009' && Carbon::createFromFormat('Y-m-d', $camper->birthdate)->diffInYears(Carbon::createFromFormat('Y-m-d', $this->year->start_date)) < 21) {
//            $programid = '1006';
//        }
//
//        $camper->roommate = $request->input('roommate')[$i];
//        $camper->sponsor = $request->input('sponsor')[$i];
//        if(array_key_exists($i, $request->input('churchid'))) {
//            $camper->churchid = $request->input('churchid')[$i];
//        }
//        $camper->is_handicap = $request->input('is_handicap')[$i];
//        $camper->foodoptionid = $request->input('foodoptionid')[$i];
//
//        $camper->save();
//
//        if ((int)$request->input('days')[$i] > 0) {
//            $ya = \App\Yearattending::updateOrCreate(['camperid' => $camper->id, 'year' => $this->year->year],
//                ['days' => $request->input('days')[$i], 'programid' => $programid]);
//            $camper->yearattendingid = $ya->id;
//            $staffs = \App\Camper__Staff::where('camperid', $camper->id)->get();
//            if (count($staffs) > 0) {
//                foreach ($staffs as $staff) {
//                    \App\Yearattending__Staff::updateOrCreate(['yearattendingid' => $ya->id, 'staffpositionid' => $staff->staffpositionid]);
//                }
//                \App\Camper__Staff::where('camperid', $camper->id)->delete();
//            }
//        } else {
//            $ya = \App\Yearattending::where(['camperid' => $camper->id, 'year' => $this->year->year])->first();
//            if ($ya != null) {
//                if ($request->input('days')[$i] == '0') {
//                    \App\Yearattending__Workshop::where('yearattendingid', $ya->id)->delete();
//                    \App\Yearattending__Staff::where('yearattendingid', $ya->id)->delete();
//                    $ya->delete();
//                }
//            }
//        }
//
//        return $camper;
//    }
//
//
//    private function getFamilyId($i, $id)
//    {
//        return $i == 'c' ? \App\Camper::find($id)->familyid : $id;
//    }
}
