<?php

namespace App\Http\Controllers;

use App\Compensationlevel;
use App\Program;
use App\Staffposition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    public function positionStore(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            $matches = array();
            if (preg_match('/(delete|program_id|name|compensationlevel_id)-(\d+)/', $key, $matches)) {
                $position = Staffposition::findOrFail($matches[2]);
                if ($matches[1] == 'delete') {
                    if ($value == 'on') {
                        DB::table('yearsattending__staff')
                            ->join('yearsattending', 'yearsattending.id', '=', 'yearsattending__staff.yearattending_id')
                            ->where('yearsattending.year_id', $this->year->id)
                            ->where('yearsattending__staff.staffposition_id', $matches[2])->delete();
                        $position->end_year = $this->year->year - 1;
                        $position->save();
                    }
                } else {
                    $position->{$matches[1]} = $value;
                    $position->save();
                }
            }
        }

        if ($request->input('name') != '') {
            $position = new Staffposition();
            $position->program_id = $request->input('program_id');
            $position->name = $request->input('name');
            $position->compensationlevel_id = $request->input('compensationlevel_id');
            $position->pctype = $request->input('pctype');
            $position->start_year = $this->year->year;
            $position->save();
        }

        $request->session()->flash('success', 'You created those positions like a <i>pro</i>.');

        return redirect()->action('AdminController@positionIndex');
    }

    public function positionIndex()
    {
        return view('admin.positions', ['programs' => Program::with('staffpositions.compensationlevel')->orderBy('order')->get(),
            'levels' => Compensationlevel::all()]);
    }

}
