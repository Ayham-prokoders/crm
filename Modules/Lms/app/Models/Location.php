<?php

namespace Modules\Lms\Models;

use Modules\Lms\Models\City;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Lms\Database\Factories\LocationFactory;

class Location extends Model
{
    use HasFactory;
    protected $fillable=[
        'external_id',
        'title',
        'description',
        'lat',
        'long',
        'city_id'
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}