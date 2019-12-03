<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Event;

class Company extends Model
{
    protected $fillable = [
        'name'
    ];

    public function events()
    {
        return $this->hasMany(Event::class, 'company_id',  'id');
    }
}
