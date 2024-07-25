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
    protected $fillable = ["name", "type_of_person_id", "cpf", "cnpj", "phone", "responsible", "type_schedule"];
    protected $hidden = ["created_at", "updated_at"];


    public  function scopeFiltro($query, $filtro, $page)
    {
        return $query
            ->withTrashed()
            ->with("tipoPessoa")
            ->OrWhere('name', 'LIKE', '%' . $filtro . '%')
            ->OrWhere('cnpj', 'LIKE', '%' . $filtro . '%')
            ->OrWhere('cpf', 'LIKE', '%' . $filtro . '%')
            ->OrWhere('phone', 'LIKE', '%' . $filtro . '%')
            // ->OrWhereRelation('tipoPessoa', 'name', 'LIKE', '%' . $filtro . '%')
            ->paginate($page);
    }

    public function tipoPessoa()
    {
        return $this->hasOne(TypeOfPerson::class, 'id', 'type_of_person_id')->select("id", "name");
    }


    // public function checkExistenceUnityContract(array $data)
    // {

    //     $query = $this->unityContract;

    //     $query =  $query->whereColumn(
    //         [
    //             ['contract_id',     (int)$data['contract_id']],
    //             ['unity_id',        (int)$data['unity_id']],
    //             ['profession_id',   (int)$data['profession_id']],
    //             ['occupation_id',   (int)$data['occupation_id']],
    //             ['dayweek_id',      (int)$data['dayWeek_id']],
    //             ['shift_id',        (int)$data['shift_id']],
    //         ]
    //     )->count();

    //     return $query;
    // }
}
