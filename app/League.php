<?php

namespace App;

use App\Sport;
use App\Match;
use App\Ground;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client as guzzle;

class League extends Model
{
    //
    

    public function sport()
    {

        return $this->belongsTo('App\Sport', 'sport_id');
    }

    public function season()
    {
        return Season::find($this->id);
    }

    public function seasons()
    {
        return $this->hasMany('App\Season');
    }

    public function team()
    {
        return $this->hasOne('App\Team');
    }

    public function teams()
    {
        return $this->hasMany('App\Team');
    }

    public function ground()
    {
        return $this->hasOne('App\Ground');
    }

    public function grounds()
    {
        return $this->hasMany('App\Ground');
    }

    public function getJson()
    {
        $client = new Guzzle();
        $response = $client->get('http://api.stats.foxsports.com.au/3.0/api/sports/afl/series/1/seasons/115/fixturesandresultswithbyes.json?userkey=A00239D3-45F6-4A0A-810C-54A347F144C2');
        $result = json_decode($response->getBody());
        try {
            
            // First find the season
            $season = Season::where('seasonName', '=', $result[0]->series->name . " " . $result[0]->season->name)->first();
            if (empty($season)) {
                $season = new Season();
                $season->seasonName = $result[0]->series->name . " " . $result[0]->season->name;
                $season->league_id = $this->id;
                $season->save();
            }
            foreach ($result as $match) {
                if (is_null($match->fixture_id)) {
                    continue;
                }
                if (Ground::where('groundName', '=', $match->venue->name)->count() == 0) {
                    $ground = new Ground();
                    $ground->groundName = $match->venue->name;
                    $ground->save();
                } else {
                    $ground = Ground::where('groundName', '=', $match->venue->name)->first();
                }
                if (Match::where('season_id', '=', $season->id)->where('match_number', '=', $match->fixture_id)->count() == 0) {
                    $gameMatch = new Match();
                    $gameMatch->match_number = $match->fixture_id;
                    $gameMatch->match_start = $match->match_start_date;
                    $gameMatch->is_final = $match->is_final;
                    $gameMatch->is_grand_final = $match->is_grand_final;
                    $gameMatch->season_id = $season->id;
                    $gameMatch->ground_id = $ground->id;
                    $gameMatch->save();
                } else {
                    $gameMatch = Match::where('match_number', '=', $match->fixture_id)->first();
                }
                
                if (Team::where('sport_id', '=', $this->sport->id)->where('teamName', '=', $match->team_A->name)->count() == 0) {
                    $teamA = new Team();
                    $teamA->teamName = $match->team_A->name;
                    $teamA->sport_id = $this->sport->id;
                    $teamA->save();
                } else {
                    $teamOne = Team::where('sport_id', '=', $this->sport->id)->where('teamName', '=', $match->team_A->name)->get();
                    $teamA = Team::find($teamOne[0]->id);
                }
                
                if (Team::where('sport_id', '=', $this->sport->id)->where('teamName', '=', $match->team_B->name)->count() == 0) {
                    $teamB = new Team();
                    $teamB->teamName = $match->team_B->name;
                    $teamB->sport_id = $this->sport->id;
                    $teamB->save();
                } else {
                    $teamTwo = Team::where('sport_id', '=', $this->sport->id)->where('teamName', '=', $match->team_B->name)->get();
                    $teamB = Team::find($teamTwo[0]->id);
                }
                if ($match->team_A->id == $match->winning_team_id) {
                    $teamAWin = true;
                    $teamBWin = false;

                } elseif ($match->team_B->id == $match->winning_team_id) {
                    $teamAWin = false;
                    $teamBWin = true;
                } else {
                    $teamAWin = false;
                    $teamBWin = false;
                }
                $gameMatch->teams()->sync([$teamA->id => ['is_home' => true, 'is_winner' => $teamAWin , 'super_goals' => $match->team_A->superGoals, 'goals' => $match->team_A->goals, 'behinds' => $match->team_A->behinds, 'points' => $match->team_A->score]], false);
                $gameMatch->teams()->sync([$teamB->id => ['is_home' => false, 'is_winner' => $teamBWin , 'super_goals' => $match->team_B->superGoals, 'goals' => $match->team_B->goals, 'behinds' => $match->team_B->behinds, 'points' => $match->team_B->score]], false);
                //$gameMatch->calculateScores();
            }
        } catch (Exception $e) {
            dd($e->getMessag());
        }
    }
}
