<?php

namespace App;

use App\Bases\BaseModel;

class Member extends BaseModel
{
    protected $table = 'members'; // Table name

    /*
     * Defining Fillable Attributes On A Model
     */
    protected $fillable = [
        'name',
        'dob',
        'address',
        'telephone',
        'identity',
        'join_date',
        'is_active'
    ];

    protected $dates = ['dob', 'join_date'];

}
