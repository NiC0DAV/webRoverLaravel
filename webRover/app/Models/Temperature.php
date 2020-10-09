<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temperature extends Model
{
    use HasFactory;

    protected $table = 'temperatures';

    public function Temperature(){
        return $this->belongsTo('App\Models\Crops', 'id_cultivo');
    }
}
