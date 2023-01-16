<?php

namespace Sebastienheyd\Boilerplate\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Sebastienheyd\Boilerplate\Models\Proyect;

class ProyectsController extends Controller
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
        $proyects = Proyect::join('CONTACT','CONTACT.IDCONTACT','=','PROJET.IDCONTACT')
        ->select('PROJET.IDPROJET', 'PROJET.IDCONTACT', 'PROJET.NOMPROJET', 'PROJET.COMMENTAIRE','CONTACT.RAISON_SOCIALE')
        ->orderBy('PROJET.IDPROJET')->get();
        return view('boilerplate::proyect.list', [ 'proyects' => $proyects ]);
    }

    /**
     * Show the form for creating a new role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('boilerplate::proyect.create', [ 'permissions' => Permission::all() ]);
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input[ 'name' ] = str_slug($input[ 'display_name' ]);
        $request->replace($input);

        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'display_name' => 'required',
            'description' => 'required'
        ]);

        $role = Role::create($input);
        $role->permissions()->sync(array_keys($request->input('permission', [ ])));

        return redirect()->route('roles.edit', $role)->with('growl', [ __('boilerplate::role.successadd'), 'success' ]);
    }

    /**
     * Display the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        return redirect()->route('roles.edit', $id);
    }

    /**
     * Show the form for editing the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permissions = Permission::all();
        return view('boilerplate::roles.edit', compact('role', 'permissions'));
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
            'display_name' => 'required',
            'description' => 'required'
        ]);

        $role = Role::find($id);
        $role->update($request->all());
        $role->permissions()->sync(array_keys($request->input('permission')));

        return redirect()->route('roles.edit', $role)->with('growl', [ __('boilerplate::role.successmod'), 'success' ]);
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Role::destroy($id);
    }
}
