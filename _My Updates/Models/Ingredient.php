<?php 
	namespace Sebastienheyd\Boilerplate\Models;
	use Illuminate\Database\Eloquent\Model;

	class Ingredient extends Model
	{
		protected $table = 'INGREDIENT';
		protected $fillable = array('IDINGREDIENT','CD_PRODUIT','IDPROJET','IDTACHE','IDINTERVENTION','QT_CONSO','PU_ACHAT_HT','PU_VENTE_HT', 'TYP_FAM','LIB80');
		public $timestamps = false;

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