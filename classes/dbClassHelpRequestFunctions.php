<?php

require_once "dbClass.php";
require_once "Pet.php";
require_once "User.php";
require_once "HelpRequest.php";

class dbClassHelpRequestFunctions
{
    public function getAllHelpRequests(){
        $db=new dbClass();
        return  $db->getObjectsGeneral("helprequests"," WHERE 1", "HelpRequest");
    }

    public function getAllActiveHelpRequests(){
        $db=new dbClass();
        return  $db->getObjectsGeneral("helprequests"," WHERE `helpStatus` = 1", "HelpRequest");
    }
    //----------------------------------------------------
/*
    public function getCityArray(){
        $db = new dbClass();
        $CityNameArr = array();
        $HelpRequests = $db->getObjectsDistinct("helprequests", "helpLocCity", "HelpRequest");
        foreach ($HelpRequests as $hr){
            array_push($CityNameArr, $hr->getHelpLocCity());
        }
        return $CityNameArr;
    }

    public function getHelpTypesArray(){
        $db = new dbClass();
        $HelpTypesArr = array();
        $HelpRequests = $db->getObjectsDistinct("helprequests", "helpType", "HelpRequest");
        foreach ($HelpRequests as $hr){
            if ($hr->getHelpStatus() == 1)  //only active helpRequests
                array_push($HelpTypesArr, $hr->getHelpType());
        }
        return $HelpTypesArr;
    }
*/


    public function getRefinedHelpRequests($refinementArray)
    {
        $db = new dbClass();
        $RefinedHelpRequests = array();
        $whereStatement = "";
        if (!empty($refinementArray)) {
            $arrayKeys = array_keys($refinementArray);
            $lastArrayKey = array_pop($arrayKeys);


            $count = 0;

            //--------------------------other (city, helpType):

            foreach ($refinementArray as $k => $v) {    //deleting "All" fields
                if ($v == "All") {
                    unset ($refinementArray[$k]);
                } else
                    $count++;   //counting fields that are not "All"
            }
            //echo "count = ".$count."<br>";
            if ($count > 0) {                                   //if there are refinements:
                $whereStatement .= " WHERE `helpStatus` = 1 AND ";    //only active

                foreach ($refinementArray as $k => $v) {
                    if ($k == "future") {
                        $whereStatement .= "`helpStartTime` >= CURRENT_TIMESTAMP()";
                        $count++;
                    } else {
                        $whereStatement .= "`" . $k . "` = '" . $db->stringToXml($v) . "'";
                    }
                    if (!($k == $lastArrayKey)) {
                        $whereStatement .= " AND ";
                    }
                }

                //echo $whereStatement;
                $RefinedHelpRequests = $db->getObjectsGeneral("helprequests", $whereStatement, "HelpRequest");
            }
            else {
                $dbhr = new dbClassHelpRequestFunctions();
                $RefinedHelpRequests = $dbhr->getAllActiveHelpRequests(); //contains all help Requests
            }
            return $RefinedHelpRequests;
            //return $whereStatement;
        }
    }

    public function insertHelpRequest($h)
    {
        $db = new dbClass();

        foreach ($h as $k=>$v)
        {
            if (is_string($v)){
                $h[$k] = $db->stringToXml($h[$k]);
            }
        }

        $db->insertLine("helprequests",$h);
    }

    public function updateHelpRequest($helpID, $form_data){
        $db = new dbClass();
        foreach ($form_data as $k=>$v)
        {
            if (is_string($v)){
                $form_data[$k] = $db->stringToXml($form_data[$k]);
            }
        }
        $db->updateLine("helprequests", "helpID", $helpID, $form_data);
    }

    public function getHelpRequestByID($helpID){
        $db = new dbClass();
        $helpRequest = $db->getObjectsGeneral("helprequests", " WHERE `helpID`='".$helpID."'", "HelpRequest")[0];
        return $helpRequest;
    }
    public function getHelpRequestsByUserID($userID){
        $db = new dbClass();
        $helpRequests = $db->getObjectsGeneral("helprequests", " WHERE `userID`='".$userID."' ORDER BY helpStatus, helpStartTime", "HelpRequest");
        return $helpRequests;
    }

    public function getUserbyHelpID($helpID){
        $db = new dbClass();
        $user = $db->getObjectsGeneral("users", " WHERE `helpID`='".$helpID."'", "User")[0];
        return $user;
    }

}