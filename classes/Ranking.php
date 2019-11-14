<?php
require_once "dbClass.php";
class Ranking
{
    private $rankID;
    private $rankingUserID;
    private $rankedUserID;
    private $helpID;
    private $rankRank;
    private $rankAbout;

    /**
     * @return mixed
     */
    public function getRankID()
    {
        return $this->rankID;
    }

    /**
     * @param mixed $rankID
     */
    public function setRankID($rankID): void
    {
        $this->rankID = $rankID;
    }

    /**
     * @return mixed
     */
    public function getRankingUserID()
    {
        return $this->rankingUserID;
    }

    /**
     * @param mixed $rankingUserID
     */
    public function setRankingUserID($rankingUserID): void
    {
        $this->rankingUserID = $rankingUserID;
    }

    /**
     * @return mixed
     */
    public function getRankedUserID()
    {
        return $this->rankedUserID;
    }

    /**
     * @param mixed $rankedUserID
     */
    public function setRankedUserID($rankedUserID): void
    {
        $this->rankedUserID = $rankedUserID;
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
    public function getRankRank()
    {
        return $this->rankRank;
    }

    /**
     * @param mixed $rankRank
     */
    public function setRankRank($rankRank): void
    {
        $this->rankRank = $rankRank;
    }

    /**
     * @return mixed
     */
    public function getRankAbout()
    {
        $db = new dbClass();
        //return $this->rankAbout;
        return $db->xmlToString($this->rankAbout);
    }

    /**
     * @param mixed $rankAbout
     */
    public function setRankAbout($rankAbout): void
    {
        $this->rankAbout = $rankAbout;
    }



}