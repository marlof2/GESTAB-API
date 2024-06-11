<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Services extends Model
{
    protected $guarded = ['id'];
    protected $fillable = ["name", "amount", "time", "establishment_id"];
    protected $hidden = ["created_at", "updated_at"];

    public function scopeFiltro($query, $filtro)
    {
        return $query
            ->where('name', 'LIKE', '%' . $filtro . '%')
            ->paginate(config('app.pageLimit'));
    }

    public function establishment(){
        return $this->hasOne(Establishment::class, 'id', 'establishment_id');
    }
}
