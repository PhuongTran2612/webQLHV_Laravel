<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable =[
        'name',
        'opening_day',
        'id_levels',
        'id_teachers',
        'total',
        'actual_number'
    ];
}