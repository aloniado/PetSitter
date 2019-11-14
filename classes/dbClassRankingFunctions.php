<?php
/**
 * Created by PhpStorm.
 * User: aloni
 * Date: 8/16/2018
 * Time: 10:33 AM
 */
require_once "dbClass.php";
require_once "dbClassHelpRequestFunctions.php";

class dbClassRankingFunctions
{
    public function insertRanking($data_arr){
        $db = new dbClass();

        foreach ($data_arr as $k=>$v)
        {
            if (is_string($v)){
                $data_arr[$k] = $db->stringToXml($data_arr[$k]);
            }
        }
        $db->insertLine("rankings", $data_arr);

        $changes_array = array(
                'helpStatus' => 2,
            );
        $db->updateLine("helprequests", "helpID", $data_arr['helpID'], $changes_array);  //setting helprequest status to 2: done
    }

    public function getUserRankByID($userID){

        $db = new dbClass();

        $sum=$count=0;
        $ranksArr = $db->getObjectsGeneral("rankings", " WHERE `rankedUserID` = '".$userID."'", "Ranking");
        foreach ($ranksArr as $k=>$v){
            $sum+=$v->getRankRank();
            $count++;
        }
        if ($count!=0)
            return (float)$sum/$count;
        else
            return null;

    }

    public function getRankedUserIdByHelpID($helpID){
        $db = new dbClass();
        $userID=null;
        $ranksArr = $db->getObjectsGeneral("rankings", " WHERE `helpID` = '".$helpID."'", "Ranking");
        if (!empty($ranksArr)){
            $userID = $ranksArr[0]->getRankedUserID();
        }
        return $userID;
    }
}