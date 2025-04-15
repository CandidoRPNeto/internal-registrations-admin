<?php
namespace Src\Request;

class LoginRequest
{

    public static function validate($data)
    {
        if (!isset($data["password"]) || !isset($data["email"])) {
            return false;
        }
        return true;
    }

}