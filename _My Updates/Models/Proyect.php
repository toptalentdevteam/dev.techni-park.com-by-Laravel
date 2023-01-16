<?php

namespace Sebastienheyd\Boilerplate\Models;
use Sebastienheyd\Boilerplate\Models\Client;
use Illuminate\Database\Eloquent\Model;

class Proyect extends Model
{
    protected $table = 'PROJET';
    protected $fillable = ['IDPROJET', 'IDCONTACT', 'NOMPROJET', 'COMMENTAIRE'];

    public function client()
    {  
        return $this->belongsTo(Client::class,'IDCONTACT','IDCONTACT');
    }

    public function getDisplayNameAttribute($value)
    {
        return __($value);
    }

    public function getDescriptionAttribute($value)
    {
        return __($value);
    }

}