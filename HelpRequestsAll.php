<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//unset($_SESSION['refinement']);   //del
require_once "classes/dbClass.php";
require_once "classes/Pet.php";
require_once "classes/HelpRequest.php";
require_once "classes/Pet_HelpRequest.php";
require_once "classes/dbClassHelpRequestFunctions.php";
require_once "classes/dbClassInterested.php";
require_once "classes/User.php";
require_once "classes/dbClassUserFunctions.php";
$db = new dbClass();
//------------------------------------------------------
//function fills comboBoxes with items from string array 'items'
function selectItems($items, $selectedoption)
{
    $text = "";
    $text.="<option value='All'>All</option>\n";
    foreach($items as $k=>$v)
    {
        if ($v != "")   //skipping empty line at end of files
        {
            $text.="<option value='$v'";
            if ($v == $selectedoption)
                $text.=" selected";
            $text.=">$v</option>\n";
        }
    }
    return $text;
}
//-----------------------form variables:
$futurechecked="";
$form_helpType="All";
$form_helpLocCity="All";




reset($_POST);
$first_key = key($_POST);

//--------------------------------------------------------------------- view button is pressed:
if (substr($first_key, 0, 4) === "view")
{
    $first_key = substr($first_key, 4);
    //echo $first_key;

    $_SESSION['helpRequestView']=$first_key;
    $_SESSION['nextPage']="helpRequestView.php";
    header("Location: main.php");
    die();
}
//--------------------------------------------------------------if "show on map" clicked:
if (isset($_POST['ShowOnMap']))
{
    $_SESSION['nextPage']="maps/markersOnMap.php";  //map page
    header("Location: main.php");
    die();
}
//------------------------------------------------------1.refinement form was sent: inserting variables to $_SESSION:
$dbhr = new dbClassHelpRequestFunctions();

if (isset($_POST['sort'])) {

    $refinementArray = array();
    if (isset($_POST['city'])) {
        $refinementArray['helpLocCity'] = $_POST['city'];
        //echo "<br>city = ".$_POST['city']."<br>";
    }
    if (isset($_POST['helpType']) && $_POST['helpType'] != "All") {
        $refinementArray['helpType'] = $_POST['helpType'];
        //echo "helpType = ".$_POST['helpType']."<br>";
    }
    if (isset($_POST['future']))
    {
        $refinementArray['future'] = "";    //indicates that user wants to see past requests
        //echo "future = ".$_POST['future']."<br>";
    }


    //echo "sorted = '".$dbhr->getRefinedHelpRequests($refinementArray)."'<br>";

    $helpRequestsArr = null;

    $_SESSION['refinement'] = $refinementArray;
    $_SESSION['nextPage']="HelpRequestsAll.php";
    header("Location: main.php");
    die();
}
//----------------------------------------------------------------------2.getting helpRequests according to refinement:
else if (isset($_SESSION['refinement']))
{

    $helpRequestsArr = $dbhr->getRefinedHelpRequests($_SESSION['refinement']);
    /*
    echo "*************<br><pre>";
    var_dump($helpRequestsArr);
    echo "</pre>";
*/
    //-------filling form with current preferences:
    $refinements = $_SESSION['refinement']; //saving for correct filling in form

    if (isset($refinements['future'])){
        $futurechecked="checked";
    }
    if (isset($refinements['helpLocCity'])){
        $form_helpLocCity = $refinements['helpLocCity'];
    }
    if (isset($refinements['helpType'])){
        $form_helpType = $refinements['helpType'];
    }

    unset($_SESSION['refinement']);
}
else{
    //no refinement sent. showing all:
    //$helpRequestsArr = $dbhr->getAllHelpRequests(); //contains all help Requests
    $helpRequestsArr = $dbhr->getAllActiveHelpRequests(); //contains all help Requests


}

//---------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------Sorting options form:

//filling options for selection box:
$cityNameArr = array();
$helpTypeArr = array();
foreach ($dbhr->getAllActiveHelpRequests() as $k=>$v)
{
    array_push($cityNameArr, $v->getHelpLocCity());
    array_push($helpTypeArr, $v->getHelpType());
}
$cityNameArr = array_unique($cityNameArr);
$helpTypeArr = array_unique($helpTypeArr);
//----------------------------------------------------------

