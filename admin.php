<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "classes/dbClass.php";
require_once "classes/User.php";
require_once "classes/dbClassPetsFunctions.php";


$db = new dbClass();
//---------------------------------------------------------------------
reset($_POST);
$first_key = key($_POST);
if (substr($first_key, 0, 13) === "RemovePetType")       //deleting pet type:
{
    $first_key = substr($first_key, 13);
    //echo "<br>remove type clicked: ".$first_key;
    $dbp = new dbClassPetsFunctions();
    $dbp->removePetType($first_key);
    $_SESSION['nextPage']="admin.php";
    header("Location: main.php");
    die();
}
if (isset($_POST['petTypeAdd']))
{
    //upload image:
    $dbp = new dbClassPetsFunctions();
    if ($dbp->loadPetTypeImage($_POST['ptype']) == "ok"){
        //adding pet's type to file:
        $dbp->addPetType($_POST['ptype']);

        $_SESSION['userMessage']="Pet type added";
    }
    else
    {
        $_SESSION['userMessage']="Problem adding pet type";
    }
    $_SESSION['nextPage']="admin.php";
    header("Location: main.php");
    die();
}

//----------------------------------------------------------------------------------------PDF CREATION:


echo "<div class='d-flex justify-content-center'>";
echo "<div class='card'>";
echo "<h5 class='card-header'>Statistics</h5>";
echo "<div class='card-body'>";

$users_arr = $db->getObjectsGeneral("users", " WHERE 1 ORDER BY userStatus", "User");
echo "<br><h4>Registered Users:</h4>";
if ($users_arr == null){
    $str="No registered users in DB";
}
else
{
    $count=0;
    $str = "<table border='1'>";
    $str.="<tr>";
    $str.="<td><b>#</b></td>";
    $str.="<td><b>Name</b></td>";
    $str.="<td><b>Email</b></td>";
    $str.="<td><b>Phone</b></td>";
    $str.="<td><b>Register Time</b></td>";
    $str.="<td><b>Status</b></td>";
    $str.="</tr>";
    foreach ($users_arr as $k)  //each $k is a user
    {
        $count++;
        $str.="<tr>";
        $str.="<td>".$count."</td>";
        $str.="<td>".$k->getUserFName()." ".$k->getUserLName()."</td>";
        $str.="<td>".$k->getUserEmail()."</td>";
        $str.="<td>".$k->getUserPhone()."</td>";
        $str.="<td>".$k->getUserRegisterTime()."</td>";
        $str.="<td>".$k->getUserStatus()."</td>";
        $str.="</tr>";
    }
    $str.= "</table>";
    $str.= "total: ".$count;
}

echo $str;
echo "<br>";
echo "<br><form id='form1' onsubmit='formonsubmit();' method='get' action='classes/pdfReports/myPDF.php' target='_new'>";
echo "<input type='submit' value='Create PDF Report' class='btn btn-primary'/></form> ";
echo "*Please allow pop-ups to see report";

echo "</div></div></div>";



//---------------------------------------------pet types:

$str="";
$str.=  "<div class='d-flex justify-content-center'>";
$str.=  "<div class='card'>";
$str.=  "<h5 class='card-header'>Pet types</h5>";
$str.=  "<div class='card-body'>";

$petTypes = file_get_contents("classes/petTypes.txt");   //$words now contains text from file
$words_arr = explode("\n",$petTypes);
$str.="<form action='admin.php' method=post>";
foreach ($words_arr as $k=>$v)
{
    if ($v != "")
        $str.= $v."  <button type='submit' class='buttonLooksLikeLink' name='RemovePetType".$v."'>(X)</button><br>";
}
$str.="</form>";

$str.="<form action='admin.php' method='post' enctype='multipart/form-data'>";
$str.="<div class='form-row'>";
$str.="<div class='col'>";
$str.="<input type='text' class='form-control form-control' placeholder='Type' name='ptype' pattern='[A-Za-z]{2,15}' title='Only letters, at least two' required='required'>";
$str.="</div>";
$str.="<div class='col'>";
$str.="<input type='file' class='form-control' name='load_user_file' required='required'>";
$str.="</div>";
$str.="<div class='col'>";
$str.="<button type='submit' class='btn btn-primary' name='petTypeAdd'>Add pet type</button>";
$str.="</div>";

$str.="</div>";
$str.="</form>";


$str.=  "</div></div></div>";


echo $str;



