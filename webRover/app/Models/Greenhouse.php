<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Greenhouse extends Model
{
    use HasFactory;

    protected $table = 'greenhouses';

    public function crops(){
        $this->hasMany('App/Crops');
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'id_user');
    }
}