?>
<div class='d-flex justify-content-center'>
    <div class='card' id='helpRequests-refinement'>
        <h5 class="card-header">Help requests</h5>
    <form action="HelpRequestsAll.php" method="post" enctype="multipart/form-data">
        <div class="form-group" id='helpRequests-refinement-text'>
            <div class="form-row">
                <div class="col">
                    City:
                </div>
                <div class="col">
                    Help type:
                </div>

            </div>
            <div class="form-row">
                <div class="col">
                    <select class="form-control" name='city'><?=selectItems($cityNameArr, $form_helpLocCity)?></select>
                </div>
                <div class="col">
                    <select class="form-control" name='helpType'><?=selectItems($helpTypeArr, $form_helpType)?></select>
                </div>
            </div>
            <br>
            <div class="form-group">
            <div class="form-row">
                <div class="col">
                    <input type="checkbox"  name="future" value="future" <?=$futurechecked?>>
                    <label>Only future requests</label>
                </div>
            </div>
            </div>
            <button type="submit" class="btn btn-primary" name="sort">Refine</button>
            <button type="submit" class="btn btn-primary" name="ShowOnMap">Show (All) On Map</button>
        </div>


    </form>
        <?php
        echo sizeof($helpRequestsArr)." help requests found";
        ?>
    </div>

</div>

<?php
//------------checking if user have a phone number:
$dbu = new dbClassUserFunctions();
$signedInUserPhone = $dbu->getUserByID($_SESSION['user'])->getUserPhone();
if ($signedInUserPhone == "")
    echo "<br><div class='userMessageRed'>Your profile is not complete. Please edit your profile and enter a phone number.<br> Without a phone number you won't be able to offer help.</div>";


//--------------------------------------------------------------printing help requests:
/*
echo "<pre>";
var_dump($myHR_Array);
echo "</pre>";
*/
//echo "<h3>All Help Requests:</h3>";
echo "<br>";
if ($helpRequestsArr== null)
    echo "";
