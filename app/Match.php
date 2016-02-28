<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        return $this->BelongsToMany('App\Team');
    }
}
