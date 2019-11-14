<?php

require_once "dbClass.php";
require_once "Pet.php";
require_once "Pet_HelpRequest.php";


//class acts as an additional layer between dbClass and site files
//contains functions regarding "pets" table and uses functions in dbClass
class dbClassUserFunctions
{
    public function insertUser($h)
    {
        $db = new dbClass();
        /*
        foreach ($h as $k=>$v)
        {

            if (is_string($v)){
                $h[$k] = $db->stringToXml($h[$k]);
            }

        }
        */
        $db->insertLine("users",$h);
    }

    public function getUserByID($ID){
        $db = new dbClass();
        $uArr = $db->getObjectsGeneral("users", " WHERE `userID`='".$ID."'", "User");
        $u = null;
        if ($uArr != null)
            $u = $uArr[0];
        return $u;
    }

    public function getUserByEmail($email){
        $db = new dbClass();
        $uArr = $db->getObjectsGeneral("users", " WHERE `userEmail`='".$email."'", "User");
        $u = null;
        if ($uArr != null)
            $u = $uArr[0];
        return $u;
    }

    public function verifyPassword($entered, $userID){
        $userFromDB = $this->getUserByID($userID);
        if ($userFromDB == null)    //user not found:
        {
            $ans = -1;
        }
        else                        //user found:
        {
            //verifying password:
            $realPassword = $userFromDB->getUserPassword();
            $verify=password_verify($entered,$realPassword);

            if ($verify)
            {
                $ans = 1;
            }
            else
            {
                $ans = 0;
            }
        }
        return $ans;
    }

    public function changePassword($newPassword, $userID){

    }



}