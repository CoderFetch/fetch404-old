<?php namespace App\Services;

require_once base_path() . '/core/functions/html/library/HTMLPurifier.auto.php';

use HTMLPurifier, HTMLPurifier_Config;

class Purifier 
{

    public static function clean($html)
    {
		$config = HTMLPurifier_Config::createDefault();
		$config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
		$config->set('URI.DisableExternalResources', false);
		$config->set('URI.DisableResources', false);
		$config->set('HTML.Allowed', 'u,p,b,i,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img');
		$config->set('CSS.AllowedProperties', array('float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size'));
		$config->set('HTML.AllowedAttributes', 'src, height, width, alt, class, *.style');
		$purifier = new HTMLPurifier($config);
        
        return $purifier->purify($html);
    }

    public static function cleanWithExtraDots($html)
    {
		$config = HTMLPurifier_Config::createDefault();
		$config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
		$config->set('URI.DisableExternalResources', false);
		$config->set('URI.DisableResources', false);
		$config->set('HTML.Allowed', 'u,p,b,i,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img');
		$config->set('CSS.AllowedProperties', array('float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size'));
		$config->set('HTML.AllowedAttributes', 'src, height, width, alt, class, *.style');
		$purifier = new HTMLPurifier($config);
        
        $dots = '';
        if (strlen(strip_tags($html)) > env('MAX_STR_SIZE', 50))
        {
        	$dots = '...';
        }
        
        return Purifier::clean($html);
    }
}