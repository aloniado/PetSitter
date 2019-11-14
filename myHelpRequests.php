<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "classes/dbClass.php";
require_once "classes/dbClassInterested.php";
require_once "classes/Pet.php";
require_once "classes/Interested.php";
require_once "classes/HelpRequest.php";
require_once "classes/Pet_HelpRequest.php";
require_once "classes/dbClassUserFunctions.php";
require_once "classes/dbClassRankingFunctions.php";
$db = new dbClass();

//-----------------------------------------------------form handling:
reset($_POST);
$first_key = key($_POST);   //command + ID of request
//echo $first_key."<br>";

if (isset($_POST['addhr']))                                    //if add help request clicked
{
    $_SESSION['nextPage']="helpRequestAdd.php";
    header("Location: main.php");
    die();
}
else if (substr($first_key, 0, 6) === "delete")       //if delete button is pressed
{
    $first_key = substr($first_key, 6);
    echo $first_key;


    //delete from table pets_helprequests:
    $db->deleteLine("pets_helprequests", "helpID", $first_key);
    //delete interested:
    $dbi=new dbClassInterested();
    $dbi->RemoveInterestedRecordByHelpID($first_key);
    //delete from table helprequests:
    $db->deleteLine("helprequests", "helpID", $first_key);

    $_SESSION['nextPage']="myHelpRequests.php";
    header("Location: main.php");
    die();
}
else if (substr($first_key, 0, 4) === "view")       //if view button is pressed
{
    $first_key = substr($first_key, 4);
    echo $first_key;

    $_SESSION['helpRequestView']=$first_key;
    $_SESSION['nextPage']="helpRequestView.php";
    header("Location: main.php");
    die();
}
else if (substr($first_key, 0, 8) === "editHelp")       //if view button is pressed
{
    $first_key = substr($first_key, 8);
    //echo $first_key;

    $_SESSION['helpRequestToEdit']=$first_key;
    $_SESSION['nextPage']="helpRequestEdit.php";
    header("Location: main.php");
    die();
}
else if (substr($first_key, 0, 4) === "rate")       //if dismiss and rate button is pressed
{
    $first_key = substr($first_key, 4);
    $keysArr=explode("-",$first_key);
    $helpID=$keysArr[1];
    $userToBeRanked=$keysArr[0];

    $_SESSION['RankedUser']=$keysArr[0];
    $_SESSION['RankedOnHelp']=$keysArr[1];

    $_SESSION['nextPage']="UserRanking.php";
    header("Location: main.php");
    die();
}
else if (substr($first_key, 0, 17) === "UserProfileToShow")       //if a name was clicked -> refering to user's page
{
    $first_key = substr($first_key, 17);
    echo "<br>userID clicked: ".$first_key;

    $_SESSION['UserProfileToShow']=$first_key;
    $_SESSION['nextPage']="ProfileUser.php";

    header("Location: main.php");
    die();
}

//---------------------------------------------------------------------
$dbhr = new dbClassHelpRequestFunctions();
$myHR_Array = $dbhr->getHelpRequestsByUserID($_SESSION['user']);


echo "<div class='card' id='userProfileCard'>";
echo "<h5 class='card-header'>My help requests</h5>";
echo "<div class='card-body'>";
echo "<form action='myHelpRequests.php' method=post>";
echo "<button type='submit' class='btn btn-primary' name='addhr'>Add Help Request</button>";
echo "</form>";
if ($myHR_Array == null)
    echo "<br>Add a help request and get some petSitting :)";

echo "</div></div>";


//$myHR_Array = $db->getObjectsGeneral("helprequests"," WHERE `userID`='".$_SESSION['user']."'","HelpRequest");



/*
echo "<pre>";
var_dump($myHR_Array);
echo "</pre>";
*/

if ($myHR_Array == null)
    echo "";
