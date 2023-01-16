<?php

namespace Sebastienheyd\Boilerplate\Models;
use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    protected $table = 'SIGNATURE';
    protected $fillable = ['IDSIGNATURE', 'TRGCIBLE', 'CONTENUFIC'];

    public function getDisplayNameAttribute($value)
    {
        return __($value);
    }

    public function getDescriptionAttribute($value)
    {
        return __($value);
    }

}