<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    public function league()
    {
        return $this->belongsTo('App\League', 'league_id');
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

    public function match()
    {
        return $this->hasOne('App\Match');
    }

    public function matches()
    {
        return $this->hasMany('App\Match');
    }
}
