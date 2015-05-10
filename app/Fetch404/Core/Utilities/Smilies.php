<?php namespace Fetch404\Core\Utilities;

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
            ':(' => '/assets/img/smilies/sad.png',
            ':D' => '/assets/img/smilies/grin.png',
            ':P' => '/assets/img/smilies/tongue.png',
            ';)' => '/assets/img/smilies/wink.png',
            ':/' => '/assets/img/smilies/pouty.png',
            ':|' => '/assets/img/smilies/pouty.png'
        );

        foreach($smilies as $key => $img)
        {
            $value = preg_replace("/(^|>|\s)(" . preg_quote($key, "/") . ")($|<|\s)/", "$1<img src='" . $img . "' />$3", $value);

        }

        return $value;
    }
}