<?php namespace App\Http\Controllers;

// External libraries (well, sort of)
use App\Http\Controllers\Controller;

// Models
use App\User;
use App\AccountConfirmation;

// Illuminate stuff
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

// The Laracasts libraries
use Illuminate\Support\Collection;
use Laracasts\Flash\Flash;

// The facades need to be included for some reason O_o
use Auth;
use Response;
use Redirect;
use Session;
use Validator;

class UsersController extends Controller {

    /**
     * Show a list of members.
     *
     * @return mixed
     */
    public function showMembers()
    {
        $users = User::orderBy('name', 'asc')->get();

        $users = $users->filter(function($item)
        {
            // Only show if the user is not banned and they have confirmed
            // their account. This is to prevent spambot clutter.
            return (!$item->isBanned()) && $item->isConfirmed();
        });

        return view('core.forum.members', compact('users'));
    }

    /**
     * Create a new users controller instance.
     *
     * @return mixed
     */
    public function __construct()
    {

    }

}
