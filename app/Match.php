<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Match extends Model
{
    //
    //
    public function season()
    {
        return $this->belongsTo('App\Season');
    }

    public function teams()
    {
        return $this->belongsToMany('App\Team')->withPivot('points', 'is_home');
    }

    //PREDICTED SCORE = 85 * (TEAM ATTACK ÷ OPPOSITION DEFENCE) + HOME ADVANTAGE
    public function calculateScores()
    {
        foreach ($this->teams as $team) {
            if ($team->pivot->points == 0) {
                return false;
            }
            if ($team->pivot->is_home) {
                $homeTeam = $team;
            } else {
                $awayTeam = $team;
            }
        }
        $typicalScore = DB::select("SELECT AVG(points) as typicalScore FROM match_team where team_id = " . $homeTeam->id . " AND match_id IN (SELECT match_id FROM match_team where team_id = " . $awayTeam->id . ")");
        $homeScore = DB::select("SELECT AVG(points) as typicalScore FROM match_team where team_id = " . $homeTeam->id . " AND is_home = 1 AND match_id IN (SELECT match_id FROM match_team where team_id = " . $awayTeam->id . ")");
        $homeAdvantage = $homeScore[0]->typicalScore - $typicalScore[0]->typicalScore;
        echo $homeTeam->teamName . " vs " . $awayTeam->teamName . " typical score is " . $typicalScore[0]->typicalScore . " Home advantage: " . $homeAdvantage;
        //Calculat ethe home attack first
        $factor = $homeTeam->pivot->points/$typicalScore[0]->typicalScore;
        $factor = $factor * 10000000000000;
        $array= $this->simplify($factor, 10000000000000);
        $homeTeam->competitors()->sync([$homeTeam->id => ['team_id_one_attack' => $array[0], 'team_id_two_defense' => $array[1]]]);
        dd($this->simplify($factor, 10000000000000));

        dd($homeTeam->pivot->points);
        dd('the end');

    }

    function simplify($num, $den)
    {
        $g = $this->gcd($num, $den);
        return array($num/$g,$den/$g);
    }

    function gcd($a, $b)
    {
        $a = abs($a);
        $b = abs($b);
        if ($a < $b) {
            list($b,$a) = array($a,$b);
        }
        if ($b == 0) {
            return $a;
        }
        $r = $a % $b;
        while ($r > 0) {
            $a = $b;
            $b = $r;
            $r = $a % $b;
        }
        return $b;
    }
}
