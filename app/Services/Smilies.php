<?php namespace App\Services;

/**
 * Smilies library
 * 
 * @package        Smilies
 * @author        BrianLabs
 * @version        1.0.0
 * @license        http://unlicense.org/
 * @copyright    Copyright (c) 2011 BrianLabs
 */
class Smilies 
{
    /**
     * Replace the text smilies with images
     *
     * @access   public
     * @param    string   $value
     * @return   string
     */
    public static function parse($value) 
    {
        $config = config('smilies');
        
        $smileys = $config['images'];

//         foreach($smileys as $key => $val) {
//             $value = str_replace($key, '<img src="' . $config['path'] . $smileys[$key][0] . '" width="' . $smileys[$key][1] . '" height="' . $smileys[$key][2] . '" alt="' . $smileys[$key][3] . '" style="border:0;" />', $value);
//         }
		foreach($smileys as $key => $val)
		{
			$value = preg_replace("/\$key\s/", '<img src="' . $config['path'] . $smileys[$key][0] . '" width="' . $smileys[$key][1] . '" height="' . $smileys[$key][2] . '" alt="' . $smileys[$key][3] . '" style="border:0;" />', $value);
		}
		
        return $value;
    }
}