<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Feedbacks extends Model
{
    protected $guarded = ['id'];
    protected $fillable = ["category_id","title","description","user_id"];

}
