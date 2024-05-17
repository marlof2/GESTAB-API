<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TypeOfPerson extends Model
{
    protected $table = 'type_of_person';
    protected $guarded = ['id'];
    protected $fillable = ["name"];
}
