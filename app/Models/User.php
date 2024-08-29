<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'profile_id',
        'phone',
        "type_schedule"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public  function scopeFiltro($query, $filtro)
    {
        return $query
            ->with("profile")
            ->OrWhere('name', 'LIKE', '%' . $filtro . '%')
            ->OrWhere('cpf', 'LIKE', '%' . $filtro . '%')
            ->OrWhere('email', 'LIKE', '%' . $filtro . '%')
            ->OrWhere('phone', 'LIKE', '%' . $filtro . '%')
            ->paginate(config('app.pageLimit'));
    }


    public function profile()
    {
        return $this->hasOne(Profile::class, 'id', 'profile_id')->select("id", "name", "description");
    }

    public function establishments()
    {
        return $this->belongsToMany(Establishment::class, 'establishment_user', 'user_id', 'establishment_id')->as("establishments")->select("establishment_id", "name", "cpf", "cnpj", "phone");
    }
}
