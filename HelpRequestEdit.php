<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "classes/dbClass.php";
require_once "classes/dbClassHelpRequestFunctions.php";
require_once "classes/Pet.php";
require_once "classes/HelpRequest.php";
require_once "classes/dbClassPetsFunctions.php";

$dbhr = new dbClassHelpRequestFunctions();
$helpRequest = $dbhr->getHelpRequestByID($_SESSION['helpRequestToEdit']);
unset($_SESSION['helpRequestToEdit']);

$db = new dbClass();
$signedInUser=$db->getSignedInUserData();


//date and time:
$today = date("Y-m-d H:i:s");
$today = substr($today, 0, 10);
$oneyearfromnow = (string)((int)substr($today, 0, 4)+1).substr($today, 4, 6);
//echo "<br>".$oneyearfromnow;
//-----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------if form information sent:

if (isset($_POST['helpRequestEditSent'])) {

    echo "<pre>";
    var_dump($_POST);
    echo "</pre>";

    if (isset($_POST['payment']))
        $pay = $_POST['paym'];
    else
        $pay = 0;

    $help_data = array(
        'helpStartTime' => $_POST['startdate'] . " " . $_POST['starttime'],
        'helpEndTime' => $_POST['enddate'] . " " . $_POST['endtime'],
        'helpPayment' => $_POST['helpPayment'],
        'helpAbout' => $_POST['helpAbout'],
        'helpType' => $_POST['type'],
        'userPhone' => $_POST['phone'],
        'helpStatus' => 1
    );

    $dbhr->updateHelpRequest($_POST['helpID'], $help_data);

    $_SESSION['userMessage']="Help request edited successfully.";
    $_SESSION['nextPage']="myHelpRequests.php";
    header("Location: main.php");
    die();
}



//---------------------------------------------pets in help request:
$dbp = new dbClassPetsFunctions();
$hr_petsArray = $dbp->getPetsArrayByHelpID($helpRequest->getHelpID());  //array of pets_helprequests


$petsInRequestStr="<table><tr>";
foreach ($hr_petsArray as $p_hr) //each $p_hr is a Pet_HelpRequest object
{
    $p = $db->getObjectsGeneral("pets", " WHERE `petID`='".$p_hr->getPetID()."'", "Pet");
    //$p is an array containing one pet
    $petsInRequestStr.="<td align='center'>";
    $petsInRequestStr.=$p[0]->getPetName()."<br>";
    $petsInRequestStr.=$db->getPetImageString($p[0])."<br>";
    $petsInRequestStr.="</td>";
}
$petsInRequestStr.="</tr></table>";



//---------------------------------new pets view:
$petsStr="<br><div class='card-deck'>";
foreach ($hr_petsArray as $p_hr) //each $p_hr is a Pet_HelpRequest object
{
    $p = $db->getObjectsGeneral("pets", " WHERE `petID`='".$p_hr->getPetID()."'", "Pet");
    //$p is an array containing one pet
    $image_str = "'background-image:url(".$db->getPetImageLocation($p[0]).")';";
    $petsStr.="<div class='pet-img' style=$image_str><div class='pet-name'>".$p[0]->getPetName()." - ".$p[0]->getPetType()."</div></div>";
}
$petsStr.="</div>";





//echo $helpRequest->getHelpStartTime()."<br>";
//echo substr($helpRequest->getHelpStartTime(), 0, 10);
?>

<script>    <!-- form validation -->
    function validateForm() {
        var errorMsg = "";
        //--------------------------------------------dates:
        d1 = new Date(document.getElementById("datestart").value);
        d2 = new Date(document.getElementById("dateend").value);
        if (d2<d1)
            errorMsg += "End date must be left empty, or be after start date.\n";

        //--------------------------------------------error message:
        if (errorMsg !== ""){
            alert(errorMsg);
            return false;
        }
    }
</script>

<div class="d-flex justify-content-center">
    <div class="card">
        <h5 class="card-header">Editing help request</h5>
        <div class="card-body">

<form action="HelpRequestEdit.php" method=post id="newHelpForm" onsubmit="return validateForm()" >
    <label>Pets in this help request:</label>
    <?php
    echo $petsStr;
    ?>

    <div class="form-group">
        <br><label>Address of help request:</label>
        <div class="form-row">
            <div class="col">
                <input type="text" name="address" class="form-control" value="<?php echo $helpRequest->getHelpLocCity().", ".$helpRequest->getHelpLocStreet().", ".$helpRequest->getHelpLocNum()?>" readonly>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="form-row">
            <div class="col">
                <label>Help type:</label>
                <select class="form-control" name="type">
                    <option value="Feeding">Feeding</option>
                    <option value="Walk">Walk</option>
                    <option value="House Sitting">House Sitting</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>
    </div>


    <!------------------------------------------------------------------------------------------time:   -->
    <!--
            <fieldset id="dates">
            <label class="radio-inline"><input type="radio" name="radiot" value="1" id="r1">One day</label>
            <label class="radio-inline"><input type="radio" name="radiot" value="2" id="r2">multiple days</label><br>
            </fieldset>
            -->



    <div class="form-group">

        <div class="row">
            <div class="col">
                <label>Help start date:</label>
                <input type="date" class="form-control" id="datestart" placeholder="Start date" name="startdate" value ='<?php echo substr($helpRequest->getHelpStartTime(), 0, 10)?>' min='<?php echo $today;?>' max="<?php echo $oneyearfromnow?>" required>
            </div>
            <div class="col">
                <label>Help end date:</label>
                <input type="date" class="form-control" id="dateend" placeholder="End date date" name="enddate" value ='<?php echo substr($helpRequest->getHelpEndTime(), 0, 10)?>' min='<?php echo $today;?>' max="<?php echo $oneyearfromnow?>">
                <input type="time" class="form-control" placeholder="Start time" name="starttime" hidden>
            </div>
        </div>
    </div>






    <!------------------------------------------------------------------------------------------time\   -->
    <div class="form-group">
        <label>Payment offerd:</label>
        <div class="form-row">
            <div class="col">
                <input type="text" name="helpPayment" class="form-control" value="<?php echo $helpRequest->getHelpPayment()?>">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label>Help description:</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" name="helpAbout" rows="3"><?php echo $helpRequest->getHelpAbout()?></textarea>
    </div>
    <div class="form-group">
        <label>Phone number for contact:</label>
        <div class="form-group">
            <input type="tel" class="form-control" id="exampleInputPhone1" aria-describedby="emailHelp" placeholder="Enter Phone Number"  name="phone" value="<?php echo $helpRequest->getUserPhone()?>">
        </div>
    </div>
    <input type="text" name="helpID" value="<?php echo $helpRequest->getHelpID()?>"  style="display:none">

    <button type="submit" class="btn btn-primary" name="helpRequestEditSent">Update help request</button>
</form>

        </div>
    </div>
</div>