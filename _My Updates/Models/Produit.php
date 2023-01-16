<?php 
	namespace Sebastienheyd\Boilerplate\Models;
	use Illuminate\Database\Eloquent\Model;

	class Produit extends Model
	{
		protected $table = 'PRODUIT';

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