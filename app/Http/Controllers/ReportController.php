<?php

namespace App\Http\Controllers;

use App\Charge;
use App\Chargetype;
use App\Enums\Chargetypename;
use App\ThisyearCharge;
use App\Year;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function preg_match;

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
            'pronounname' => 'Pronouns',
            'firstname' => 'First Name',
            'lastname' => 'Last Name',
            'email' => 'Email',
            'birthday' => 'Birthday',
            'age' => 'Age',
            'programname' => 'Program',
            'days' => 'Days Attending',
            'buildingname' => 'Building',
            'room_number' => 'Room',
            'controls' => 'Admin Controls'];
        $visible = [0, 1, 2, 3, 4, 5, 6, 7, 8, 12, 15];
        $years = Year::where('year', '>', $this->year->year - 7)->where('year', '<=', $this->year->year)->with('byyearcampers')->get();

        return view('reports.datatables', ['title' => 'Registered Campers', 'columns' => $columns,
            'visible' => $visible, 'groupColumn' => 0, 'tabs' => $years, 'tabfield' => 'year',
            'datafield' => "byyearcampers"]);
    }

    public function depositsMark(Request $request, $id)
    {
        $found = false;
        foreach ($request->all() as $key => $value) {
            $matches = array();
            if (preg_match('/mark-(\d+)/', $key, $matches)) {
                $charge = Charge::findOrFail($matches[1]);
                $charge->deposited_date = Carbon::now()->toDateString();
                $charge->save();
                $found = true;
            }
        }
        if (!$found) {
            Charge::where('chargetype_id', $id)->where('deposited_date', null)
                ->update(['deposited_date' => Carbon::now()->toDateString()]);
        }
        $request->session()->flash('success', 'John made me put this message here. Send help.');
        return redirect()->action('ReportController@deposits');
    }

    public function deposits()
    {
        $chargetypes = Chargetype::where('is_deposited', '1')->with('thisyearcharges.camper')->get();
        $moments = ThisyearCharge::whereNotNull('created_at')->where(function ($query) use ($chargetypes) {
            $query->whereIn('chargetype_id', $chargetypes->pluck('id'))
                ->orWhereIn('chargetype_id', [Chargetypename::Donation, Chargetypename::PayPalServiceCharge]);
        })->get()->groupBy(function ($item) {
            return $item->created_at->toISOString();
        });

        return view('reports.deposits', ['chargetypes' => $chargetypes, 'moments' => $moments]);
    }
}
