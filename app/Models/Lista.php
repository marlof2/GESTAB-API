<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lista extends Model
{
    protected $table = 'list';
    protected $guarded = ['id'];
    protected $fillable = ["user_id","service_id","status_id","establishment_id","date","time", "valid","professional_id"];


    public function user()
    {
        return $this->hasOne(User::class, 'id','user_id');
    }
    public function professional()
    {
        return $this->hasOne(User::class, 'id','professional_id');
    }
    public function service()
    {
        return $this->hasOne(Services::class, 'id','service_id');
    }
    public function status()
    {
        return $this->hasOne(Status::class, 'id','status_id');
    }
}
