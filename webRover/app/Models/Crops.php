<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crops extends Model
{
    use HasFactory;

    protected $table = 'crops';

    public function cropsTemperature(){
        $this->hasMany('App/Temperature');
    }

    public function cropsHumidity(){
        $this->hasMany('App/Dampness');
    }

    public function cropsBrightness(){
        $this->hasMany('App/Brightness');
    }

    public function Crops(){
        return $this->belongsTo('App\Models\Greenhouse', 'id_invernadero');
    }
}