<?php

namespace App;

use App\Bases\BaseModel;

class Movie extends BaseModel
{
    protected $table = 'movies'; // Table name

    /*
     * Defining Fillable Attributes On A Model
     */
    protected $fillable = [
        'title',
        'genre',
        'release_date'
    ];

    protected $dates = ['release_date'];
    
}
