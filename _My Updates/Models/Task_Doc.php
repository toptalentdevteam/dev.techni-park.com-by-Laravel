<?php

namespace Sebastienheyd\Boilerplate\Models;
use Illuminate\Database\Eloquent\Model;

class Task_Doc extends Model
{
    protected $table = 'intervention_documents';
  
    public function getDisplayNameAttribute($value)
    {
        return __($value);
    }

    public function getDescriptionAttribute($value)
    {
        return __($value);
    }

}