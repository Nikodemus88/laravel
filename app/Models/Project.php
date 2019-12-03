<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function events()
    {
        return $this->hasMany('App\Models\Event');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
