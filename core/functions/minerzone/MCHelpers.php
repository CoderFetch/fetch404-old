<?php namespace MinerZone\Helpers;

class MCHelpers {

    public static function checkValidName($name)
    {
        $check_mcUser = file_get_contents('http://www.minecraft.net/haspaid.jsp?user='.$name.'');

        if ($check_mcUser == 'true')
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}