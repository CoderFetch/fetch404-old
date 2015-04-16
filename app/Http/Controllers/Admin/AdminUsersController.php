<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;

use App\User;
use App\Role;
use App\Permission;
use App\Topic;
use App\Post;

use Carbon\Carbon;
use Datatables;
use Input;
use Validator;

use Cmgmyr\Messenger\Models\Thread;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;

use Illuminate\Http\Request;

class AdminUsersController extends AdminController {
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
        $title = 'Users';
        // Grab all the users
        $users = $this->user;
        // Show the page
        return view('core.admin.users.index', compact('users', 'title'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate()
    {
        // All roles
        $roles = $this->role->all();
        // Get all the available permissions
        $permissions = $this->permission->all();
        // Selected groups
        $selectedRoles = Input::old('roles', array());
        // Selected permissions
        $selectedPermissions = Input::old('permissions', array());
		// Title
		$title = 'Create a user';
		// Mode
		$mode = 'create';
		// Show the page
		return view('core.admin.users.create_edit', compact('roles', 'permissions', 'selectedRoles', 'selectedPermissions', 'title', 'mode'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(Request $request)
    {
//         $this->user->username = Input::get( 'username' );
//         $this->user->email = Input::get( 'email' );
//         $this->user->password = Input::get( 'password' );
//         // The password confirmation will be removed from model
//         // before saving. This field will be used in Ardent's
//         // auto validation.
//         $this->user->password_confirmation = Input::get( 'password_confirmation' );
//         // Generate a random confirmation code
//         $this->user->confirmation_code = md5(uniqid(mt_rand(), true));
//         if (Input::get('confirm')) {
//             $this->user->confirmed = Input::get('confirm');
//         }
//         // Permissions are currently tied to roles. Can't do this yet.
//         //$user->permissions = $user->roles()->preparePermissionsForSave(Input::get( 'permissions' ));
//         // Save if valid. Password field will be hashed before save
//         $this->user->save();
//         if ( $this->user->id ) {
//             // Save roles. Handles updating.
//             $this->user->saveRoles(Input::get( 'roles' ));
//             if (Config::get('confide::signup_email')) {
//                 $user = $this->user;
//                 Mail::queueOn(
//                     Config::get('confide::email_queue'),
//                     Config::get('confide::email_account_confirmation'),
//                     compact('user'),
//                     function ($message) use ($user) {
//                         $message
//                             ->to($user->email, $user->username)
//                             ->subject(Lang::get('confide::confide.email.account_confirmation.subject'));
//                     }
//                 );
//             }
//             // Redirect to the new user page
//             return redirect('admin/users/' . $this->user->id . '/edit')
//                 ->with('success', Lang::get('admin/users/messages.create.success'));
//         } else {
//             // Get validation errors (see Ardent package)
//             $error = $this->user->errors()->all();
//             return redirect('admin/users/create')
//                 ->withInput(Input::except('password'))
//                 ->with( 'error', $error );
//         }
		$username = $request->input('name');
		$email = $request->input('email');
		
		$password = $request->input('password');
		$password_confirmation = $request->input('password_confirmation');
		
		$confirm = $request->has('confirm');
		
		$roles = $request->input('roles');
		
		$validator = Validator::make(
			[
				'name' => $username,
				'email' => $email,
				'password' => $password,
				'password_confirmation' => $password_confirmation
			],
			[
				'name' => 'required|min:5|max:13|regex:/[A-Za-z0-9\-_!\.\s]/|unique:users',
				'email' => 'required|unique:users|email',
				'password' => 'required|min:8|max:30|confirmed|regex:/[A-Za-z0-9\-_!\$\^\@\#]/',
			],
			[
				'name.required' => 'A username is required.',
				'name.min' => 'Usernames must be at least 5 characters long.',
				'name.max' => 'Usernames can be up to 13 characters long.',
				'name.regex' => 'You are using characters that are not allowed. Allowed characters: A through Z, a through z, 0 through 9, -, _, !, and . (period)',
				'name.unique' => 'That username is taken. Try another!',
				'email.required' => 'An email address is required.',
				'email.unique' => 'Another account is using this email. Contact support if you don\'t know anything about this.',
				'email.email' => 'Please enter a valid email. Without this, we are unable to provide email-based support.',
				'password.required' => 'A password is required.',
				'password.min' => 'Passwords must be at least 8 characters long.',
				'password.max' => 'Passwords can be up to 30 characters long.',
				'password.confirmed' => 'Your passwords do not match. Please verify that the confirmation matches the original.',
				'password.regex' => 'You are using characters that are not allowed. Allowed characters: A through Z, a through z, 0 through 9, !, -, _, $, ^, @, #'
			]
		);
		
		if ($validator->passes())
		{
			$user = User::create([
				'name' => $username,
				'email' => $email,
				'password' => Hash::make($password),
				'confirmed' => ($confirm ? 1 : 0),
				'slug' => str_slug($name, "-")
			]);
			
			$user->saveRoles($roles);
			
            return redirect('admin/users/')->with('success', 'User created');		
		}
		else
		{
			return redirect('admin/users/create')->withErrors($validator->messages())->withInput();
		}
    }
    /**
     * Display the specified resource.
     *
     * @param $user
     * @return Response
     */
    public function getShow($user)
    {
        // redirect to the frontend
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param $user
     * @return Response
     */
    public function getEdit($user)
    {
        if ( $user->id )
        {
            $roles = $this->role->all();
            $permissions = $this->permission->all();
            // Title
        	$title = 'Update user';
        	// mode
        	$mode = 'edit';
        	return view('core.admin.users.create_edit', compact('user', 'roles', 'permissions', 'title', 'mode'));
        }
        else
        {
            return redirect('admin/users')->with('error', 'User not found');
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param User $user
     * @return Response
     */
    public function postEdit(Request $request, $user)
    {
    	$username = $request->input('name');
    	$email = $request->input('email');
    	
    	$password = $request->input('password');
    	$password_confirmation = $request->input('password_confirmation');
    	
    	$roles = $request->input('roles');
    	
    	if (!empty($username) && $username != $user->name)
    	{
			$nameValidator = Validator::make(
				[
					'name' => $username
				],
				[
					'name' => 'min:5|max:13|regex:/[A-Za-z0-9\-_!\.\s]/|unique:users'
				],
				[
					'name.required' => 'A username is required.',
					'name.min' => 'Usernames must be at least 5 characters long.',
					'name.max' => 'Usernames can be up to 13 characters long.',
					'name.regex' => 'You are using characters that are not allowed. Allowed characters: A through Z, a through z, 0 through 9, -, _, !, and . (period)',
					'name.unique' => 'That username is taken. Try another!'
				]
			);
			
			if ($nameValidator->passes())
			{
				$user->name = $username;
			}
			else
			{
				return redirect('admin/users/' . $user->id . '/edit')->withErrors($nameValidator->messages());
			}
    	}
    	
    	if (!empty($email) && $email != $user->email)
    	{
			$emailValidator = Validator::make(
				[
					'email' => $email
				],
				[
					'email' => 'email|unique:users'
				],
				[
					'email.email' => 'Please enter a valid email',
					'email.unique' => 'That email is in use.'
				]
			);
			
			if ($emailValidator->passes())
			{
				$user->email = $email;
			}
			else
			{
				return redirect('admin/users/' . $user->id . '/edit')->withErrors($emailValidator->messages());
			}    	
    	}
    	
    	if (!empty($password) && !Hash::check($password, $user->password))
    	{
			$passwordValidator = Validator::make(
				[
					'password' => $password,
					'password_confirmation' => $password_confirmation
				],
				[
					'password' => 'min:8|max:30|confirmed|regex:/[A-Za-z0-9\-_!\$\^\@\#]/'
				],
				[
					'password.required' => 'A password is required.',
					'password.min' => 'Passwords must be at least 8 characters long.',
					'password.max' => 'Passwords can be up to 30 characters long.',
					'password.confirmed' => 'Your passwords do not match. Please verify that the confirmation matches the original.',
					'password.regex' => 'You are using characters that are not allowed. Allowed characters: A through Z, a through z, 0 through 9, !, -, _, $, ^, @, #'
				]
			);
			
			if ($passwordValidator->passes())
			{
				$user->password = Hash::make($password);
			}
			else
			{
				return redirect('admin/users/' . $user->id . '/edit')->withErrors($passwordValidator->messages());
			}    
    	}
        if ($user->save()) 
        {
            // Save roles. Handles updating.
            $user->saveRoles($roles);
            return redirect('admin/users')->with('success', 'Saved user');
        } 
        else 
        {
            return redirect('admin/users/' . $user->id . '/edit')->with('error', 'Could not save user');
        }
    }
    /**
     * Remove user page.
     *
     * @param $user
     * @return Response
     */
    public function getDelete($user)
    {
        // Title
        $title = 'Delete user';
        // Show the page
        return view('core.admin.users.delete', compact('user', 'title'));
    }
    /**
     * Remove the specified user from storage.
     *
     * @param $user
     * @return Response
     */
    public function postDelete($user)
    {
        // Check if we are not trying to delete ourselves
        if ($user->id === Auth::id())
        {
            // Redirect to the user management page
            return redirect('admin/users')->with('error', 'You can\'t delete yourself...');
        }

		if ($user->is_protected == 1)
		{
			Flash::error('Protected users can not be deleted.');
			return redirect('admin/users');
		}
        $user->roles()->sync([]);
        $user->roles()->detach();
        
        $id = $user->id;
        $user->delete();
        
        $user = User::find($id);
        
        //Was the user deleted?
        if ( empty($user) )
        {
        	Topic::where(
        		'user_id',
        		'=',
        		$id
        	)->delete();
        	
        	Post::where(
        		'user_id',
        		'=',
        		$id
        	)->delete();
        	
        	Message::where(
        		'user_id',
        		'=',
        		$id
        	)->delete();
        	
        	Participant::where(
        		'user_id',
        		'=',
        		$id
        	)->delete();
        	
            return redirect('admin/users')->with('success', 'User deleted');
        }
        else
        {
            // There was a problem deleting the user
            return redirect('admin/users')->with('error', 'Could not delete user');
        }
    }
    /**
     * Show a list of all the users formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function getData()
    {
        $users = User::select(array('users.id', 'users.name','users.email', 'users.confirmed', 'users.created_at'));
//         return Datatables::of($users)
//         // ->edit_column('created_at','{{{ Carbon::now()->diffForHumans(Carbon::createFromFormat(\'Y-m-d H\', $test)) }}}')
//         ->edit_column('roles', function($row) {
//         	$str = '';
//         	foreach($row->roles as $i => $role)
//         	{
//         		$str .= $role->display_name;
//         		if ($i != sizeof($row->roles) - 1)
//         		{
//         			$str .= ', ';
//         		}
//         	}
//         	return $str;
//         })
//         ->edit_column('confirmed','@if($confirmed)
//                             Yes
//                         @else
//                             No
//                         @endif')
//         ->add_column('actions', '<a href="{{{ URL::to(\'admin/users/\' . $id . \'/edit\' ) }}}" class="btn btn-xs btn-default">Edit</a>
//                                 @if($name == \'admin\')
//                                 @else
//                                     <a href="{{{ URL::to(\'admin/users/\' . $id . \'/delete\' ) }}}" class="btn btn-xs btn-danger">Delete</a>
//                                 @endif
//             ')
//         ->remove_column('id')
//         ->make();
		return Datatables::of($users)
			->edit_column('name', function($row) {
				return $row->name;
			})
			->edit_column('email', function($row) {
				return $row->email;
			})
			->edit_column('roles', function($row) {
				$str = ($row->roles->count() > 0 ? '' : 'None');
				foreach($row->roles as $i => $role)
				{
					$str .= $role->display_name;
					if ($i != sizeof($row->roles) - 1)
					{
						$str .= ', ';
					}
				}
				return $str;			
			})
			->edit_column('confirmed', function($row) {
				return $row->confirmed == 1 ? "Yes" : "No";
			})
			->edit_column('created_at', function($row) {
				return $row->created_at->diffForHumans();
			})
			->edit_column('actions', '<a href="{{{ URL::to(\'admin/users/\' . $id . \'/edit\' ) }}}" class="btn btn-xs btn-default">Edit</a>
                                 @if($name == \'admin\')
                                 @else
                                     <a href="{{{ URL::to(\'admin/users/\' . $id . \'/delete\' ) }}}" class="btn btn-xs btn-danger">Delete</a>
                                 @endif
            ')
			->remove_column('id')
		->make();
    }
}