<?php


class Pet_HelpRequest
{
    private $petID;
    private $helpID;
    private $userID;
    private $recordRegisterTime;

    /**
     * @return mixed
     */
    public function getPetID()
    {
        return $this->petID;
    }

    /**
     * @param mixed $petID
     */
    public function setPetID($petID): void
    {
        $this->petID = $petID;
    }

    /**
     * @return mixed
     */
    public function getHelpID()
    {
        return $this->helpID;
    }

    /**
     * @param mixed $helpID
     */
    public function setHelpID($helpID): void
    {
        $this->helpID = $helpID;
    }

    /**
     * @return mixed
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * @param mixed $userID
     */
    public function setUserID($userID): void
    {
        $this->userID = $userID;
    }

    /**
     * @return mixed
     */
    public function getRecordRegisterTime()
    {
        return $this->recordRegisterTime;
    }

    /**
     * @param mixed $recordRegisterTime
     */
    public function setRecordRegisterTime($recordRegisterTime): void
    {
        $this->recordRegisterTime = $recordRegisterTime;
    }


}