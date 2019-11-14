<?php
/**
 * Created by PhpStorm.
 * User: aloni
 * Date: 8/15/2018
 * Time: 1:56 PM
 */
require_once "dbClass.php";
class dbClassInterested
{
    public function GetInterestedByIDs($user, $help)
    {
        $db = new dbClass();
        $int = $db->getObjectsGeneral("interested", " WHERE `interestedUserID`='".$user."' AND `helpID`='".$help."'", "Interested");

        return $int;
    }
    public function GetInterestedByhelpID($help)
    {
        $db = new dbClass();
        $int = $db->getObjectsGeneral("interested", " WHERE `helpID`='".$help."'", "Interested");

        return $int;
    }

    public function insertInterestedRecord($help, $user)
    {
        $db = new dbClass();
        $int = $db->getObjectsGeneral("interested", " WHERE `interestedUserID`='".$user."' AND `helpID`='".$help."'", "Interested");
        if ($int == null)
        {
            $data_arr = array(
                'helpID' => (int)$help,
                'interestedUserID' => (int)$user,
                'status' => 1,
            );
            $db->insertLine("interested",$data_arr);
        }
    }

    public function RemoveInterestedRecord($help, $user)    //removes specific single interested record
    {
        $db = new dbClass();
        $db->deleteLineGeneral("interested"," WHERE `interestedUserID`='".$user."' AND `helpID`='".$help."'");

    }

    public function RemoveInterestedRecordByHelpID($help)   //removes all interested records prior to help request deletion
    {
        $db = new dbClass();
        $db->deleteLineGeneral("interested"," WHERE `helpID`='".$help."'");

    }
}