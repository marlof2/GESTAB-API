<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Establishment extends Model
{
    protected $guarded = ['id'];
    protected $fillable = ["name","type_of_person_id","cpf","cnpj","ativo"];
}
