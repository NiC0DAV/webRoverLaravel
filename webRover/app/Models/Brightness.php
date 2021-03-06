<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brightness extends Model
{
    use HasFactory;

    protected $table = 'brightness';

    public function Brightness(){
        return $this->belongsTo('App\Models\Crops', 'id_cultivo');
    }
}
