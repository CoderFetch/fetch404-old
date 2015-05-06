<?php namespace App\Http\Controllers;

use App\Category;
use App\CategoryPermission;
use App\ChannelPermission;
use App\Setting;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

use App\Channel;
use App\Http\Requests\Installer\InstallRequest;
use App\Post;
use App\Topic;
use App\User;
use App\Role;

use Cmgmyr\Messenger\Models\Thread as Conversation;
use Cmgmyr\Messenger\Models\Message as ConversationMessage;

use App\Services\Purifier;
use Illuminate\Support\Facades\View;

class InstallController extends Controller
{
    private $user;

    private $channel;

    private $category;

    private $topic;

    private $post;

    private $conversation;

    private $conversationMessage;

    private $role;

    private $auth;

    /**
     * Initializer.
     *
     * @param User $user
     * @param Channel $channel
     * @param Category $category
     * @param Topic $topic
     * @param Post $post
     * @param Conversation $conversation
     * @param ConversationMessage $conversationMessage
     * @param Role $role
     * @param Guard $auth
     */
    public function __construct(
        User $user, Channel $channel, Category $category,
        Topic $topic, Post $post,
        Conversation $conversation,
        ConversationMessage $conversationMessage,
        Role $role, Guard $auth
    )
    {
        $this->user = $user;
        $this->channel = $channel;
        $this->topic = $topic;
        $this->post = $post;
        $this->conversation = $conversation;
        $this->conversationMessage = $conversationMessage;
        $this->role = $role;
        $this->category = $category;
        $this->auth = $auth;
    }

    public function show()
    {
        return view('core.installer.index');
    }

    public function showDBError()
    {
        return view('core.installer.errors.configuredb');
    }

    public function showPDOException()
    {
        return view('core.installer.errors.pdoexception');
    }

