<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

use App\User;
use App\Channel;
use App\Category;
use App\Topic;
use App\Post;

class InstallForum extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'forum:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Install Fetch404';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		//
		if (Schema::hasTable('migrations') ) //Already installed(?)
		{
			$this->error('Fetch404 has already been installed. Go and use it!');
			return;
		}

		if ($this->confirm('Would you like to install Fetch404 on the webserver ' . config('app.url') . '?'))
		{
			//
			$this->call('migrate', ['--quiet']);
			$this->info('Migrated tables');
			sleep(1.5);
			$this->call('vendor:publish', ['--quiet']);
			$this->info('Published vendor assets.');
			sleep(1.5);
			$this->info('You can now set up the admin user.');
			sleep(2);

			$name = $this->ask('Choose a name for the admin user:');

			if ($name == null || $name == '')
			{
				$this->info('Using "admin" as the admin user name.');
			}

			$email = $this->ask('Please enter your email address.');

			if ($email == null || $email == '')
			{
				$this->error('You must put in an administrator email. Exiting installer.');
				return;
			}

			$password = $this->secret('Choose a password for the admin user (defaults to "admin"):');

			if ($password == null || $password == '')
			{
				$this->info('Using "admin" as the admin user password. CHANGE IT WHEN YOU LOG ON!');
			}

			$confirmed = 0;

			if ($this->confirm('Would you like to confirm your account?'))
			{
				$confirmed = 1;
			}
			else
			{
				$confirmed = 0;
			}


			$this->info('Fetch404 has been installed! Go to ' . config('app.url') . ' and start using your forum!');
		}
		else
		{
			$this->info(PHP_EOL . '===========' . PHP_EOL . 'Goodbye!' . PHP_EOL . '===========');
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [];
	}

}
