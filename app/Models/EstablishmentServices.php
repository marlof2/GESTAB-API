<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class EstablishmentServices extends Model
{
    protected $guarded = ['id'];
    protected $fillable = ["establishment_id","service_id"];
}
