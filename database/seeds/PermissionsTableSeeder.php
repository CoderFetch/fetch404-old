<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class PermissionsTableSeeder extends Seeder {

    public function run()
    {
        Permission::truncate();

        $startDiscussion = new Permission();
        $startDiscussion->name = 'startDiscussion';
        $startDiscussion->save();

        $editOwnDiscussion = new Permission();
        $editOwnDiscussion->name = 'editOwnDiscussion';
        $editOwnDiscussion->save();

        $editAllDiscussions = new Permission();
        $editAllDiscussions->name = 'editAllDiscussions';
        $editAllDiscussions->save();

        $deleteOwnDiscussion = new Permission();
        $deleteOwnDiscussion->name = 'deleteOwnDiscussion';
        $deleteOwnDiscussion->save();

        $deleteAllDiscussions = new Permission();
        $deleteAllDiscussions->name = 'deleteAllDiscussions';
        $deleteAllDiscussions->save();

        $reply = new Permission();
        $reply->name = 'reply';
        $reply->save();

        $editOwnPost = new Permission();
        $editOwnPost->name = 'editOwnPost';
        $editOwnPost->save();

        $deleteOwnPost = new Permission();
        $deleteOwnPost->name = 'deleteOwnPost';
        $deleteOwnPost->save();

        $editAllPosts = new Permission();
        $editAllPosts->name = 'editAllPosts';
        $editAllPosts->save();

        $deleteAllPosts = new Permission();
        $deleteAllPosts->name = 'deleteAllPosts';
        $deleteAllPosts->save();

        $login = new Permission();
        $login->name = 'login';
        $login->save();

        $register = new Permission();
        $register->name = 'register';
        $register->save();

        $adminPerms = array(
            $startDiscussion,
            $editOwnDiscussion,
            $editAllDiscussions,
            $editAllPosts,
            $editOwnPost,
            $deleteAllDiscussions,
            $deleteAllPosts,
            $deleteOwnDiscussion,
            $deleteOwnPost,
            $reply,
            $login,
            $register
        );
        $moderatorPerms = array(
              $startDiscussion,
              $editOwnDiscussion,
              $editAllDiscussions,
              $deleteOwnDiscussion,
              $reply,
              $editOwnPost,
              $editAllPosts,
              $deleteOwnPost,
              $login,
              $register
        );
        $memberPerms = array(
          $startDiscussion,
          $reply,
          $editOwnPost,
          $editOwnDiscussion,
          $deleteOwnPost,
          $deleteOwnDiscussion,
          $login,
          $register
        );
        $guestPerms = array(
            $login,
            $register
        );
//        $groups = ['Administrator', 'Guest', 'Member', 'Moderator', 'Staff'];
        $groups = ['Guest', 'Member', 'Moderator', 'Administrator'];
        foreach($groups as $group)
        {
            $role = Role::where('name', '=', $group)->first();
            if ($role)
            {
                switch($role->name)
                {
                    case 'Administrator':
                        $role->attachPermissions($adminPerms);
                        break;
                    case 'Guest':
                        $role->attachPermissions($guestPerms);
                        break;
                    case 'Member':
                        $role->attachPermissions($memberPerms);
                        break;
                    case 'Moderator':
                        $role->attachPermissions($moderatorPerms);
                        break;
                    default:
                        $role->attachPermissions([]);
                        break;
                }
            }
        }
    }

}