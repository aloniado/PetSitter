<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//unset($_SESSION['refinement']);   //del
require_once "../classes/dbClass.php";
require_once "../classes/Pet.php";
require_once "../classes/HelpRequest.php";
require_once "../classes/Pet_HelpRequest.php";
require_once "../classes/dbClassHelpRequestFunctions.php";
require_once "../classes/dbClassPetsFunctions.php";


//--------------------------------------------------------
$db = new dbClass();
$dbhr = new dbClassHelpRequestFunctions();
$helpRequestsArr = $dbhr->getAllActiveHelpRequests();


//----------

header("Content-type: text/xml");

// Start XML file, echo parent node
echo "<?xml version='1.0' encoding='UTF-8'?>";
echo '<markers>';
$ind=0;
// Iterate through the rows, printing XML nodes for each

$dbp = new dbClassPetsFunctions();
foreach ($helpRequestsArr as $k=>$v)
{    // Add to XML document node


    $petTypesString = "";
    $petsInRequestArr = $dbp->getPetsArrayByHelpID($v->getHelpID());

    foreach ($petsInRequestArr as $k1=>$v1){
        $petTypesString .= "".$v1->getPetType().", ";
    }

    echo '<marker ';
    echo 'id="' . $v->getHelpID() . '" ';
    echo 'name="' . $v->getHelpType().": ".$petTypesString.'" ';
    //echo 'address="' . "Help ID: ".$link.'" ';
    echo 'address="' . "ID: ".$v->getHelpID() .'" ';
    echo 'lat="' . $v->getHelpLocLat() . '" ';
    echo 'lng="' . $v->getHelpLocLng() . '" ';
    echo 'type="' . str_replace(' ', '', $v->getHelpType()) . '" '; //for label
    echo '/>';
    $ind = $ind + 1;
}

// End XML file
echo '</markers>';

?>