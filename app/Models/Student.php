<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable =[
        'name',
        'birthday',
        'home_town',
        'address',
        'level_id',
        'phone',
        'id_users',
    ];
}
