<?php

namespace Sebastienheyd\Boilerplate\Models;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'DOCUMENT';
    /*protected $fillable = ['IDINTERVENTION', 'IDCONTACT', 'DT_INTER_DBT','HR_DEBUT','ID2GENRE_INTER','CDUSER','US_TEAM','IDPROJET', 'LIB50', 'ST_INTER'];

    public function client()
    {  
        return $this->belongsTo(Client::class,'IDCONTACT','IDCONTACT');
    }

    public function proyect()
    {  
        return $this->belongsTo(Proyect::class,'IDPROJET','IDPROJET');
    }
    */
    public function getDisplayNameAttribute($value)
    {
        return __($value);
    }

    public function getDescriptionAttribute($value)
    {
        return __($value);
    }

}