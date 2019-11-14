<?php

class Pet
{
    private $petID;
    private $userID;

    private $petRegisterTime;
    private $petName;
    private $petType;
    private $petBirthday;
    private $petFood;
    private $petTemper;
    private $petSex;
    private $petAbout;
    private $petStatus;



    public function getPetID()
    {
        return $this->petID;
    }

    public function setPetID($petID): void
    {
        $this->petID = $petID;
    }

    public function getPetRegisterTime()
    {
        return $this->petRegisterTime;
    }

    public function getPetName()
    {
        return $this->petName;
    }

    public function setPetName($petName): void
    {
        $this->petName = $petName;
    }

    public function getPetType()
    {
        return $this->petType;
    }

    public function setPetType($petType): void
    {
        $this->petType = $petType;
    }

    public function getPetBirthday()
    {
        return $this->petBirthday;
    }

    public function setPetBirthday($petBirthday): void
    {
        $this->petBirthday = $petBirthday;
    }

    public function getPetFood()
    {
        return $this->petFood;
    }

    public function setPetFood($petFood): void
    {
        $this->petFood = $petFood;
    }

    public function getPetTemper()
    {
        return $this->petTemper;
    }

    public function setPetTemper($petTemper): void
    {
        $this->petTemper = $petTemper;
    }

    public function getPetSex()
    {
        return $this->petSex;
    }

    public function setPetSex($petSex): void
    {
        $this->petSex = $petSex;
    }

    public function getPetAbout()
    {
        return $this->petAbout;
    }

    public function setPetAbout($petAbout): void
    {
        $this->petAbout = $petAbout;
    }

    public function getPetStatus()
    {
        return $this->petStatus;
    }

    public function setPetStatus($petStatus): void
    {
        $this->petStatus = $petStatus;
    }

    public function getUserID()
    {
        return $this->userID;
    }
    public function setUserID($userID)
    {
        $this->userID = $userID;
    }




}









