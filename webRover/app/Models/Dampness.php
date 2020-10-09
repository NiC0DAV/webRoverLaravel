<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dampness extends Model
{
    use HasFactory;

    protected $table = 'dampness';

    public function Humidity(){
        return $this->belongsTo('App\Models\Crops', 'id_cultivo');
    }
}
