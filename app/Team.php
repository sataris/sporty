<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client as Guzzle;

class Team extends Model
{
    //
    //
    public function season()
    {
        return $this->belongsTo('App\Season');
    }

    public function seasons()
    {
        return $this->belongsToMany('App\Season');
    }

    public function ground()
    {
        return$this->hasOne('App\Ground');
    }

    public function matches()
    {
        return $this->hasMany('App\Match');
    }

    public function sport()
    {
        return $this->hasOne('App\Sport');
    }

    public function getJson()
    {

        $client = new Guzzle();
        /*$res = $client->get('http://api.stats.foxsports.com.au/3.0/api/sports/afl/series/1/seasons/120/teams/20002/fixturesandresultswithbyes.json?userkey=A00239D3-45F6-4A0A-810C-54A347F144C2');
        echo $res->getStatusCode(); // 200
        echo $res->getBody(); // { "type": "User", ....*/
        $response = $client->get('http://api.stats.foxsports.com.au/3.0/api/sports/afl/series/1/seasons/120/fixturesandresultswithbyes.json?userkey=A00239D3-45F6-4A0A-810C-54A347F144C2');
        $result = json_decode($response->getBody());
        dd($result);
    }
}
