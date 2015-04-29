<?php namespace App\Traits;

use App\User;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Thread;

use App\AccountConfirmation;
use App\Ticket;

use Auth;

use Illuminate\Support\Facades\Storage;

use MinerZone\Helpers\MCHelpers;
use MinerZone\Helpers\Helpers;

trait BaseUser {

    /*
     * Relationship functions
     * DO NOT MODIFY
     */

    /*
     * Get all the topics created by a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics()
    {
        return $this->hasMany('App\Topic');
    }

    /*
    * Get all the posts created by a user.
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    /*
     * Get all of the user's tickets.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany('App\Ticket');
    }

    /*
     * Get all of the user's news posts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function news()
    {
        return $this->hasMany('App\News');
    }

    /*
     * Get any name changes the user has had.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function nameChanges()
    {
        return $this->hasMany('App\NameChange');
    }

    /*
     * Get all of the user's recent notifications.
     *
     * @return mixed
     */
    public function notifications()
    {
        return $this->hasMany('App\Notification')
            ->with(['user', 'subject'])
            ->take(5)
            ->latest();
    }

    /*
     * Get all of the user's unread notifications.
     *
     * @return mixed
     */
    public function unreadNotifications()
    {
        return $this->notifications()->unread($this->id);
    }

    /*
     * Get the user's account confirmation object.
     *
     * @return App\AccountConfirmation
     */
    public function getAccountConfirmation()
    {
        $confirmation = AccountConfirmation::where(
            'user_id',
            '=',
            $this->id
        )->first();

        if ($confirmation == null)
        {
            return null;
        }

        if ($this->isConfirmed())
        {
            return null;
        }

        return $confirmation;
    }

    /*
     * Get a user's conversations.
     *
     * @return Cmgmyr\Messenger\Models\Thread
     */
    public function getConversations()
    {
        return Thread::forUser($this->id);
    }

    /*
     * Attribute functions
     * Not recommended to modify.
     */
    /*
     * Is the user confirmed?
     *
     * @return boolean
     */
    public function isConfirmed()
    {
        return $this->confirmed == 1;
    }

    /*
     * Get the user's profile URL.
     *
     * @return string
     */
    public function getProfileURLAttribute()
    {
        return route('profile.get.show', ['slug' => $this->slug, 'id' => $this->id]);
    }

    /*
     * Get the user's name color.
     *
     * @return string
     */
    public function getRoleColorAttribute()
    {
        if ($this->hasRole('owner'))
        {
            return "#CC0000";
        }
        elseif ($this->hasRole('admin'))
        {
            return "#ccc";
        }
        else
        {
            return "#fff";
        }
    }

    /*
     * Get a user's prefix.
     *
     * @return string
     */
    public function getPrefixAttribute()
    {
        if ($this->roles->count() > 0)
        {
            $prefix = '[' . $this->roles()->orderBy('created_at', 'desc')->first()->display_name . ']';
            return $prefix;
        }
        else
        {
            return "[Member]";
        }
    }

    /*
     * Get the generated URL to a user's avatar.
     * Returns a link to the default avatar if the user does not have an avatar
     * This may also return a Cravatar image URL if the user has set their Minecraft name and it is valid.
     *
     * @param integer $size
     * @return string
     */
    public function getAvatarURL($size = 25, $large = true)
    {
        if ($this->mcname)
        {
            if (MCHelpers::checkValidName($this->mcname))
            {
                return "//cravatar.eu/avatar/" . $this->mcname . "/" . $size;
            }
            else
            {
                return "//cravatar.eu/avatar/Steve/" . $size;
            }
        }
        else
        {
            $extensions = [
                'png',
                'jpg'
            ];

            foreach($extensions as $ext)
            {
                if (Storage::exists('avatars/' . $this->id . '.' . $ext))
                {
                    return 'avatars/' . $this->id . '.' . $ext;
                }
            }

            return '/assets/img/defaultavatar' . ($large ? 'large' : '') . '.png';
        }
    }
    /*
     * Other functions (role IDs, etc)
     *
     * Don't modify these!
     */

