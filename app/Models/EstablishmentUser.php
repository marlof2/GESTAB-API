<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstablishmentUser extends Model
{
    protected $table = "establishment_user";
    protected $guarded = ["id"];
    protected $fillable = ["user_id","establishment_id"];

    public function establishment()
    {
        return $this->hasMany(Establishment::class, "id", "user_id");
    }

    public function establishment_user()
    {
        return $this->hasOne(Establishment::class, "id", "establishment_id")->select("id", "name", "cnpj", "cpf", "phone");
    }

    public function scopeFiltro($query, $filtro) {
        return $query
        ->with("establishment_user")
        ->where('name', 'ILIKE', '%' . $filtro . '%')
        ->OrWhere('cpf', 'ILIKE', '%' . $filtro . '%')
        ->OrWhere('cnpj', 'ILIKE', '%' . $filtro . '%')
        ->OrWhere('phone', 'ILIKE', '%' . $filtro . '%')
        ->paginate(config('app.pageLimit'));

    }
}
