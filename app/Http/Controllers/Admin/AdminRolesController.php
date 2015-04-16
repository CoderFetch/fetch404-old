<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;

use App\User;
use App\Role;
use App\Permission;

use Datatables;
use Input;
use Flash;
use Redirect;
use Illuminate\Http\Request;
use Validator;
use View;

class AdminRolesController extends AdminController {
    /**
     * User Model
     * @var User
     */
    protected $user;
    
    /**
     * Role Model
     * @var Role
     */
    protected $role;
    
    /**
     * Permission Model
     * @var Permission
     */
    protected $permission;
    
    /**
     * Inject the models.
     * @param User $user
     * @param Role $role
     * @param Permission $permission
     */
    public function __construct(User $user, Role $role, Permission $permission)
    {
        parent::__construct();
        $this->user = $user;
        $this->role = $role;
        $this->permission = $permission;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        // Title
        $title = 'Roles';
        // Grab all the groups
        $roles = $this->role;
        // Show the page
        return view('core.admin.roles.index', compact('roles', 'title'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate(Request $request)
    {
        // Get all the available permissions
        $permissions = $this->permission->all();
        // Selected permissions
        $selectedPermissions = $request->old('permissions', array());
        // Title
        $title = 'Create role';
        // Show the page
        return view('core.admin.roles.create', compact('permissions', 'selectedPermissions', 'title'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(Request $request)
    {
        // Declare the rules for the form validation
        $rules = array(
            'name' => 'required|unique:roles',
            'display_name' => 'required|unique:roles'
        );
        // Validate the inputs
        $validator = Validator::make($request->all(), $rules, [
        	'name.required' => 'A name is required.',
        	'name.unique' => 'Please use a different name.',
        	'display_name.required' => 'A display name is required',
        	'display_name.unique' => 'Please use a different display name.'
        ]);
        // Check if the form validates with success
        if ($validator->passes())
        {
  	    // Get the inputs, with some exceptions
            $inputs = $request->except('csrf_token');
            $this->role->name = $request->input('name');
            $this->role->display_name = $request->input('display_name');
            $this->role->is_protected = $request->has('is_protected') ? 1 : 0;
            $this->role->is_superuser = $request->has('is_superuser') ? 1 : 0;
            $this->role->save();
            // Save permissions
            $this->role->perms()->sync($this->permission->preparePermissionsForSave($inputs['permissions']));
            // Was the role created?
            if ($this->role->id)
            {
                // Redirect to the new role page
                Flash::success('Created role');
                return redirect('admin/roles/' . $this->role->id . '/edit');
            }
            // Redirect to the new role page
            Flash::error('Could not create role');
            return redirect('admin/roles/create');
        }
        // Form validation failed
        return redirect('admin/roles/create')->withInput()->withErrors($validator);
    }
    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Response
     */
    public function getShow($id)
    {
        // redirect to the frontend
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param $role
     * @return Response
     */
    public function getEdit($role)
    {
        if(! empty($role))
        {
            $permissions = $this->permission->preparePermissionsForDisplay($role->perms()->get());
        }
        else
        {
            // Redirect to the roles management page
            Flash::error('Role not found');
            return redirect('admin/roles');
        }
        // Title
        $title = 'Update role';
        // Show the page
        return view('core.admin.roles.edit', compact('role', 'permissions', 'title'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param $role
     * @return Response
     */
    public function postEdit(Request $request, $role)
    {
        // Declare the rules for the form validation
        $rules = array(
            'name' => 'required|unique:roles',
            'display_name' => 'required|unique:roles'
        );
        // Validate the inputs
//         $validator = Validator::make($request->all(), $rules, [
//         	'name.required' => 'A name is required.',
//         	'name.unique' => 'Please use a different name.',
//         	'display_name.required' => 'A display name is required',
//         	'display_name.unique' => 'Please use a different display name.'
//         ]);
        
        $name = $request->input('name');
        $display_name = $request->input('display_name');
        
        // Check if the form validates with success
//         if ($validator->passes())
//         {
//             // Update the role data
//             $role->name = $request->input('name');
//             $role->display_name = $request->input('display_name');
//             $role->is_protected = $request->has('is_protected') ? 1 : 0;
//             $role->perms()->sync($this->permission->preparePermissionsForSave($request->input('permissions')));
//             // Was the role updated?
//             if ($role->save())
//             {
//                 // Redirect to the role page
//                 return redirect('admin/roles/' . $role->id . '/edit')->with('success', 'Role updated');
//             }
//             else
//             {
//                 // Redirect to the role page
//                 return redirect('admin/roles/' . $role->id . '/edit')->with('error', 'Could not update role');
//             }
//         }
		if (!empty($name) && $name != $role->name)
		{
			$nameValidator = Validator::make(
				[
					'name' => $name
				],
				[
					'name' => 'required|unique:roles'
				],
				[
        			'name.required' => 'A name is required.',
        			'name.unique' => 'Please use a different name.'				
				]
			);
			
			if ($nameValidator->passes())
			{
				$role->name = $name;
				$role->perms()->sync($this->permission->preparePermissionsForSave($request->input('permissions')));
				if ($role->save())
				{
					// Redirect to the role page
					return redirect('admin/roles/' . $role->id . '/edit')->with('success', 'Role updated');
				}
				else
				{
					// Redirect to the role page
					return redirect('admin/roles/' . $role->id . '/edit')->with('error', 'Could not update role');
				}
			}
		}
		
		if (!empty($display_name) && $display_name != $role->display_name)
		{
			$displayNameValidator = Validator::make(
				[
					'display_name' => $display_name
				],
				[
					'display_name' => 'required|unique:roles'
				],
				[
        			'display_name.required' => 'A display name is required.',
        			'display_name.unique' => 'Please use a different display name.'				
				]
			);
			
			if ($displayNameValidator->passes())
			{
				$role->display_name = $display_name;
				$role->perms()->sync($this->permission->preparePermissionsForSave($request->input('permissions')));
				if ($role->save())
				{
					// Redirect to the role page
					Flash::success('Role updated');
					return redirect('admin/roles/' . $role->id . '/edit');
				}
				else
				{
					// Redirect to the role page
					return redirect('admin/roles/' . $role->id . '/edit')->withInput()->withErrors($validator->messages());
				}
			}			
		}
		
		$role->perms()->sync($this->permission->preparePermissionsForSave($request->input('permissions')));
		$role->is_protected = $request->has('is_protected') ? 1 : 0;
		$role->is_superuser = $request->has('is_superuser') ? 1 : 0;
		$role->save();
        // Form validation failed
        return redirect('admin/roles/' . $role->id . '/edit');
    }
    /**
     * Remove user page.
     *
     * @param $role
     * @return Response
     */
    public function getDelete($role)
    {
        // Title
        $title = 'Delete role';
        // Show the page
        return view('core.admin.roles.delete', compact('role', 'title'));
    }
    /**
     * Remove the specified user from storage.
     *
     * @param $role
     * @internal param $id
     * @return Response
     */
    public function postDelete($role)
    {
    	if (!$role->canBeDeleted)
    	{
    		Flash::error('Protected roles can not be deleted.');
    		return redirect('admin/roles');
    	}
    	
		// Was the role deleted?
		if($role->delete()) 
		{
			// Redirect to the role management page
			Flash::success('Role deleted');
			return redirect('admin/roles');
		}
		// There was a problem deleting the role
		Flash::error('Could not delete role');
		return redirect('admin/roles');
    }
    /**
     * Show a list of all the roles formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function getData()
    {
        $roles = Role::select(array('roles.id',  'roles.name', 'roles.id as users', 'roles.created_at'));
        return Datatables::of($roles)
        // ->edit_column('created_at','{{{ Carbon::now()->diffForHumans(Carbon::createFromFormat(\'Y-m-d H\', $test)) }}}')
        ->edit_column('users', '{{{ DB::table(\'role_user\')->where(\'role_id\', \'=\', $id)->count()  }}}')
        ->add_column('actions', '<a href="{{{ URL::to(\'admin/roles/\' . $id . \'/edit\' ) }}}" class="iframe btn btn-xs btn-default">{{{ Lang::get(\'button.edit\') }}}</a>
                                <a href="{{{ URL::to(\'admin/roles/\' . $id . \'/delete\' ) }}}" class="iframe btn btn-xs btn-danger">{{{ Lang::get(\'button.delete\') }}}</a>
                    ')
        ->remove_column('id')
        ->make();
    }
}