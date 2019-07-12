<?php

namespace App\Http\Controllers;

use App\Building;
use App\Program;

class HomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function campcost()
    {
        return view('campcost', ['background' => 'calculator.jpg', 'rates' => DB::table('rates')
            ->join('years', function ($join) {
                $join->on('rates.start_year', '<=', 'years.year')->on('rates.end_year', '>', 'years.year');
            })->join('programs', 'programs.id', 'rates.programid')
            ->whereIn('buildingid', ['1000', '1007', '1017'])->where('years.is_current', '1')
            ->orderBy('name')->orderBy('min_occupancy')->orderBy('max_occupancy')->get()]);
    }

    public function housing()
    {
        return view('housing', ['buildings' => Building::whereNotNull('blurb')->get(), 'background' => 'housing.jpg']);
    }

    public function programs()
    {
        return view('programs', ['programs' => Program::whereNotNull('blurb')->orderBy('order')->get(), 'background' => 'programs.jpg']);
    }

    public function scholarship()
    {
        return view('scholarship', ['background' => 'scholarship.jpg']);
    }

    public function themespeaker()
    {
        return view('themespeaker', ['background' => 'biographies.jpg']);
    }
}
