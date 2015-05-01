<?php namespace App\Services;

use App\NameChange;
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
				$user = User::where('name', '=', $possible_username)->first();
				if ($user)
				{
					$value = preg_replace("/".preg_quote("@{$possible_username}", "/")."/", "<a href=\"{$user->profileURL}\"><img src=\"{$user->getAvatarURL(20)}\" height=\"25\" width=\"20\" style=\"margin-bottom: 6px;\" />&nbsp;{$possible_username}</a>", $value);
					break;
				}

				$nameChange = NameChange::where('old_name', '=', $possible_username)->first();
				if ($nameChange)
				{
					$value = preg_replace("/".preg_quote("@{$possible_username}", "/")."/", "<a href=\"{$nameChange->user->profileURL}\"><img src=\"{$nameChange->user->getAvatarURL(20)}\" height=\"25\" width=\"20\" style=\"margin-bottom: 6px;\" />&nbsp;{$nameChange->new_name}</a>", $value);
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