else {
    foreach ($myHR_Array as $k) //each %k is a helpRequest
    {
        echo "<div id='myHelpRequestsContainer'>";

        //getting array of pets_helprequests for each help:
        $HRpetsArray = $db->getObjectsGeneral("pets_helprequests", " WHERE `helpID`='" . $k->getHelpID() . "'", "Pet_HelpRequest");

        //-------------------------------------new card:
        if ($k->getHelpStatus() == 1)
            $cardCssId = "helpRequestCard";
        else
            $cardCssId = "helpRequestCard_Done";


        $str = "";
        $str .= "<div class='d-flex justify-content-center'>";
        $str .= "<div class='card-group' id='helpRequestCard'>";
        $str .= "<div class='card text-center' id='".$cardCssId."'>";
        $str .= "<div class='card-body'>";
        $str .= "<h5 class='card-title'>" . $k->getHelpType() . "</h5>";
        $str .= "<p class='card-text'>";
        //$str .= "Help ID: " . $k->getHelpID() . "<br>";
        $helpTimeStr = "";

        if ($k->getHelpEndTime() == "0000-00-00 00:00:00") {
            if ($k->getHelpStartTime() == "0000-00-00 00:00:00") {
                $helpTimeStr .= "<b>Time not specified</b><br>";
            } else
                $helpTimeStr .= "<b>One day help:</b> " . substr($k->getHelpStartTime(), 0, 10) . "<br>";
        } else
            $helpTimeStr .= "<b>Time:</b> " . substr($k->getHelpStartTime(), 0, 10) . " - " . substr($k->getHelpEndTime(), 0, 10) . "<br>";
        $str .= $helpTimeStr;

        $str .= "<b>Location:</b> " . $k->getHelpLocCity() . ", " . $k->getHelpLocStreet() . "<br>";
        $str .= "<b>Payment offered:</b> ₪" . $k->getHelpPayment() . "";

        $str .= "<form action='myHelpRequests.php' method=post>";
        if ($k->getHelpStatus() == 1) {
            $str .= "<button type='submit' class='btn btn-primary' name='" . "delete" . $k->getHelpID() . "'>Delete</button>  ";
            $str .= "<button type='submit' class='btn btn-primary' name='" . "editHelp" . $k->getHelpID() . "'>Edit</button>  ";
        }
        $str .= "<button type='submit' class='btn btn-primary' name='" . "view" . $k->getHelpID() . "'>View</button></form>";


        //interested:
        //-----------------------------------------------------------------------Interested:
        $dbi = new dbClassInterested();
        $interestedArr = $dbi->GetInterestedByhelpID($k->getHelpID());
        if ($k->getHelpStatus() == 1)
        {
            if (!empty($interestedArr)) {        //there are interested people:
                $str .= "<br><b>People are interested, and left you their details:</b><br>";
                $dbu = new dbClassUserFunctions();
                foreach ($interestedArr as $k1 => $v) {
                    $user = $dbu->getUserByID($v->getInterestedUserID());
                    $str .= "<div class='card text-center'>";
                    $str .= "<div class='card-body'>";
                    $str .= $user->getUserFName() . " " . $user->getUserLName() . " - " . $user->getUserPhone();

                    $str .= "<form action='myHelpRequests.php' method=post><button type='submit' class='btn btn-primary' name='UserProfileToShow" . $user->getUserID() . "'>" . $user->getUserFName() . "'s Profile</button>  ";
                    $str .= "<button type='submit' class='btn btn-primary' name='rate" . $user->getUserID() . "-" . $v->getHelpID() . "'>Mark as done by " . $user->getUserFName() . "</button>  </form>";
                    $str.="</div></div>";
                }
                $str .= "<small id='emailHelp' class='form-text text-muted'>you can give them a call to set up a meeting :)</small>";
            }
            else
            {
                $str .= "no one is interested yet<br>";
            }

        }
        else if ($k->getHelpStatus() == 2)//help request is done
        {
            $dbr = new dbClassRankingFunctions();
            $dbu = new dbClassUserFunctions();
            $userWhoDoneHelp = $dbu->getUserByID($dbr->getRankedUserIdByHelpID($k->getHelpID()));

            $str .= "<form action='myHelpRequests.php' method=post><button type='submit' class='buttonLooksLikeLink' name='UserProfileToShow" . $userWhoDoneHelp->getUserID() . "'><b>You've marked this help request as done by " . $userWhoDoneHelp->getUserFName() . " " . $userWhoDoneHelp->getUserLName() . " (" . $userWhoDoneHelp->getUserPhone() . ")" . "</b></button></form>";
            $str .= "";
        }

        $str .= "</p></div>";
        $str .= "</div></div>";




        //Pets card:
        $str .= "<div class='card text-center' id='".$cardCssId."'>";
        $str .= "<div class='card-body'>";

        //getting array of pets_helprequests for each help:
        $HRpetsArray = $db->getObjectsGeneral("pets_helprequests", " WHERE `helpID`='" . $k->getHelpID() . "'", "Pet_HelpRequest");
        if ($HRpetsArray == null) {
            $petsInRequest = "No pets in request";
        }
        else
        {
            $petsInRequest = "<div class='card-deck'>";
            foreach ($HRpetsArray as $p_hr) //each $p_hr is a Pet_HelpRequest object
            {
                $p = $db->getObjectsGeneral("pets", " WHERE `petID`='" . $p_hr->getPetID() . "'", "Pet");
                //$p is an array containing one pet
                /*
                $petsInRequest .= "<div id='petCardinProfile' class='mt-lg-auto'>";
                $petsInRequest .= "<img class='card-img-top' src='" . $db->getPetImageLocation($p[0]) . "' alt='" . $p[0]->getPetName() . "'>";
                //$petsStr.="<div class='card-body'>";
                $petsInRequest .= "<div class='card-footer'><small class='text-muted'>" . $p[0]->getPetName() . "</small></div>";
                $petsInRequest .= "</div>";
                */

                $image_str = "'background-image:url(".$db->getPetImageLocation($p[0]).")';";
                $petsInRequest.="<div class='pet-img' style=$image_str><div class='pet-name'>".$p[0]->getPetName()." - ".$p[0]->getPetType()."</div></div>";

            }
            $petsInRequest .= "</div>";
            $str .= $petsInRequest;
            $str .= "</div>";
            $str .= "</div>";
        }
        $str .= "</div>";
        $str .= "</div><br></div>";

        echo $str;
        echo "</div>";
    }


}