    /**
     * Returns user's current role ids only.
     * @return array|bool
     */
    public function currentRoleIds()
    {
        $roles = $this->roles;
        $roleIds = false;
        if( !empty( $roles ) ) {
            $roleIds = array();
            foreach( $roles as $role )
            {
                $roleIds[] = $role->id;
            }
        }
        return $roleIds;
    }

    /*
     * Save a user's roles, input is taken from the Select2 inputs.
     *
     * @param array $inputRoles
     * @return void
     */
    public function saveRoles($inputRoles)
    {
        if(! empty($inputRoles)) {
            $this->roles()->sync($inputRoles);
        } else {
            $this->roles()->detach();
        }
    }

    /*
     * Get a user's number of unread tickets.
     *
     * @return integer
     */
    public function unreadTicketsCount()
    {
        $tickets = Ticket::forUser(Auth::user()->id);
        $i = 0;

        foreach($tickets as $ticket)
        {
            if ($ticket->isUnread(Auth::user()->id))
            {
                $i++;
            }
        }

        return $i;
    }

    public function genNotificationHTML()
    {
        if ($this->unreadNotifications()->count() < 1)
        {
            return '<ul class="list notificationsList"><li><p>You have no new notifications.</p></li><li id="viewAllNotifications"><a href="#">View all notifications »</a></li></ul>';
        }
        else
        {
            $html = '<ul class="list notificationsList">';
            foreach($this->notifications()->latest('created_at')->take(5) as $notification)
            {
                $html .= '<li class="notification">
                                <a href="#">
                                    <span class="avatar">' . strtoupper(substr(strip_tags($notification->sender->name), 0, 1)) . '</span>
                                    test
                                    <small class="time pull-right">' . $notification->created_at->diffForHumans() . '</small>
                                </a>
                            </li>';
            }

            $html .= '<li id="viewAllNotifications"><a href="#">View all notifications »</a></li></ul>';
            return $html;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getJoinedOn()
    {
        $now = Carbon::now();
        $now->subDays(7);

        return ($this->created_at > $now->toDateTimeString() ? $this->created_at->diffForHumans() : $this->created_at->format('M j, Y'));
    }

    public function getLastActivity()
    {
        $now = Carbon::now();
        $now->subDays(7);

        if ($this->last_active == null)
        {
            return ($this->created_at == null ? "Never" : ($this->created_at > $now->toDateTimeString() ? $this->created_at->diffForHumans() : $this->created_at->format('M j, Y')));
        }

        return ($this->last_active > $now->toDateTimeString() ? $this->last_active->diffForHumans() : $this->last_active->format('M j, Y'));
    }

    public function postCount()
    {
        $posts = $this->posts;

        return $posts->count();
    }

    /**
     * Check to see if the current user is banned.
     * Will automatically update the ban data when this is called.
     * TODO: Move the ban updating to the scheduler.
     *
     * @return bool
     */
    public function isBanned()
    {
        if ($this->banned_until != null && $this->banned_until < Carbon::now()->toDateTimeString() && $this->is_banned == 1)
        {
            $this->update(array(
                'is_banned' => 0,
                'banned_until' => null
            ));
        }

        if ($this->banned_until != null)
        {
            return ($this->is_banned == 1 && $this->banned_until > Carbon::now()->toDateTimeString());
        }

        return $this->is_banned == 1;
    }

    /**
     * Check to see if the current user "is" a certain user
     * You can provide either a name or a user ID.
     *
     * @param User $user
     * @return boolean
     */
    public function isUser(User $user)
    {
        if (is_null($user)) return false;

        return $this->getId() == $user->getId();
    }

    /**
     * Get a user's current "status" (their latest profile post)
     *
     * @return object
     */
    public function currentStatus()
    {
        $profilePost = $this->profilePosts()->where('from_user_id', '=', $this->getId())->where('to_user_id', '=', $this->getId())->first();

        return $profilePost;
    }

    /**
     * Query scopes
     *
     * @param $query
     * @return mixed
     */
    public function scopeBanned($query)
    {
        return $query->where('is_banned', '=', 1);
    }
}