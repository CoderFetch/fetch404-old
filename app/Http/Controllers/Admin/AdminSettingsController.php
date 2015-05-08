<?php namespace App\Http\Controllers\Admin;

// External libraries (well, sort of)
use App\Http\Controllers\AdminController;

// The Laracasts libraries
use Fetch404\Core\Models\Setting;
use Fetch404\Core\Repositories\SettingsRepository;
use Laracasts\Flash\Flash;

use App\Http\Requests\Admin\SaveGeneralSettingsRequest;

class AdminSettingsController extends AdminController {

    private $settings;

    /**
     * Attempt to save the general site settings
     *
     * @param SaveGeneralSettingsRequest $request
     * @return mixed
     */
    public function saveGeneral(SaveGeneralSettingsRequest $request)
    {
        $theme = $request->input('bootstrap_style');
        $navbarStyle = $request->input('navbar_theme');
        $enableRecaptcha = $request->has('enable_recaptcha') ? 'true' : 'false';
        $siteName = $request->input('sitename');
        $recaptchaKey = $request->has('recaptcha') ? $request->input('recaptcha') : null;

        $this->settings->setSetting("bootswatch_theme", $theme);
        $this->settings->setSetting("navbar_style", $navbarStyle);
        $this->settings->setSetting("recaptcha", $enableRecaptcha);
        $this->settings->setSetting("recaptcha_key", $recaptchaKey);
        $this->settings->setSetting("sitename", $siteName);

        Flash::success('Updated site settings!');

        return redirect(route('admin.get.general'));
    }

    /**
     * Create a new admin settings controller instance.
     *
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->middleware('auth');
        $this->middleware('confirmed');
        $this->settings = $settingsRepository;
    }

}
