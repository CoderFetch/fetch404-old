<?php namespace App\Http\Controllers\Admin;

// External libraries (well, sort of)
use App\Http\Controllers\AdminController;

// The Laracasts libraries
use Laracasts\Flash\Flash;

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
	 * @return void
	 */
	public function showIndex()
	{
		return view('core.admin.index');
	}

	/**
	 * Attempt to show the general settings page
	 *
	 * @return void
	 */
	public function showGeneral()
	{
		return view('core.admin.general');
	}
		 
	/**
	 * Create a new admin page controller instance.
	 *
	 * @return mixed
	 */
	public function __construct()
	{
		
	}

}
