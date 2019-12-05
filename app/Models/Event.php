<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use RRule\RRule;
use Auth;

class Event extends Model
{

	protected $fillable =   ['title', 'description'];
    
    public $rules = [
        'title'             => 'required',
        'user_id'           => 'required',
        'start'             => 'required|date|after:now'
    ];

    public function start()
	{
	    return Carbon::parse($this->start); 
	}

	public function user()
    {
        return $this->belongsTo('App\Models\User', 'id');
    }

	public function project()
    {
        return $this->belongsTo('App\Models\Project', 'id');
    }

    public function getRRuleStringAttribute()
    {   
        $rule = new RRule($this->rrule);

        return $rule;
    }

    public function getNextOccurrence() {
        
        $date = [];

        if($this->rrule) {
            $rrule = new RRule($this->rrule);
            $nextOccurrence = $rrule->getOccurrencesAfter(Carbon::now(), true, 1);
            $date = $nextOccurrence[0];
        }

        return $date;
    }

    public function getProjectNameAttribute () {
        return $this->project;
    }
}
