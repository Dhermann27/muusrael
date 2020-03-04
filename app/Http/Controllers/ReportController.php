<?php

namespace App\Http\Controllers;

use App\Charge;
use App\Chargetype;
use App\Year;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function campers()
    {
        $columns = ["familyname" => "Family Name",
            "pronounname" => "Pronouns",
            "firstname" => "First Name",
            "lastname" => "Last Name",
            "email" => "Email",
            "age" => "Age",
            "programname" => "Program",
            "buildingname" => "Building",
            "room_number" => "Room"];
        $years = Year::where('year', '>', $this->year->year - 7)->where('year', '<=', $this->year->year)->with('byyearcampers')->get();

        return view('reports.datatables', ['title' => 'Registered Campers', 'columns' => $columns,
            'tabs' => $years, 'tabfield' => 'year', 'datafield' => "byyearcampers"]);
    }

//    public function campersExport($year = 0)
//    {
//        $year = $year == 0 ? $this->year->year : (int)$year;
//        Excel::create('MUUSA_' . $year . '_Campers_' . Carbon::now()->toDateString(), function ($excel) use ($year) {
//            $excel->sheet('campers', function ($sheet) use ($year) {
//                $sheet->setOrientation('landscape');
//                $sheet->with(ByyearCamper::select('familyname', 'address1', 'address2', 'city', 'statecd',
//                    'zipcd', 'country', 'pronounname', 'firstname', 'lastname', 'email', 'phonenbr', 'birthday', 'age',
//                    'programname', 'roommate', 'sponsor', 'churchname', 'churchcity', 'churchstatecd', 'days',
//                    'room_number', 'buildingname')->where('year', $year)
//                    ->orderBy('familyname')->orderBy('familyid')->orderBy('birthdate')->get());
//            });
//        })->export('xls');
//    }

    public function depositsMark($id)
    {
        Charge::where('chargetype_id', $id)->where('deposited_date', null)
            ->update(['deposited_date' => Carbon::now()->toDateString()]);
        return redirect()->action('ReportController@deposits');
    }

    public function deposits()
    {
        return view('reports.deposits',
            ['chargetypes' => Chargetype::where('is_deposited', '1')->with('thisyearcharges.camper')->get()]);
    }
}
