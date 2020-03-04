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
        $columns = ['familyname' => 'Family Name',
            'address1' => 'Address Line #1',
            'address2' => 'Address Line #2',
            'city' => 'City',
            'provincecode' => 'Province',
            'zipcd' => 'Postal Code',
            'country' => 'Country',
            'birthday' => 'Birthday',
            'pronounname' => 'Pronouns',
            'firstname' => 'First Name',
            'lastname' => 'Last Name',
            'email' => 'Email',
            'age' => 'Age',
            'programname' => 'Program',
            'days' => 'Days Attending',
            'buildingname' => 'Building',
            'room_number' => 'Room',
            'controls' => 'Admin Controls'];
        $visible = [0, 1, 2, 3, 4, 5, 6, 7, 8, 12, 14];
        $years = Year::where('year', '>', $this->year->year - 7)->where('year', '<=', $this->year->year)->with('byyearcampers')->get();

        return view('reports.datatables', ['title' => 'Registered Campers', 'columns' => $columns,
            'visible' => $visible, 'groupColumn' => 0, 'tabs' => $years, 'tabfield' => 'year',
            'datafield' => "byyearcampers"]);
    }

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