else
{
    foreach ($helpRequestsArr as $k) //each %k is a helpRequest
    {

        //getting array of pets_helprequests for each help:
        $HRpetsArray = $db->getObjectsGeneral("pets_helprequests", " WHERE `helpID`='".$k->getHelpID()."'", "Pet_HelpRequest");
        if ($HRpetsArray == null){
            $petsInRequest="No pets in request";
        }
        else {
            $petsInRequest="<div class='card-deck'>";
            foreach ($HRpetsArray as $p_hr) //each $p_hr is a Pet_HelpRequest object
            {
                $p = $db->getObjectsGeneral("pets", " WHERE `petID`='".$p_hr->getPetID()."'", "Pet");
                //$p is an array containing one pet
                $image_str = "'background-image:url(".$db->getPetImageLocation($p[0]).")';";
                $petsInRequest.="<div class='pet-img' style=$image_str><div class='pet-name'>".$p[0]->getPetName()." - ".$p[0]->getPetType()."</div></div>";
            }
            $petsInRequest.="</div>";
        }
        //getting User of request:
        $u = $db->getObjectsGeneral("users", " WHERE `userID`='".$k->getUserID()."'", "User")[0];

        $userName = $u->getUserFName()." ".$u->getUserLName();



        //------------------------------------------------------------------
        $str="";
        $str.="<div class='d-flex justify-content-center'>";


        $str.="<div class='card-group' id='helpRequestCard'>";
            $str.="<div class='card text-center'  id='helpRequestCard-request'>";
            $str.="<div class='card-body'>";
            $str.="<h5 class='card-title'>".$k->getHelpType()."</h5>";
            $str.="<p class='card-text'>";
            $helpTimeStr="";

            if ($k->getHelpEndTime() == "0000-00-00 00:00:00"){
                if ($k->getHelpStartTime() == "0000-00-00 00:00:00"){
                    $helpTimeStr .= "<b>Time not specified</b><br>";
                }
                else
                    $helpTimeStr .= "<b>One day help:</b> ".substr($k->getHelpStartTime(), 0, 10)."<br>";
            }
            else
                $helpTimeStr .= "<b>Time:</b> ".substr($k->getHelpStartTime(), 0, 10)." - ".substr($k->getHelpEndTime(), 0, 10)."<br>";
            $str.=$helpTimeStr;

            $str.="<b>Location:</b> ".$k->getHelpLocCity().", ".$k->getHelpLocStreet()."<br>";
            $str.="<b>Payment offered:</b> ₪".$k->getHelpPayment()."<br>";
            $str.="<b>Request by: </b>".$userName."";
            $str.="<form action='myHelpRequests.php' method=post><button type='submit' class='btn btn-primary' name='"."view".$k->getHelpID()."'>View</button>";
            if ($k->getUserID() == $_SESSION['user'])
                $str .= "  <button type='submit' class='btn btn-primary' name='" . "editHelp" . $k->getHelpID() . "'>Edit</button>";
            $str.="</form>";
            /*
            if ($k->getUserID()!=$_SESSION['user'] && $signedInUserPhone != "")
            {
                $likeStr="Like";
                $dbi = new dbClassInterested();
                $int = $dbi->GetInterestedByIDs($_SESSION['user'],$k->getHelpID());
                if (isset($int[0]))
                {
                    //already liked:
                    $likeStr="Unlike";
                }
                $str.="<a href='#' onclick='likeFunction()' class='likebtn' id='".$k->getHelpID()."-".$_SESSION['user']."-".$likeStr."' title='Like a help request to leave your details to the person who needs help'>".$likeStr."</a>";
                //$str.="<button type='button' class='btn btn-secondary' data-toggle='tooltip' data-placement='top' title='Tooltip on top'> Tooltip on top </button>";
                $str.="</td>";

            }
*/

        //------------------------------------------------------------------------------------ new Like:
        $dbu = new dbClassUserFunctions();
        if ($k->getUserID() != $_SESSION['user'] && $dbu->getUserByID($_SESSION['user'])->getUserPhone() != "")
        {
            $dbi = new dbClassInterested();
            $is_interested = $dbi->GetInterestedByIDs($_SESSION['user'], $k->getHelpID());
            //var_dump($is_interested);
            if ($is_interested == null)
            {
                $likeButton = "'background-image:url(images/like-empty.svg)';";
                $tooltipText = "Liking this help request will leave your details to the publisher so he can get back to you";
                $liked = "no";
            }
            else
            {
                $likeButton = "'background-image:url(images/like-full.svg)';";
                $tooltipText = "You've already liked this help request! The publisher has your details";
                $liked = "yes";
            }
            $str.= "<div id='".$k->getHelpID()."-".$_SESSION['user']."-".$liked."' style=$likeButton class='like_pressed likeHeartAllRequests'><span class='like-tooltip-text' id='tooltip-text-".$k->getHelpID()."'>".$tooltipText."</span>";
            $str.= "</div>";

        }
        //---------------------------------------------------------------------------------------

            $str.="</p></div>";


            $str.="</div>";
            //$str.="</div>";
        $str.="<div class='card text-center' id='helpRequestCard-pets'>";
        $str.="<div class='card-body mx-auto'>";
        $str.=$petsInRequest;

        $str.="</div>";
        $str.="</div>";
        $str.="</div>";
        $str.="</div><br>";

        echo $str;


    }


}
?>


<script type="text/javascript">
    $('div.like_pressed').click(function(){
        var target = event.target || event.srcElement;
        var id = target.id;
        var strArr = id.split("-");
        //alert("userID: "+strArr[1]+"  helpID: "+strArr[0]);

        $.ajax({
            type: "POST",
            data : { user : strArr[1], help : strArr[0]},
            url: "scripts/HelpRequestLikeAndUnlike.php",
            success: function(msg){
                //alert("ok");
                //window.location = "main.php";
                var status = strArr[2];
                if (status === "yes")
                {
                    //alert("need to unlike");
                    document.getElementById(strArr[0]+"-"+strArr[1]+"-yes").style.backgroundImage="url(images/like-empty.svg)";
                    document.getElementById(strArr[0]+"-"+strArr[1]+"-yes").id=strArr[0]+"-"+strArr[1]+"-no";
                    document.getElementById("tooltip-text-"+strArr[0]).innerHTML = "Liking this help request will leave your details to the publisher so he can get back to you";
                }
                else
                {
                    //alert("need to like");
                    document.getElementById(strArr[0]+"-"+strArr[1]+"-no").style.backgroundImage="url(images/like-full.svg)";
                    document.getElementById(strArr[0]+"-"+strArr[1]+"-no").id=strArr[0]+"-"+strArr[1]+"-yes";
                    document.getElementById("tooltip-text-"+strArr[0]).innerHTML = "You've already liked this help request! The publisher has your details";
                }

            }
        });
    });

</script>
