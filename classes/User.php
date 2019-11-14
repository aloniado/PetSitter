<?php
require_once "dbClass.php";

class User
{
    private $userID;
    private $userEmail;
    private $userPassword;
    private $userFName;
    private $userLName;
    private $userPhone;
    private $userBirthday;
    private $userAbout;
    private $userRegisterTime;
    private $userStatus;
    /*
    public function __construct($userPassword, $userFName, $userLName, $userEmail)
    {   //construct a new basic user
        //$this->userID = $userID;
        $this->userPassword = password_hash($userPassword,PASSWORD_DEFAULT);
        //$this->userRegisterTime = $userRegisterTime;
        $this->userFName = $userFName;
        $this->userLName = $userLName;
        $this->userEmail = $userEmail;
        //$this->userPhone = $userPhone;
        //$this->userBirthday = $userBirthday;
        //$this->userAbout = $userAbout;
        $this->userStatus = 1;              //indicates user's level
    }
    */

    public function getUserID()
    {
        return $this->userID;
    }

    public function getUserRegisterTime()
    {
        return $this->userRegisterTime;
    }

    public function getUserFName()
    {
        $db = new dbClass();
        return $db->xmlToString($this->userFName);
    }

    public function setUserFName($userFName): void
    {
        $db = new dbClass();
        $this->userFName = $db->StringToXml($userFName);
    }

    public function getUserLName()
    {
        $db = new dbClass();
        return $db->xmlToString($this->userLName);
    }

    public function setUserLName($userLName): void
    {
        //$this->userLName = $userLName;
        $db = new dbClass();
        $this->userLName = $db->StringToXml($userLName);
    }

    public function getUserEmail()
    {
        return $this->userEmail;
    }

    public function setUserEmail($userEmail): void
    {
        $this->userEmail = $userEmail;
    }

    public function getUserPhone()
    {
        return $this->userPhone;
    }

    public function setUserPhone($userPhone): void
    {
        $this->userPhone = $userPhone;
    }

    public function getUserBirthday()
    {
        return $this->userBirthday;
    }

    public function setUserBirthday($userBirthday): void
    {
        $this->userBirthday = $userBirthday;
    }

    public function getUserAbout()
    {
        $db = new dbClass();
        return $db->xmlToString($this->userAbout);
    }

    public function setUserAbout($userAbout): void
    {
        //$this->userAbout = $userAbout;
        $db = new dbClass();
        $this->userAbout = $db->StringToXml($userAbout);
    }

    public function getUserStatus()
    {
        return $this->userStatus;
    }

    public function setUserStatus($userStatus): void
    {
        $this->userStatus = $userStatus;
    }


    public function getUserPassword()
    {
        return $this->userPassword;
    }

    public function setUserPassword($userPassword): void
    {
        $this->userPassword = password_hash($userPassword,PASSWORD_DEFAULT);
        //$this->userPassword=$userPassword;
    }


}