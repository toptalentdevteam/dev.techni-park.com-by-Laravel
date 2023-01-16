<?php 
	namespace Sebastienheyd\Boilerplate\Models;
	use Illuminate\Database\Eloquent\Model;

	class Intervention extends Model
	{
		protected $table = 'INTERVENTION';


		public function getDisplayNameAttribute($value)
	    {
	        return __($value);
	    }

	    public function getDescriptionAttribute($value)
	    {
	        return __($value);
	    }
	}

 ?>