<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Establishment extends Model
{
    use SoftDeletes;

    protected $table = "establishment";
    protected $guarded = ['id'];
    protected $fillable = ["name", "type_of_person_id", "cpf", "cnpj", "is_active"];

    public  function scopeFiltro($query, $filtro)
    {
        return $query
            ->with("tipoPessoa")
            ->OrWhere('name', 'LIKE', '%' . $filtro . '%')
            ->OrWhere('cnpj', 'LIKE', '%' . $filtro . '%')
            ->OrWhere('cpf', 'LIKE', '%' . $filtro . '%')
            ->OrWhere('phone', 'LIKE', '%' . $filtro . '%')
            // ->OrWhereRelation('tipoPessoa', 'name', 'LIKE', '%' . $filtro . '%')
            ->paginate(config('app.pageLimit'));
    }

    public function tipoPessoa()
    {
        return $this->hasOne(TypeOfPerson::class, 'id', 'type_of_person_id')->select("id", "name");
    }
}
