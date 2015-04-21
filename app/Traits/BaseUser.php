<?php namespace App\Traits;

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

}