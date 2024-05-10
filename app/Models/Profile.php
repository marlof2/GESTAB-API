<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Profile extends Model
{
    protected $guarded = ['id'];
    protected $fillable = ["name","description"];

    public function user()
    {
        return $this->belongsTo(User::class, 'profile_id', 'id');
    }

    public function abilities()
    {
        return $this->belongsToMany(Ability::class, 'profile_abilities', 'profile_id', 'ability_id');
    }
}
