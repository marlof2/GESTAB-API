<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Lista extends Model
{
    protected $table = 'list';
    protected $guarded = ['id'];
    protected $fillable = ["user_id","service_id","status_id","establishment_id","date","time", "valid"];
}
