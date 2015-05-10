<?php namespace App\Services;

/**
 * Smilies library
 * 
 * @package        Smilies
 * @author        ItsLeo
 * @version        1.0.0
 * @license        http://unlicense.org/
 * @copyright    Copyright (c) 2015 ItsLeo
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
        $smilies = array(
            ':)' => '/assets/img/smilies/smile.png',
            ':D' => '/assets/img/smilies/grin.png',
            ':P' => '/assets/img/smilies/tongue.png',
            ';)' => '/assets/img/smilies/wink.png'
        );

        foreach($smilies as $key => $img)
        {
            $value = preg_replace("/[\s]" . preg_quote($key, "/") . "[\s]*/", "<img src='" . $img . "' />", $value);
        }

        return $value;
    }
}