<?php

namespace Sebastienheyd\Boilerplate\Models;
use Sebastienheyd\Boilerplate\Models\User;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'CONTACT';
    protected $fillable = ['IDCONTACT', 'RAISON_SOCIALE', 'LOGIN'];

    public function getDisplayNameAttribute($value)
    {
        return __($value);
    }

    public function getDescriptionAttribute($value)
    {
        return __($value);
    }

}