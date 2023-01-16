<?php

namespace Sebastienheyd\Boilerplate\Models;
use Illuminate\Database\Eloquent\Model;

class Parametres extends Model
{
    protected $table = 'parametres';
  
    public function getDisplayNameAttribute($value)
    {
        return __($value);
    }

    public function getDescriptionAttribute($value)
    {
        return __($value);
    }
    public function proyect()
    {  
        return $this->belongsTo(Proyect::class,'identifiant','IDPROJET');
    }

}