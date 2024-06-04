<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class EstablishmentServices extends Model
{
    protected $guarded = ['id'];
    protected $fillable = ["establishment_id", "service_id"];
    protected $hidden = ["created_at", "updated_at"];

    public function establishment()
    {
        return $this->belongsTo(Establishment::class)->select("id", "name", "cpf", "cnpj", "phone");
    }

    public function service()
    {
        return $this->belongsTo(Services::class)->select("id", "name", "amount", "time");
    }
}
