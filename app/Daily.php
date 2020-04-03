<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Daily extends Model
{
    public function user(){
        return $this->belongsTo('App\User');
    }

    protected $table = "daily_scrum";
}
