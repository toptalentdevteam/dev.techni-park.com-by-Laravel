<?php

namespace Sebastienheyd\Boilerplate\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Sebastienheyd\Boilerplate\Models\Client;
use Illuminate\Support\Facades\Storage;

class ClientsController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('ability:admin,users_crud', [
            'except' => [
                'edit'
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('boilerplate::client.list', [ 'clients' => Client::all() ]);
    }

    /**
     * Show the form for creating a new role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('boilerplate::client.create', [ 'permissions' => Permission::all() ]);
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
        $documents = array();
      
        $client = Client::where('IDCONTACT',$id)->first();
        $clienteDocument = Client::where('NOMFAMILLE','LIKE','%DOCUMENTS%')->where('IDSOCIETE',$client->IDSOCIETE)->first();

        if($clienteDocument)
            $directory = 'CONTACT_'.$clienteDocument->IDCONTACT;
        else
            $directory = 'CONTACT_'.$id;

        if(Storage::disk('public')->exists('gestan/DOCUMENTS/'.$directory))
        {
              $documents = $this->dirToArray(public_path().'/storage/gestan/DOCUMENTS/'.$directory);
        }

        return view('boilerplate::client.edit', compact('client','documents'));
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
        $file['extension'] = $filePath['extension'];
        $file['dirname'] = str_replace(public_path(), '', $filePath['dirname'].'/'.$filePath['basename']);
        $file['size'] = number_format(filesize($filePath['dirname'] . '/' . $filePath['basename']) / 1024, 2) . ' KB';
        $file['date'] = date("d/m/Y H:i:s", filemtime($filePath['dirname'] . '/' . $filePath['basename']));
        
        return $file;
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
