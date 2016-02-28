<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\League;
use App\Http\Controllers\Controller;

class defaultController extends Controller
{
    //
    //

    public function index(Request $request)
    {
        $league = League::findorFail(1);
        $league->getJson();
        return response()->view('welcome');
    }

    public function getJson(Request $request)
    {
        //http://api.stats.foxsports.com.au/3.0/api/sports/afl/series/1/seasons/120/teams/20002/fixturesandresultswithbyes.json?userkey=A00239D3-45F6-4A0A-810C-54A347F144C2
        //
        $guzzle = new guzzle();

    }

    public function calculate(Request $request)
    {
        $league = League::findorFail(1);
        foreach ($league->seasons as $season) {
            foreach ($season->matches as $match) {
                $match->calculateScores();
            }
        }
    }

    public function create(Request $request)
    {
        $name = $request->input('name');

    }
}
