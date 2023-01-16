<?php

namespace Sebastienheyd\Boilerplate\Controllers;

use App\Http\Controllers\Controller;
use Sebastienheyd\Boilerplate\Models\Task;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = \Auth::user();
        
        $fechaFrom= (isset($_GET['start']))?$_GET['start']:'2000-01-01';
        $fechaTo= (isset($_GET['end']))?$_GET['end']:'2099-12-31';
        $tasks = Task::leftJoin('intervention_state','intervention_state.id','=','INTERVENTION.ST_INTER')
                ->leftJoin('PROJET','PROJET.IDPROJET','=','INTERVENTION.IDPROJET')
                ->leftJoin('users','users.CDUSER','=','INTERVENTION.CDUSER')
                ->whereIn('INTERVENTION.ST_INTER',[1,2,3,6,8])
                ->where('INTERVENTION.DT_INTER_DBT', '>=', $fechaFrom)
                ->where('INTERVENTION.DT_INTER_DBT', '<=', $fechaTo);
        
        if(!$user->hasRole('admin'))
            $tasks = $tasks->where(function ($query) use ($user) {
                                    $query->orWhere('INTERVENTION.CDUSER','=',$user->CDUSER)
                                    ->orWhereRaw('FIND_IN_SET("'.$user->CDUSER.'",REPLACE(REPLACE(INTERVENTION.US_TEAM,";",",")," ",""))');
                            });

               
        $tasks = $tasks->select('INTERVENTION.IDINTERVENTION','INTERVENTION.DT_INTER_DBT','PROJET.IDPROJET','PROJET.NOMPROJET','LIB50', 
                         'intervention_state.name as STATE','intervention_state.id as STATEID')
                         ->orderby('INTERVENTION.DT_INTER_DBT', 'DESC')
                ->Paginate(15);
        
        $totales = Task::where('INTERVENTION.DT_INTER_DBT', '>=', $fechaFrom)
                ->where('INTERVENTION.DT_INTER_DBT', '<=', $fechaTo);

        if(!$user->hasRole('admin'))
            $totales = $totales->where(function ($query) use ($user) {
                                    $query->orWhere('INTERVENTION.CDUSER','=',$user->CDUSER)
                                    ->orWhereRaw('FIND_IN_SET("'.$user->CDUSER.'",REPLACE(REPLACE(INTERVENTION.US_TEAM,";",",")," ",""))');
                            });
                
        $totales = $totales->select('INTERVENTION.ST_INTER', DB::Raw('COUNT(*) as TOTAL'))
                ->groupby('INTERVENTION.ST_INTER')
                ->get();

        $total=0;
        $afaire=0;
        $encurso=0;
        $enattente=0;
        foreach ($totales as $key => $value) {
            $total+=$value->TOTAL;
            switch ($value->ST_INTER) {
                case 1:
                    $afaire+=$value->TOTAL;
                    break;
                case 2:
                    $encurso+=$value->TOTAL;
                    break;
                default:
                    $enattente+=$value->TOTAL;
                    break;
            }
            
        }
        $months = array(1=>'janvier',2=>'févier',3=>'mars',4=>'avril',5=>'mai',6=>'juin',7=>'julliet',8=>'août',9=>'septembre',10=>'octobre', 11=>'novembre',12=>'décembre');
 
        //$fechaFrom =  $months[date("n",strtotime($fechaFrom))]." ".date("d, Y",strtotime($fechaFrom));
        //$fechaTo = $months[date("n",strtotime($fechaTo))]." ".date("d, Y",strtotime($fechaTo));

        $fechaFrom =  date("d/m/Y",strtotime($fechaFrom));
        $fechaTo = date("d/m/Y",strtotime($fechaTo));

        return view('boilerplate::home', compact('tasks','total','afaire','encurso','enattente','fechaFrom','fechaTo'));
 
    }
}
