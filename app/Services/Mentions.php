<?php namespace App\Services;

use App\User;

/**
 * Mentions library
 * 
 * @package        Mentions
 * @author        ItsLeo
 * @version        0.01
 * @license        GPL v3
 * @copyright    Copyright (c) 2015 ItsLeo
 */
class Mentions 
{
    /**
     * Replace @name mentions with link
     *
     * @access   public
     * @param    string   $value
     * @return   string
     */
    public static function parse($value) 
    {
       // $config = config('mentions');
      
      // find @tags ... might have spaces or punctuation
      if (preg_match_all("/\@([A-Za-z0-9\-_!\.\s]+)/", $value, $matches))
	  {
      	$matches = $matches[1];
      	foreach($matches as $possible_username)
		{
      		$user = null;
      		while((strlen($possible_username) > 0) && !$user)
			{
				if ($user = User::where('name', '=', $possible_username)->first())
				{
					$value = preg_replace("/".preg_quote("@{$possible_username}", "/")."/", "<a href=\"{$user->profileURL}\">@{$possible_username}</a>", $value);
					break;
				}
				
				// chop last word off of it
				$new_possible_username = preg_replace("/([^A-Za-z0-9]{1}|[A-Za-z0-9]+)$/", "", $possible_username);
				if ($new_possible_username !== $possible_username)
				{
					$possible_username = $new_possible_username;
				}
				else
				{
					break;
				}
			}
      	}
      }
		
      return $value;
	}
}