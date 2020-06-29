<?php

namespace App\Exports;

use App\Http\ByyearCamper;
use App\Http\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use function array_push;
use function count;

class ByyearCampersExport implements FromQuery
{
    use Exportable;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $programs = Program::orderBy('order')->get();

        $rows = ByyearCamper::select('familyname',
            DB::raw('IF(COUNT(*)>1,CONCAT("The ",familyname," Family"),CONCAT(firstname," ",lastname))'),
            'address1', 'address2', 'city', 'provincecode', 'zipcd', 'country', 'pronounname', 'firstname', 'lastname',
            DB::raw('MAX(email)'), 'phonenbr', 'birthday', 'age', 'programname', 'roommate', 'sponsor',
            'churchname', 'churchcity', 'churchprovincecode', 'days', 'room_number', 'buildingname');
        if ($this->request->input("campers") == "reg") {
            $rows->where('year', DB::raw('getcurrentyear()'));
        } elseif ($this->request->input("campers") == "unp") {
            $rows->where('byyear_campers.year', DB::raw('getcurrentyear()'));
            $rows->where(DB::raw('(SELECT SUM(amount) FROM byyear_charges
                    WHERE byyear_campers.family_id=byyear_charges.family_id AND
                          byyear_campers.year=byyear_charges.year AND
                         (chargetypeid=1003 OR amount<0) GROUP BY byyear_campers.family_id)'), '>', 0);
        } elseif ($this->request->input("campers") == "uns") {
            $rows->where('byyear_campers.year', DB::raw('getcurrentyear()'));
            $rows->whereRaw('byyear_campers.family_id IN (SELECT family_id FROM byyear_campers bcp
                    LEFT JOIN medicalresponses m ON bcp.yearattending_id=m.yearattending_id
                    WHERE m.id IS NULL AND bcp.age<18 GROUP BY bcp.family_id)');
        } elseif ($this->request->input("campers") == "oneyear") {
            $rows->where('year', DB::raw('getcurrentyear()-1'));
        } elseif ($this->request->input("campers") == "lost") {
            $rows->where('year', DB::raw('getcurrentyear()-1'));
            $rows->where(DB::raw('(SELECT COUNT(*) FROM thisyear_campers WHERE byyear_campers.id=thisyear_campers.id)'), 0);
        } elseif ($this->request->input("campers") == "loster") {
            $rows->where('year', DB::raw('getcurrentyear()-3'));
            $rows->where(DB::raw('(SELECT COUNT(*) FROM thisyear_campers WHERE byyear_campers.id=thisyear_campers.id)'), 0);
        } elseif ($this->request->input("campers") == "threeyears") {
            $rows->where('year', '>', DB::raw('getcurrentyear()-3'));
        }

        if ($this->request->input("email") == "1") {
            $rows->whereNotNull('email');
        }
        if ($this->request->input("ecomm") != '-1') {
            $rows->where('is_ecomm', $this->request->input("ecomm"));
        }

        if ($this->request->input("current") == "1") {
            $rows->where('is_address_current', '1');
        }

        $programids = array();
        foreach ($programs as $program) {
            if ($this->request->input("program-" . $program->id) == "on") {
                array_push($programids, $program->id);
            }
        }
        if (count($programids) > 0) {
            $rows->whereIn('program_id', $programids);
        }

        $rows->groupBy("byyear_campers." . $this->request->input("groupby"));

        $rows->orderBy('familyname')->orderBy('family_id')->orderBy('birthdate');

        return $rows;

    }
}
