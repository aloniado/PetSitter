<?php
require_once "dbClass.php";

//class represents a HelpRequest
class HelpRequest
{
    private $helpID;
    private $userID;

    private $helpLocLat;
    private $helpLocLng;
    private $helpLocCity;
    private $helpLocStreet;
    private $helpLocNum;
    private $helpLocCountry;

    private $helpRegisterTime;
    private $helpStartTime;
    private $helpEndTime;
    private $helpPayment;
    private $helpAbout;
    private $helpType;
    private $userPhone;
    private $helpStatus;


    public function getUserPhone()
    {
        return $this->userPhone;
    }

    public function setUserPhone($userPhone): void
    {
        $this->userPhone = $userPhone;
    }

    public function getHelpID()
    {
        return $this->helpID;
    }
    public function setHelpID($helpID): void
    {
        $this->helpID = $helpID;
    }
    public function getUserID()
    {
        return $this->userID;
    }
    public function setUserID($userID): void
    {
        $this->userID = $userID;
    }
    public function getHelpLocLat()
    {
        return $this->helpLocLat;
    }
    public function setHelpLocLat($helpLocLat): void
    {
        $this->helpLocLat = $helpLocLat;
    }
    public function getHelpLocLng()
    {
        return $this->helpLocLng;
    }
    public function setHelpLocLng($helpLocLng): void
    {
        $this->helpLocLng = $helpLocLng;
    }
    public function getHelpLocCity()
    {
        $db = new dbClass();
        return $db->xmlToString($this->helpLocCity);
    }
    public function setHelpLocCity($helpLocCity): void
    {
        $this->helpLocCity = $helpLocCity;
    }
    public function getHelpLocStreet()
    {
        $db = new dbClass();
        return $db->xmlToString($this->helpLocStreet);
    }
    public function setHelpLocStreet($helpLocStreet): void
    {
        $this->helpLocStreet = $helpLocStreet;
    }
    public function getHelpLocNum()
    {
        return $this->helpLocNum;
    }
    public function setHelpLocNum($helpLocNum): void
    {
        $this->helpLocNum = $helpLocNum;
    }
    public function getHelpLocCountry()
    {
        $db = new dbClass();
        return $db->xmlToString($this->helpLocCountry);
    }
    public function setHelpLocCountry($helpLocCountry): void
    {
        $this->helpLocCountry = $helpLocCountry;
    }
    public function getHelpRegisterTime()
    {
        return $this->helpRegisterTime;
    }
    public function setHelpRegisterTime($helpRegisterTime): void
    {
        $this->helpRegisterTime = $helpRegisterTime;
    }
    public function getHelpStartTime()
    {
        return $this->helpStartTime;
    }
    public function setHelpStartTime($helpStartTime): void
    {
        $this->helpStartTime = $helpStartTime;
    }
    public function getHelpEndTime()
    {
        return $this->helpEndTime;
    }
    public function setHelpEndTime($helpEndTime): void
    {
        $this->helpEndTime = $helpEndTime;
    }
    public function getHelpPayment()
    {
        return $this->helpPayment;
    }
    public function setHelpPayment($helpPayment): void
    {
        $this->helpPayment = $helpPayment;
    }
    public function getHelpAbout()
    {
        $db = new dbClass();
        return $db->xmlToString($this->helpAbout);
    }
    public function setHelpAbout($helpAbout): void
    {
        $this->helpAbout = $helpAbout;
    }
    public function getHelpType()
    {
        return $this->helpType;
    }
    public function setHelpType($helpType): void
    {
        $this->helpType = $helpType;
    }
    public function getHelpStatus()
    {
        return $this->helpStatus;
    }
    public function setHelpStatus($helpStatus): void
    {
        $this->helpStatus = $helpStatus;
    }





}

