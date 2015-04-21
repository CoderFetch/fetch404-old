<?php namespace App\Http\Controllers\Admin;

// External libraries (well, sort of)
use App\Http\Controllers\AdminController;

// The Laracasts libraries
use Laracasts\Flash\Flash;

use App\Http\Requests\Admin\SaveGeneralSettingsRequest;
use App\Setting;

class AdminSettingsController extends AdminController {

    /**
     * Attempt to save the general site settings
     *
     * @param SaveGeneralSettingsRequest $request
     * @return mixed
     */
    public function saveGeneral(SaveGeneralSettingsRequest $request)
    {
        $bsTheme = Setting::where('name', '=', 'bootswatch_theme')->first();

        if ($bsTheme)
        {
            $bsTheme->value = $request->input('bootstrap_style');
            $bsTheme->save();
        }

        $navbarStyle = Setting::where('name', '=', 'navbar_style')->first();

        if ($navbarStyle)
        {
            $navbarStyle->value = $request->input('navbar_theme') == 1 ? '1' : '0';
            $navbarStyle->save();
        }

        $enableRecaptcha = Setting::where('name', '=', 'recaptcha')->first();

        if ($enableRecaptcha)
        {
            $enableRecaptcha->value = ($request->has('enable_recaptcha') ? 'true' : 'false');
            $enableRecaptcha->save();
        }

        $recaptchaKey = Setting::where('name', '=', 'recaptcha_key')->first();

        if ($recaptchaKey)
        {
            $recaptchaKey->value = $request->has('recaptcha') ? $request->input('recaptcha') : null;
            $recaptchaKey->save();
        }

        $siteName = Setting::where('name', '=', 'sitename')->first();

        if ($siteName)
        {
            if ($siteName->value != $request->input('sitename'))
            {
                $siteName->value = $request->input('sitename');
                $siteName->save();
            }
        }

        Flash::success('Updated site settings!');

        return redirect(route('admin.get.general'));
    }

    /**
     * Create a new admin settings controller instance.
     *
     * @return mixed
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('confirmed');
    }

}
