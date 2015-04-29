<?php namespace App\Http\Controllers\Users;

use App\Events\UserFollowedSomeone;
use App\Events\UserUnfollowedSomeone;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\FollowUserRequest;
use App\Http\Requests\Account\UnfollowUserRequest;
use Laracasts\Flash\Flash;

class FollowsController extends Controller {

    /**
     * Create a new instance of FollowsController
     *
     * @type mixed
     */
    public function __construct()
    {

    }

    /**
     * Follow a user.
     *
     * @param FollowUserRequest $request
     * @return void
     */
    public function follow(FollowUserRequest $request)
    {
        $user = $request->user();
        $userToFollow = $request->route()->getParameter('user');

        if ($user->getId() == $userToFollow->getId())
        {
            Flash::error('You can not follow yourself.');
            return redirect()->back();
        }

        if ($user->isFollowing($userToFollow))
        {
            Flash::error('You are already following this user.');
            return redirect()->back();
        }

        event(new UserFollowedSomeone($userToFollow, $user));

        Flash::success('You are now following ' . $userToFollow->getName() . '.');

        return redirect()->back();
    }

    /**
     * Unfollow a user.
     *
     * @param UnfollowUserRequest $request
     * @return void
     */
    public function unfollow(UnfollowUserRequest $request)
    {
        $user = $request->user();
        $userToUnfollow = $request->route()->getParameter('user');

        if ($user->getId() == $userToUnfollow->getId())
        {
            Flash::error('You can not unfollow yourself.');
            return redirect()->back();
        }

        if (!$user->isFollowing($userToUnfollow))
        {
            Flash::error('You are not following this user.');
            return redirect()->back();
        }

        if ($userToUnfollow->isBanned())
        {
            Flash::error('This user has been banned.');
            return redirect()->route('home.show');
        }

        event(new UserUnfollowedSomeone($userToUnfollow, $user));

        Flash::success('You are no longer following ' . $userToUnfollow->getName() . '.');

        return redirect()->back();
    }
}