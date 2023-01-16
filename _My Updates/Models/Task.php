<?php

namespace Sebastienheyd\Boilerplate\Models;
use Sebastienheyd\Boilerplate\Models\Client;
use Sebastienheyd\Boilerplate\Models\Proyect;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'INTERVENTION';
   // protected $fillable = ['ID', 'IDCONTACT', 'DT_INTER_DBT','HR_DEBUT','ID2GENRE_INTER','CDUSER','US_TEAM','IDPROJET', 'LIB50', 'ST_INTER'];

    public function client()
    {  
        return $this->belongsTo(Client::class,'IDCONTACT','IDCONTACT');
    }

    public function proyect()
    {  
        return $this->belongsTo(Proyect::class,'IDPROJET','IDPROJET');
    }

    public function getDisplayNameAttribute($value)
    {
        return __($value);
    }

    public function getDescriptionAttribute($value)
    {
        return __($value);
    }

    public static function converToPlain($text)
    {
        $plain = $text;

        // we have to remove all line breaks, otherwise
        // the RTF>TXT regexp below doesn't work correctly.
        //$plain = preg_replace( '/\r|\n/', '', $plain);
        
        // extract the images
        // example: {\pict\pngblip\picw1166\pich190\picwgoal8071\pichgoal1315 89504e470d0a1a0a00...454e44ae426082}
        // the hexadecimal code for the image starts after the
        // whitespace and runs until the first } that we encounter.
        // then it has to be converted into base64.
        $imgHtml = '';
        $imgMatches = array();
        $imgRegex = '/{\\\\pict\\\\pngblip\\\\[a-z0-9]+\\\\[a-z0-9]+\\\\[a-z0-9]+\\\\[a-z0-9]+ ([a-z0-9]+)}/';
        preg_match_all($imgRegex, $plain, $imgMatches);
        if (count($imgMatches[1])) {
            for ($i=0; $i < count($imgMatches[1]); $i++) {
                $imgHtml .= '<img src="data:image/png;base64, ' . base64_encode(pack('H*', $imgMatches[1][$i])) . '">';
            }
        }
        
        // remove those images (or else their hex code is still displayed as text)
        $plain = preg_replace($imgRegex, '', $plain);
        
        // RTF>TXT (https://stackoverflow.com/a/42525858/357546)
        $plain = preg_replace('"{\*?\\\\.+(;})|\\s?\\\[A-Za-z0-9]+|\\s?{\\s?\\\[A-Za-z0-9‹]+\\s?|\\s?}\\s?"', '', $plain);
        
        // special characters; for a full list, see:
        // https://www.oreilly.com/library/view/rtf-pocket-guide/9781449302047/ch04.html
        $plain = str_replace("\'3f", '?', $plain);
        $plain = str_replace("\'80", '€', $plain);
        $plain = str_replace("\'a8", '¨', $plain);
        $plain = str_replace("\'ab", '«', $plain);
        $plain = str_replace("\'ae", '®', $plain);
        $plain = str_replace("\'b0", '°', $plain);
        $plain = str_replace("\'bb", '»', $plain);
        $plain = str_replace("\'c4", 'Ä', $plain);
        $plain = str_replace("\'c9", 'É', $plain);
        $plain = str_replace("\'d6", 'Ö', $plain);
        $plain = str_replace("\'dc", 'Ü', $plain);
        $plain = str_replace("\'df", 'ß', $plain);
        $plain = str_replace("\'e0", 'à', $plain);
        $plain = str_replace("\'e2", 'â', $plain);
        $plain = str_replace("\'e4", 'ä', $plain);
        $plain = str_replace("\'e7", 'ç', $plain);
        $plain = str_replace("\'e8", 'è', $plain);
        $plain = str_replace("\'e9", 'é', $plain);
        $plain = str_replace("\'ea", 'ê', $plain);
        $plain = str_replace("\'eb", 'ë', $plain);
        $plain = str_replace("\'ee", 'î', $plain);
        $plain = str_replace("\'f4", 'ô', $plain);
        $plain = str_replace("\'f6", 'ö', $plain);
        $plain = str_replace("\'f8", 'ø', $plain);
        $plain = str_replace("\'fb", 'û', $plain);
        $plain = str_replace("\'fc", 'ü', $plain);
        
        // a bit of cleaning
        $plain = trim($plain);
        $plain = preg_replace('/^-0 /', '', $plain);
        $plain = str_replace('{\* Riched20 10.0.17134', '', $plain);
        $plain = str_replace('{\* Riched20 10.0.18362', '', $plain);
        $plain .= $imgHtml;

        return $plain;
    }

}