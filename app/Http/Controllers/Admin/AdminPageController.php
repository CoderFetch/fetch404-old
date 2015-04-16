<?php namespace App\Http\Controllers\Admin;

// External libraries (well, sort of)
use App\Http\Controllers\AdminController;

// Models
use App\User;
use App\AccountConfirmation;
use App\NameChange;

// Illuminate stuff
use Illuminate\Http\Request;

// The Laracasts libraries
use Laracasts\Flash\Flash;

// The facades need to be included for some reason O_o
use Auth;
use Hash;
use Mail;
use Response;
use Redirect;
use Session;
use Validator;

class AdminPageController extends AdminController {

	/*
	|--------------------------------------------------------------------------
	| Admin page controller
	|--------------------------------------------------------------------------
	|
	| All this does is show the admin-related views.
	| There really isn't much of anything here.
	|
	*/
	
	/**
	 * Attempt to show the admin index page
	 *
	 * @param Illuminate\Http\Request $request
	 * @return void
	 */
	public function showIndex(Request $request)
	{
		return view('core.admin.index');
	}
		 
	/**
	 * Create a new admin page controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		
	}

}
