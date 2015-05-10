<?php namespace App\Http\Controllers\Admin;

use App\Events\UserWasBanned;
use App\Events\UserWasUnbanned;
use App\Http\Controllers\AdminController;

use App\Http\Requests\Admin\Forum\BanUserRequest;
use App\Http\Requests\Admin\Forum\UnbanUserRequest;

use Datatables;

use Fetch404\Core\Models\Permission;
use Fetch404\Core\Models\Role;
use Fetch404\Core\Models\User;

use Fetch404\Core\Repositories\UsersRepository;
use Input;
use Laracasts\Flash\Flash;
use Validator;

class AdminUsersController extends AdminController {

    protected $users;

    protected $role;

    protected $permission;

    public function __construct(UsersRepository $usersRepository, Role $role, Permission $permission)
    {
        parent::__construct();
        $this->users = $usersRepository;
        $this->role = $role;
        $this->permission = $permission;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $title = 'Users';

        $users = $this->users->getAll();

        return view('core.admin.users.index', compact('users', 'title'));
    }

	/**
	 * Ban a user.
	 *
	 * @param BanUserRequest $request
	 * @return mixed
	 */
	public function banUser(BanUserRequest $request)
	{
		$user = $request->route()->getParameter('user');

		if ($user->getId() == 1)
		{
			Flash::error('This user can not be banned.');
			return redirect(route('admin.users.get.index'));
		}

		if ($user->isBanned())
		{
			Flash::error('This user is already banned.');
			return redirect(route('admin.users.get.index'));
		}

		if ($user->isUser($request->user()))
		{
			Flash::error('You can not ban yourself.');
			return redirect(route('admin.users.get.index'));
		}

		event(new UserWasBanned($user, $request->user(), null));

		Flash::success('User has been banned.');
		return redirect(route('admin.users.get.index'));
	}

	/**
	 * Unban a user.
	 *
	 * @param UnbanUserRequest $request
	 * @return mixed
	 */
	public function unbanUser(UnbanUserRequest $request)
	{
		$user = $request->route()->getParameter('user');

		if (!$user->isBanned())
		{
			Flash::error('This user is not banned.');
			return redirect(route('admin.users.get.index'));
		}

		event(new UserWasUnbanned($user, $request->user()));

		Flash::success('User has been unbanned.');
		return redirect(route('admin.users.get.index'));
	}
}