<?php

class Interested
{
    private $helpID;
    private $interestedUserID;
    private $status;

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
    public function getInterestedUserID()
    {
        return $this->interestedUserID;
    }

    /**
     * @param mixed $interestedUserID
     */
    public function setInterestedUserID($interestedUserID): void
    {
        $this->interestedUserID = $interestedUserID;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }




}