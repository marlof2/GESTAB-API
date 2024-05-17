<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstablishmentUser extends Model
{
    protected $table = "establishment_user";
    protected $guarded = ['id'];
    protected $fillable = ["user_id","establishment_id"];
}
