<?php

namespace Sebastienheyd\Boilerplate\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Sebastienheyd\Boilerplate\Models\Parametres;
use Sebastienheyd\Boilerplate\Models\Proyect;
use Sebastienheyd\Boilerplate\Models\Task;
use Sebastienheyd\Boilerplate\Models\User;
use Illuminate\Support\Facades\Storage;
use DB;

class ParameterController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('ability:admin,users_crud');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parametres = Parametres::leftjoin('PROJET', function($join) {
            $join->on('parametres.identifiant', '=', 'PROJET.IDPROJET')
                 ->where('parametres.module', 'LIKE', 'PROJET');
            })->leftjoin('INTERVENTION', function($join) {
                $join->on('parametres.identifiant', '=', 'INTERVENTION.IDINTERVENTION')
                     ->where('parametres.module', 'LIKE', 'INTERVENTION');
            })->leftjoin('users', function($join) {
                $join->on('parametres.identifiant', '=', 'users.id')
                     ->where('parametres.module', 'LIKE', 'ABSENCES');
            })
            ->select('parametres.id','parametres.actif','parametres.module',
                    'parametres.description','parametres.identifiant',
                    'PROJET.NOMPROJET','INTERVENTION.LIB50','users.first_name','users.last_name')
            ->get();
        return view('boilerplate::parameter.list', [ 'parametres' => $parametres ]);
    }

    /**
     * Show the form for creating a new role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $proyects = array('0'=> __('boilerplate::parameters.selectprojet')) + Proyect::select('IDPROJET',DB::Raw('CONCAT(IDPROJET," - ",NOMPROJET) as NAME'))->orderBy('IDPROJET')->pluck('NAME', 'IDPROJET')->ToArray();
        $interventions = array('0'=> __('boilerplate::parameters.selectintervention')) + Task::select('IDINTERVENTION',DB::Raw('CONCAT(IDINTERVENTION," - ",LIB50) as NAME'))->orderBy('IDINTERVENTION')->pluck('NAME', 'IDINTERVENTION')->ToArray();
        $users = array('0'=> __('boilerplate::parameters.selectuser')) + User::select('id',DB::Raw('CONCAT(id," - ",first_name," ",last_name) as TEXT'))->orderBy('id')->pluck('TEXT', 'id')->ToArray();
        return view('boilerplate::parameter.create', [ 'proyects' => $proyects, 'interventions' => $interventions, 'users' => $users ]);
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'module' => 'required',
            'description' => 'required',
        ]);

        $input = $request->all();

        switch ($input['module']) {
            case 'PROJET':
                $id = $input['proyect'];
                break;
            case 'INTERVENTION':
                $id = $input['intervention'];
                break;
            case 'ABSENCES':
                $id = $input['user'];
                break;
            default:
                $id = 0;
                break;
        }

       $parametres = new Parametres();
       $parametres->module = $input['module'];
       $parametres->description = $input['description'];
       $parametres->identifiant = $id;
       $parametres->url_form = $input['form'];
       $parametres->url_drive = $input['drive'];
       $parametres->actif = $input['actif'];
       $parametres->save();

       return redirect('/parameters')->with('growl', [ __('boilerplate::parameters.successadd'), 'success' ]);

    }

    /**
     * Display the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $parameter = Parametres::find($id);
        $proyects = array('0'=> __('boilerplate::parameters.selectprojet')) + Proyect::select('IDPROJET',DB::Raw('CONCAT(IDPROJET," - ",NOMPROJET) as NAME'))->orderBy('IDPROJET')->pluck('NAME', 'IDPROJET')->ToArray();
        $interventions = array('0'=> __('boilerplate::parameters.selectintervention')) + Task::select('IDINTERVENTION',DB::Raw('CONCAT(IDINTERVENTION," - ",LIB50) as NAME'))->orderBy('IDINTERVENTION')->pluck('NAME', 'IDINTERVENTION')->ToArray();
        $users = array('0'=> __('boilerplate::parameters.selectuser')) + User::select('id',DB::Raw('CONCAT(id," - ",first_name," ",last_name) as TEXT'))->orderBy('id')->pluck('TEXT', 'id')->ToArray();
    
        return view('boilerplate::parameter.edit', compact('parameter','proyects','interventions','users'));
    }

    /**
     * Update the specified role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'description' => 'required',
        ]);

        $input = $request->all();

        $parametres = Parametres::find($id);

        switch ($parametres->module) {
            case 'PROJET':
                $id = $input['proyect'];
                break;
            case 'INTERVENTION':
                $id = $input['intervention'];
                break;
            case 'ABSENCES':
                $id = $input['user'];
                break;
            default:
                $id = 0;
                break;
        }

       $parametres->description = $input['description'];
       $parametres->identifiant = $id;
       $parametres->url_form = $input['form'];
       $parametres->url_drive = $input['drive'];
       $parametres->actif = $input['actif'];
       $parametres->save();

       return redirect('/parameters')->with('growl', [ __('boilerplate::parameters.successmod'), 'success' ]);

    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Parametres::destroy($id);
        return redirect('/parameters')->with('growl', [ __('boilerplate::parameters.successdel'), 'success' ]);
    
    }
}
