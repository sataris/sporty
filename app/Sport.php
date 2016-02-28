<?php

namespace App;

use App\League;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client as guzzle;

class Sport extends Model
{
    //
    

    public function leagues()
    {

        return $this->hasMany('App\League');
    }


    public function getJson()
    {
        $client = new Guzzle();
        $response = $client->get('http://api.stats.foxsports.com.au/3.0/api/sports/afl/series/1/seasons/120/fixturesandresultswithbyes.json?userkey=A00239D3-45F6-4A0A-810C-54A347F144C2');
        $result = json_decode($response->getBody());
        foreach ($result as $match) {
            // First sync the series with our league and then our season.
            echo $match->series->name;
            dd($match);
            
        }
    }
}
