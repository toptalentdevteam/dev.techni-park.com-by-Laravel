<?php

namespace Sebastienheyd\Boilerplate\Controllers\Users;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Sebastienheyd\Boilerplate\Models\Role;
use Sebastienheyd\Boilerplate\Models\User;
use Sebastienheyd\Boilerplate\Models\UserGestan;
use Sebastienheyd\Boilerplate\Models\Parametres;
use Sebastienheyd\Boilerplate\Models\Absence;
use Sebastienheyd\Boilerplate\Models\Client;
use Sebastienheyd\Boilerplate\Models\Document;
use Image;
use Auth;
use URL;
use DB;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Users Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the users management.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('ability:admin,users_crud', [
            'except' => [
                'firstLogin',
                'firstLoginPost',
                'avatar',
                'avatarDelete',
                'avatarPost',
                'profile',
                'profilePost'
            ]
        ]);
    }

    /**
     * Display a listing of users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('boilerplate::users.list');
    }

    /**
     * To display dynamic table by datatable
     *
     * @return mixed
     */
    public function datatable()
    {
        return Datatables::of(User::select('*'))
            ->rawColumns(['actions', 'status'])
            ->editColumn('created_at', function ($user) {
            return $user->created_at->format(__('boilerplate::date.YmdHis'));
        })->editColumn('last_login', function ($user) {
            return $user->getLastLogin(__('boilerplate::date.YmdHis'), '-');
        })->editColumn('status', function ($user) {
            if($user->active == 1) {
                return '<span class="label label-success">'.__('boilerplate::users.active').'</span>';
            }
            return '<span class="label label-danger">'.__('boilerplate::users.inactive').'</span>';
        })->editColumn('roles', function ($user) {
            return $user->getRolesList();
        })->editColumn('actions', function ($user) {
            $currentUser = Auth::user();

            // Admin can edit and delete anyone...
            if ($currentUser->hasRole('admin')) {
                $b = '<a href="' . URL::route('users.edit', $user->id) . '" class="btn btn-primary btn-sm mrs"><i class="fa fa-pencil"></i></a>';

                // ...except delete himself
                if ($user->id !== $currentUser->id) {
                    $b .= '<a href="' . URL::route('users.destroy', $user->id) . '" class="btn btn-danger btn-sm destroy"><i class="fa fa-trash"></i></a>';
                }
                return $b;
            }

            // The user is the current user, you can't delete yourself
            if ($user->id === $currentUser->id) {
                return '<a href="' . URL::route('users.edit', $user) . '" class="btn btn-primary btn-sm mrs"><i class="fa fa-pencil"></i></a>';
            }

            $b = '<a href="' . URL::route('users.edit', $user->id) . '" class="btn btn-primary btn-sm mrs"><i class="fa fa-pencil"></i></a>';

            // Current user is not admin, only admin can delete another admin
            if(!$user->hasRole('admin')) {
                $b .= '<a href="' . URL::route('users.destroy', $user->id) . '" class="btn btn-danger btn-sm destroy"><i class="fa fa-trash"></i></a>';
            }

            return $b;

        })->make(true);
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users_gestan = array(''=> __('boilerplate::users.selectuser')) + UserGestan::where('USER.IARCHIVE','<>',1)->select(DB::Raw('CONCAT(NOMFAMILLE," ",PRENOM) AS full_name'),'CDUSER')->pluck('full_name', 'CDUSER')->ToArray();

        // Filter roles if not admin
        if (!Auth::user()->hasRole('admin')) {
            $roles = Role::whereNotIn('name', [ 'admin' ])->get();
        } else {
            $roles = Role::all();
        }
        return view('boilerplate::users.create', [ 'roles' => $roles, 'users_gestan' => $users_gestan ]);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'last_name' => 'required',
            'first_name' => 'required',
            'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'roles' => 'required|min:1',
            'roles.*' => 'required|min:1',
            'password_confirmation' => 'same:password'
        ]);

        $input = $request->all();
        $password = trim($input[ 'password' ]);
        if(empty($password))
            $input[ 'password' ] = bcrypt(str_random(8));
        else
            $input[ 'password' ] = bcrypt($input[ 'password' ]);    

        $input[ 'remember_token' ] = str_random(32);
        $input[ 'deleted_at' ] = null;

        $user = User::withTrashed()->updateOrCreate([ 'email' => $input[ 'email' ] ], $input);
        $user->restore();
        $user->roles()->sync(array_keys($request->input('roles')));

        if(empty($password))
            $user->sendNewUserNotification($input[ 'remember_token' ], Auth::user());
        else
            $user->sendNewUserPasswordNotification($input[ 'remember_token' ], $password, Auth::user());


        return redirect()->route('users.edit', $user)->with('growl', [ __('boilerplate::users.successadd'), 'success' ]);
    }

    /**
     * Display the specified user.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort('404');
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        $users_gestan = array(''=> __('boilerplate::users.selectuser')) + UserGestan::where('USER.IARCHIVE','<>',1)->select(DB::Raw('CONCAT(NOMFAMILLE," ",PRENOM) AS full_name'),'CDUSER')->pluck('full_name', 'CDUSER')->ToArray();

        if (!Auth::user()->hasRole('admin')) {
            $roles = Role::whereNotIn('name', [ 'admin' ])->get();
        } else {
            $roles = Role::all();
        }

        return view('boilerplate::users.edit', compact('user', 'roles','users_gestan'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'last_name' => 'required',
            'first_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'roles' => 'required|min:1',
            'roles.*' => 'required|min:1'
        ]);

        $user = User::findOrFail($id);

        $user->update($request->all());
        
        // Mise à jour des rôles
        $user->roles()->sync(array_keys($request->input('roles', [ ])));

        return redirect(route('users.edit', $user))->with('growl', [ __('boilerplate::users.successmod'), 'success' ]);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
    }

    /**
     * Show the form to set a new password on the first login
     *
     * @param $token
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function firstLogin($token, Request $request)
    {
        $user = User::where([ 'remember_token' => $token ])->first();
        if (is_null($user)) abort(404);
        return view('boilerplate::auth.firstlogin', compact('user', 'token'));
    }

    /**
     * Store a newly created password in storage after the first login.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function firstLoginPost(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password'
        ]);

        $user = User::where([ 'remember_token' => $request->input('token') ])->first();

        $user->password = bcrypt($request->input('password'));
        $user->remember_token = str_random(32);
        $user->last_login = Carbon::now()->toDateTimeString();
        $user->save();

        Auth::attempt([ 'email' => $user->email, 'password' => $request->input('password'), 'active' => 1 ]);

        return redirect()->route('boilerplate.home')->with('growl', [ __('boilerplate::users.newpassword'), 'success' ]);
    }

    public function profile()
    {

        $documents = array();

        $client = Client::where('LOGIN',Auth::user()->CDUSER)->first();
        if($client)
        {
            $paramlib = Document::join("PARAMLIB",function($join){
                $join->on("PARAMLIB.IDSECONDAIRE","=","DOCUMENT.ID2GENRE_DOCUMENT")
                        ->where("TYP_LIBSIMPLE","=",9);
                })
                ->where('DOCUMENT.TRGCIBLE','CTC'.$client->IDCONTACT)
                ->select('PARAMLIB.LIB_50','DOCUMENT.LIB100','DOCUMENT.FILEREF')
                ->get();

            foreach ($paramlib as $key => $value) {
                $path = base_path().'/storage/app/public/gestan/DOCUMENTS/CONTACT_'.$client->IDCONTACT.'/'.$value->FILEREF;
                if(is_file($path) && is_readable($path)) 
                {  
                    $aux = $this->fileInfo(pathinfo($path)); 
                    $aux["dirname"] = url('/').Storage::url('gestan/DOCUMENTS/CONTACT_'.$client->IDCONTACT.'/'.$value->FILEREF);
                    $aux["name"] = $value->LIB100;
                    $documents[$value->LIB_50][] = $aux;
                }
            }
        }

        $formulaire = Parametres::where('module','ABSENCES')
                                ->where('actif',1)
                                ->where(function($query){
                                    $query->orwhere('identifiant',0);
                                    $query->orWhere('identifiant',Auth::user()->id);
                                })->first();
        if($formulaire)
                $formulaire = $formulaire->url_form;
        
        $absences = Absence::leftJoin("PARAMLIB",function($join){
                                $join->on("PARAMLIB.IDSECONDAIRE","=","ABSENCE.ID2_MOTIFABSENCE")
                                     ->where("TYP_LIBSIMPLE","=",71);
                             })
                            ->where('CDUSER',Auth::user()->CDUSER)
                            ->select('IVALIDE',
                                    'DT_DEBUT',
                                    'HR_DEBUT',
                                    'DT_FIN',
                                    'HR_FIN',
                                    'LIBABSENCE',
                                    'NB_JOURS',
                                    'IDABSENCE',
                                    'ID2_MOTIFABSENCE',
                                    'PARAMLIB.LIB_50')
                            ->get();

        return view('boilerplate::users.profile', [ 'user' => Auth::user(), 'documents' => $documents, 'formulaire' => $formulaire, 'absences' => $absences ]);
    }

    public function dirToArray($dir) { 
   
        $result = array(); 
     
        $cdir = scandir($dir); 
        foreach ($cdir as $key => $value) 
        { 
           if (!in_array($value,array(".",".."))) 
           { 
              if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
              { 
                 $result[$value] = $this->dirToArray($dir . DIRECTORY_SEPARATOR . $value); 
              } 
              else 
              { 
                 $result[] = $this->fileInfo(pathinfo($dir . '/'. $value)); 
              } 
           } 
        } 
        
        return $result; 
    } 

    public function fileInfo($filePath)
    {
        $file = array();
        $file['name'] = $filePath['filename'];
        $file['extension'] = strtoupper($filePath['extension']);
        //$file['dirname'] = str_replace(base_path(), '', $filePath['dirname'].'/'.$filePath['basename']);
        $file['dirname'] = str_replace(public_path(), '', $filePath['dirname'].'/'.$filePath['basename']);
        $file['size'] = number_format(filesize($filePath['dirname'] . '/' . $filePath['basename']) / 1024, 2) . ' KB';
        $file['date'] = date("d/m/Y H:i:s", filemtime($filePath['dirname'] . '/' . $filePath['basename']));
        
        return $file;
    }

    public function profilePost(Request $request)
    {
        $this->validate($request, [
            'avatar' => 'mimes:jpeg,png|max:10000',
            'last_name' => 'required',
            'first_name' => 'required',
            'password_confirmation' => 'same:password'
        ]);

        $avatar = $request->file('avatar');
        $user = Auth::user();

        if ($avatar && $file = $avatar->isValid()) {
            $destinationPath = dirname($user->avatar_path);
            if (!is_dir($destinationPath)) mkdir($destinationPath, 0766, true);
            $extension = $avatar->getClientOriginalExtension();
            $fileName = md5($user->id.$user->email).'_tmp.'.$extension;
            $avatar->move($destinationPath, $fileName);

            Image::make($destinationPath.DIRECTORY_SEPARATOR.$fileName)
                ->fit(100, 100)
                ->save($user->avatar_path);

            unlink($destinationPath.DIRECTORY_SEPARATOR.$fileName);
        }

        $input = $request->all();

        if ($input[ 'password' ] !== null) {
            $input[ 'password' ] = bcrypt($input[ 'password' ]);
            $input[ 'remember_token' ] = str_random(32);
        } else {
            unset($input[ 'password' ]);
        }

        $user->update($input);

        return redirect()->route('user.profile')->with('growl', [ __('boilerplate::users.profile.successupdate'), 'success' ]);
    }

    public function avatarDelete()
    {
        $user = Auth::user();
        if (is_file($user->avatar_path)) {
            unlink($user->avatar_path);
        }
    }

    /**
     * Delate ABSENCE
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAbsence($id)
    {
       Absence::where('IDABSENCE',$id)
                ->delete(); 
    
    }
}
