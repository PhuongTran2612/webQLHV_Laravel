<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRegister extends Model
{
    use HasFactory;

    protected $fillable =[
        'id_students',
        'id_classrooms',
    ];
}