<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class List extends Model
{
    protected $guarded = ['id'];
    protected $fillable = ["professional_id","client_id","services_id","status_id","establishment","date","time"];
}
