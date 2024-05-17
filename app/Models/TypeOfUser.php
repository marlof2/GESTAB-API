<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TypeOfUser extends Model
{
    protected $table = 'type_of_user';
    protected $guarded = ['id'];
    protected $fillable = ["id", "name"];
}
