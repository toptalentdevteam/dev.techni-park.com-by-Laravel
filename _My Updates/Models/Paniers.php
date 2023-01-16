<?php

namespace Sebastienheyd\Boilerplate\Models;
use Illuminate\Database\Eloquent\Model;

class Paniers extends Model
{
    protected $table = 'PANIERS';
    protected $fillable = ['ID', 'IDCONSO', 'REPMIDI', 'REPSOIR', 'NUITEE', 'created_at', 'updated_at'];
    
    public function getDisplayNameAttribute($value)
    {
        return __($value);
    }

    public function getDescriptionAttribute($value)
    {
        return __($value);
    }

}