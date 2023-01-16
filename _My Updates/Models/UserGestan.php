<?php

namespace Sebastienheyd\Boilerplate\Models;
use Sebastienheyd\Boilerplate\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserGestan extends Model
{
    protected $table = 'USER';
    protected $fillable = ['CDUSER', 'NOMFAMILLE', 'PRENOM'];

    public function getDisplayNameAttribute($value)
    {
        return __($value);
    }

    public function getDescriptionAttribute($value)
    {
        return __($value);
    }

}