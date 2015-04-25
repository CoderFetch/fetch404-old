<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder {

    public function run()
    {
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

        
        $accessAdminPanel = new Permission();
        $accessAdminPanel->name = 'accessAdminPanel';
        $accessAdminPanel->save();

        $banUser = new Permission();
        $banUser->name = 'banUser';
        $banUser->save();

        $deleteUser = new Permission();
        $deleteUser->name = 'deleteUser';
        $deleteUser->save();

        $editUser = new Permission();
        $editUser->name = 'editUser';
        $editUser->save();

        $viewDiscussion = new Permission();
        $viewDiscussion->name = 'viewDiscussion';
        $viewDiscussion->save();

        $lockDiscussion = new Permission();
        $lockDiscussion->name = 'lockDiscussion';
        $lockDiscussion->save();

        $pinDiscussion = new Permission();
        $pinDiscussion->name = 'pinDiscussion';
        $pinDiscussion->save();

        $viewCategory = new Permission();
        $viewCategory->name = 'viewCategory';
        $viewCategory->save();
        
        $viewChannel = new Permission();
        $viewChannel->name = 'viewChannel';
        $viewChannel->save();
        
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
            $register,
            $accessAdminPanel,
            $banUser,
            $deleteUser,
            $editUser,
            $viewDiscussion,
            $lockDiscussion,
            $pinDiscussion,
            $viewChannel,
            $viewCategory
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
            $register,
            $banUser,
            $pinDiscussion,
            $viewDiscussion,
            $lockDiscussion,
            $viewChannel,
            $viewCategory
        );
        $memberPerms = array(
            $startDiscussion,
            $reply,
            $editOwnPost,
            $editOwnDiscussion,
            $deleteOwnPost,
            $deleteOwnDiscussion,
            $login,
            $register,
            $viewDiscussion,
            $viewCategory,
            $viewChannel
        );
        $guestPerms = array(
            $login,
            $register,
            $viewDiscussion,
            $viewChannel,
            
        );
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