    public function install(InstallRequest $request)
    {
        // Surround everything with try/catch, in case something weird happens
        try
        {
            // STEP 1: Artisan commands
            Artisan::call('migrate', ['--quiet']);
            Artisan::call('vendor:publish', ['--quiet']);
            Artisan::call('clear-compiled', ['--quiet']);
            Artisan::call('cache:clear', ['--quiet']);

            try {
                exec("cd " . base_path());
                exec("composer update");
            } catch (\Exception $ex) {}
            $username = $request->input('username');
            $password = $request->input('password');

            // STEP 2: Create the admin user (confirmed by default)
            $adminUser = $this->user->create(array(
                'name' => $username,
                'email' => $request->input('email'),
                'password' => Hash::make($password),
                'slug' => str_slug($request->input('username')),
                'confirmed' => 1
            ));

            // Step 2b: Set up default roles and permissions
            Artisan::call('db:seed', ['--class' => 'RolesTableSeeder']);
            Artisan::call('db:seed', ['--class' => 'PermissionsTableSeeder']);

            // Step 2c: Add the admin role to the admin user
            $role = $this->role->where(
                'name', '=', 'Administrator'
            )->first();

            $modRole = $this->role->where(
                'name', '=', 'Moderator'
            )->first();

            if ($role)
            {
                $adminUser->attachRole($role);
            }

            // Step 3: Create categories and channels
            $exampleCategory1 = $this->category->create(array(
               'name' =>  'Example Category #1',
               'description' => 'An example category.',
               'weight' => 1,
               'slug' => 'example-category-1'
            ));

            $exampleCategory2 = $this->category->create(array(
                'name' =>  'Example Category #2',
                'description' => 'Another example category.',
                'weight' => 2,
                'slug' => 'example-category-2'
            ));

            $exampleChannel1 = $this->channel->create(array(
               'name' => 'Example Channel #1',
               'description' => 'An example channel.',
               'weight' => 1,
               'category_id' => 1,
               'slug' => 'example-channel-1'
            ));

            $exampleChannel2 = $this->channel->create(array(
                'name' => 'Example Channel #2',
                'description' => 'Another example channel.',
                'weight' => 2,
                'category_id' => 1,
                'slug' => 'example-channel-2'
            ));

            $exampleChannel3 = $this->channel->create(array(
                'name' => 'Example Channel #3',
                'description' => 'Yet another example channel.',
                'weight' => 1,
                'category_id' => 2,
                'slug' => 'example-channel-3'
            ));

            $staffSection = $this->category->create(array(
                'name' =>  'Staff Section',
                'description' => 'Staff only section',
                'weight' => 3,
                'slug' => 'staff-section'
            ));

            $staffChannel = $this->channel->create(array(
                'name' => 'Staff Channel',
                'description' => 'A channel for staff members',
                'weight' => 1,
                'category_id' => 3,
                'slug' => 'staff-channel'
            ));

            $accessStaffSection = [1, 4];
            $createThreads = [1, 4];

            $accessStaffChannel = [1, 4];
            $createThreadsInStaffChannel = [1, 4];

            $postInStaffSection = [1, 4];
            $postInStaffChannel = [1, 4];

            foreach($accessStaffSection as $id)
            {
                $perm = CategoryPermission::firstOrCreate(array(
                    'permission_id' => 20,
                    'role_id' => $id,
                    'category_id' => $staffSection->id
                ));
            }

            foreach($createThreads as $id)
            {
                $perm = CategoryPermission::firstOrCreate(array(
                    'permission_id' => 1,
                    'role_id' => $id,
                    'category_id' => $staffSection->id
                ));
            }

            foreach($accessStaffChannel as $id)
            {
                $perm = ChannelPermission::firstOrCreate(array(
                    'permission_id' => 21,
                    'role_id' => $id,
                    'channel_id' => $staffChannel->id
                ));
            }

            foreach($createThreadsInStaffChannel as $id)
            {
                $perm = ChannelPermission::firstOrCreate(array(
                    'permission_id' => 1,
                    'role_id' => $id,
                    'channel_id' => $staffChannel->id
                ));
            }

            foreach($postInStaffSection as $id)
            {
                $perm = CategoryPermission::firstOrCreate(array(
                    'permission_id' => 6,
                    'role_id' => $id,
                    'category_id' => $staffSection->id
                ));
            }

            foreach($postInStaffChannel as $id)
            {
                $perm = ChannelPermission::firstOrCreate(array(
                    'permission_id' => 6,
                    'role_id' => $id,
                    'channel_id' => $staffChannel->id
                ));
            }

            // Step 4: Create settings
            $data = array(
                0 => array(
                    'name' => 'sitename',
                    'value' => htmlspecialchars($request->has('forumTitle') ? $request->input('forumTitle') : 'A Fetch404 Site')
                ),
                1 => array(
                    'name' => 'sitedesc',
                    'value' => htmlspecialchars($request->has('forumDesc') ? $request->input('forumDesc') : 'This site uses Fetch404.')
                ),
                2 => array(
                    'name' => 'youtube_url',
                    'value' => null
                ),
                3 => array(
                    'name' => 'twitter_url',
                    'value' => null
                ),
                4 => array(
                    'name' => 'gplus_url',
                    'value' => null
                ),
                5 => array(
                    'name' => 'fb_url',
                    'value' => null
                ),
                6 => array(
                    'name' => 'recaptcha',
                    'value' => 'false'
                ),
                7 => array(
                    'name' => 'recaptcha_key',
                    'value' => null
                ),
                8 => array(
                    'name' => 'twitter_feed_id',
                    'value' => null
                ),
                9 => array(
                    'name' => 'bootswatch_theme',
                    'value' => $request->has('bootswatch_theme') ? $request->get('bootswatch_theme') : 6
                ),
                10 => array(
                    'name' => 'navbar_style',
                    'value' => ($request->has('inverse_navbar') ? 1 : 0)
                ),
                11 => array(
                    'name' => 'infractions',
                    'value' => ($request->has('enable_infractions') ? 'true' : 'false')
                ),
                12 => array(
                    'name' => 'outgoing_email',
                    'value' => $request->input('outgoing_email')
                )
            );
            try
            {
                foreach($data as $setting)
                {
                    Setting::create(array(
                       'name' => $setting["name"],
                       'value' => $setting["value"]
                    ));
                }

                // Privacy settings
//                $adminUser->settings()->create(array(
//                    'name' => 'show_what_im_doing',
//                    'value' => true,
//                    'user_id' => $adminUser->getId()
//                ));
                $adminUser->setSetting("show_what_im_doing", true);
                $adminUser->setSetting("show_if_im_online", true);
                $adminUser->setSetting("show_if_im_online", true);
                $adminUser->setSetting("notify_me_on_thread_reply", true);
                $adminUser->setSetting("notify_me_on_thread_lock", true);
                $adminUser->setSetting("notify_me_on_thread_pin", true);
                $adminUser->setSetting("notify_me_on_thread_move", true);
                $adminUser->setSetting("notify_me_on_new_follower", true);
                $adminUser->setSetting("notify_me_on_post_like", true);
                $adminUser->setSetting("notify_me_on_followed_user_new_post", true);
                $adminUser->setSetting("notify_me_on_profile_post", true);
            }
            catch(Exception $ex) {
                if ($ex instanceof \PDOException) // Is it PDOException? If yes, show the "pdoexception" view.
                {
                    return view('core.installer.errors.pdoexception', array(
                        'error' => $ex
                    ));
                }
                else
                {
                    return view('core.installer.errors.exception', array(
                        'error' => $ex
                    ));
                }
            }

            // Step 5: Send the administrator a "welcome" message
            // This is the final step

            $conversation = $adminUser->threads()->create(array(
                'subject' => 'Welcome to your new Fetch404 installation'
            ));

            $messageBody = 'Hey there, <strong>' . $adminUser->name . '</strong>! Thanks for using Fetch404. Here are a few tips to help you get started.';
            $messageBody .= '<h1>Managing your Forum</h1><hr>';
            $messageBody .= '<p>Managing a large forum can be hard. Luckily, Fetch404\'s admin panel allows you to easily customize almost every part of your forum, including categories, channels, and much more. Just go to the "Forum" section of your admin panel and start setting up your forum!</p><hr>';
            $messageBody .= '<h1>Customizing your Site</h1><hr>';
            $messageBody .= '<p>Bored of the same old bland look? Want some color? You can do that! Just go to the "Design" section of your admin panel and you can instantly change the site theme, and if you want to, invert the colors of the navigation bar. You can also edit the custom styles (custom.css).</p><hr>';
            $messageBody .= '<h1>Configuring your Site</h1><hr>';
            $messageBody .= '<p>Want to prevent spambots? Want to change your site\'s name? Need to disable the login or register feature? You can do all of that from the "General" section of your admin panel.</p><br><small>* Note: You will need to have a <a href="https://www.google.com/recaptcha/intro/index.html">reCAPTCHA</a> key in order to enable the captcha.</small><hr>';
            $messageBody .= '<h1>I need help!</h1><hr>';
            $messageBody .= '<p>Don\'t worry! You can go to our <a href="http://fetch404.com">support forum</a> and receive help with various things.</p><hr><p>We hope you enjoy using Fetch404. Please note that there is a lot more than what is listed here. You may want to turn off registering for a bit until you are sure that your website is ready. Once again, enjoy!</p>';

            $message = $adminUser->messages()->create(array(
               'thread_id' => $conversation->id,
                'user_id' => $adminUser->id,
                'body' => Purifier::clean($messageBody)
            ));

            $conversation->addParticipants(array($adminUser->id));

            if ($this->auth->attempt(['name' => $username, 'password' => $password]))
            {
            }

            return view('core.installer.success');
        }
        catch (\Exception $ex)
        {
            if ($ex instanceof \PDOException) // Is it PDOException? If yes, show the "pdoexception" view.
            {
                return view('core.installer.errors.pdoexception', array(
                    'error' => $ex
                ));
            }
            else
            {
                return view('core.installer.errors.exception', array(
                    'error' => $ex
                ));
            }
        }
    }
}