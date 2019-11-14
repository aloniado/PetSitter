<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "classes/dbClass.php";
require_once "classes/HelpRequest.php";
require_once "classes/Pet.php";
require_once "classes/Pet_HelpRequest.php";


require_once "classes/dbClassPetsFunctions.php";
$db = new dbClass();


//-----------------------------------------------------form handling (actual deletion):
reset($_POST);
$first_key = key($_POST);
//echo $first_key."<br>";

if (substr($first_key, 0, 7) === "deleteP")       //if verify deletion pressed
{
    $dbp = new dbClassPetsFunctions();
    $petID = substr($first_key, 7);

    echo "pet to delete: ".$first_key;
    echo "deletion verified.<br>";


    //delete help requests where pet is only pet:
    foreach($_SESSION['delete_request_id_arr'] as $k=>$v){
        $db->deleteObjectsGeneral("pets_helprequests", "WHERE `helpID`='".$v."'");
        $db->deleteObjectsGeneral("helprequests", "WHERE `helpID`='".$v."'");
    }

    //delete pet from help requests where pet is not the only one:
    foreach($_SESSION['delete_pet_from_request_id_arr'] as $k=>$v){
        $db->deleteObjectsGeneral("pets_helprequests", "WHERE `helpID`='".$v."' AND `petID`='".$petID."'");
    }

    //delete pet's image if exists:
    $dbp->deletePetImageByID($petID);


    //delete pet from db:
    $db->deleteObjectsGeneral("pets", "WHERE `petID`='".$petID."'");


    unset($_SESSION['delete_request_id_arr']);
    unset($_SESSION['delete_pet_from_request_id_arr']);
    unset($_SESSION['petToDelete']);

    $_SESSION['userMessage']="Pet deleted successfully.";
    $_SESSION['nextPage']="myPets.php";
    header("Location: main.php");
    die();


    //$_SESSION['nextPage']="myHelpRequests.php";
}
/*
else if (!isset($_SESSION['petToDelete']) || $_SESSION['petToDelete']==""){      //if pet to delete field is not set
    $_SESSION['nextPage']="myPets.php";
    header("Location: main.php");
    die();
}
*/
//---------------------------------------------------------------

unset($_SESSION['delete_request_id_arr']);
unset($_SESSION['delete_pet_from_request_id_arr']);
$tempPet = $db->getPetByID($_SESSION['petToDelete']);


echo "<h3>Deleting ".$tempPet->getPetName().":</h3>";
$petstr="<div class='card'>";
$petstr.="<table><tr><td width='30%'>";
$petstr.=$db->printPetBasic($tempPet);
$petstr.="</td><td width='30%'>";
$petstr.=$db->printPetExtra($tempPet);
$petstr.="</td><td align='center'>";
$petstr.=$db->getPetImageString($tempPet);
$petstr.="</td></tr></table></div><br>";
echo $petstr;

//checking if Pet appears on help requests:
$P_HR_Arr = $db->getObjectsGeneral("pets_helprequests", " WHERE `petID`='".$tempPet->getPetID()."'", "Pet_HelpRequest");
if ($P_HR_Arr != null){   //$P_HR holds all Pet_HelpRequest where pet to delete appears on
    $delete_request_id_arr=array();
    $delete_pet_from_request_id_arr=array();
    echo "<h4>".$tempPet->getPetName()." appears on these Help Requests:</h4>";
    /*
    echo "<pre>";
    var_dump($P_HR_Arr);
    echo "</pre>";
    */
    foreach ($P_HR_Arr as $phr){
    $HelpRequest = $db->getObjectsGeneral("helprequests", " WHERE `helpID`='".$phr->getHelpID()."'", "HelpRequest");
        $k = $HelpRequest[0];    //$k is now HelpRequest

        //todo: get and show pets in each HelpRequest!!

        $str="<div class='card'>";
        //$str.="<table><tr><td width='30%'>";
        $str.="Help ID: ".$k->getHelpID()."<br>";
        $str.="Type: ".$k->getHelpType()."<br>";
        $str.="Starts on: ".$k->getHelpStartTime()."<br>";
        $str.="Ends on: ".$k->getHelpEndTime()."<br>";
        $str.="Location: ".$k->getHelpLocCity().", ".$k->getHelpLocStreet()."<br>";
        $str.="Payment offered: ₪".$k->getHelpPayment()."<br>";
        //$str.="</td><td align='center'>";
        //$str.=$petsInRequest;
        //$str.="</td></tr></table>";
        $str.="</div>";

        echo $str;

        $p_hr_byHelpID = $db->getObjectsGeneral("pets_helprequests", " WHERE `helpID`='".$k->getHelpID()."'", "Pet_HelpRequest");
        /*
        echo "<pre>";
        var_dump($p_hr_byHelpID);
        echo "</pre>";
        */
        if (sizeof($p_hr_byHelpID)==1){
            echo "*Help request will be deleted.<br><br>";
            array_push($delete_request_id_arr, $k->getHelpID());
        }
        else if (sizeof($p_hr_byHelpID)>1){
            echo "*".$tempPet->getPetName()." will be deleted from request.<br><br>";
            array_push($delete_pet_from_request_id_arr, $k->getHelpID());
        }
    }

    $_SESSION['delete_request_id_arr']=$delete_request_id_arr;
    $_SESSION['delete_pet_from_request_id_arr']=$delete_pet_from_request_id_arr;
}
else
    {
    echo "pet is clear for deletion";
}
echo "<br><form action='petDelete.php' method=post><button type='submit' class='btn btn-primary' name='"."deleteP".$tempPet->getPetID()."'>Verify Deletion</button></form><br>";




