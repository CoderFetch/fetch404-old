<?php namespace App\Http\Controllers;

use Fetch404\Core\Models\User;

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
