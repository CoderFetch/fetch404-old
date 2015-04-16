<?php namespace App\Http\Controllers;

require_once base_path() . '/core/functions/html/library/HTMLPurifier.auto.php';

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;
	
	protected $purifier = null;
	
	public function __construct()
	{
		$config = \HTMLPurifier_Config::createDefault();

		$config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
		$config->set('URI.DisableExternalResources', false);
		$config->set('URI.DisableResources', false);
		$config->set('HTML.Allowed', 'u,p,b,i,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img');
		$config->set('CSS.AllowedProperties', array('float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size'));
		$config->set('HTML.AllowedAttributes', 'src, height, width, alt, class, *.style');

		$this->purifier = new \HTMLPurifier($config);	

		view()->share('purifier', $this->purifier);
	}
}
