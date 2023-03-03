<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable =[
        'id',
        'id_classrooms',
        'id_students',
        'status',
        'note'
    ];
}