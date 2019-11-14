<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "classes/dbClass.php";
require_once "classes/User.php";
require_once "classes/Pet.php";
require_once "classes/dbClassRankingFunctions.php";
require_once "classes/dbClassUserFunctions.php";


$db = new dbClass();
$dbr = new dbClassRankingFunctions();
$signedInUser=$db->getSignedInUserData();
//-----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------if form information sent:
if (isset($_POST['editP'])){
    $_SESSION['nextPage']="editProfile.php";
    header("Location: main.php");
    die();
}
else if (isset($_POST['PasswordChangeDataSent'])){
    $dbu = new dbClassUserFunctions();
    if ($dbu->verifyPassword($_POST['pass_old'], $_SESSION['user'])==1)
    {
        if ($_POST['pass']==$_POST['passconf']) //changing password:
        {
            $data_arr = array(
                'userPassword' => password_hash($_POST['pass'],PASSWORD_DEFAULT)
            );
            $db->updateLine('users', 'userID', $_SESSION['user'], $data_arr);
            $_SESSION['userMessage']="Password changed!";
        }
        else
            $_SESSION['userMessage']="ERROR. New passwords didn't match. Password wasn't changed.";
    }
    else
    {
        $_SESSION['userMessage']="ERROR. Wrong password. Password wasn't changed.";
    }

    $_SESSION['nextPage']="ProfileUser.php";
    $_SESSION['UserProfileToShow']=$_SESSION['user'];
    header("Location: main.php");
    die();

}

//getting user:
$u = $db->getObjectsGeneral("users", " WHERE `userID`='".$_SESSION['UserProfileToShow']."'", "User")[0];
unset($_SESSION['UserProfileToShow']);

//getting User's Pets:
$petsArray = $db->getObjectsGeneral("pets", " WHERE `userID`='".$u->getUserID()."'", "Pet");

//-------------------------------building pets string:
if ($petsArray == null){
    $petsStr="<br><b>No pets found</b>";
}
else {
    $petsStr="<br><b>Pets:</b>";

    $petsStr.="<br>";
    foreach ($petsArray as $p) //each $p is a Pet object
    {
        $image_str = "'background-image:url(".$db->getPetImageLocation($p).")';";
        $petsStr.="<div class='pet-img' style=$image_str><div class='pet-name'>".$p->getPetName()."</div></div>";
    }



    //echo $petsStr;
}
//-----------------------------------------------profile string:
$str="";
$str.="";
$str.="<div class='card'  id='userProfileCard'>";
  $str.="<h5 class='card-header'>";
if ($u->getUserID() == $_SESSION['user']){
    $str.= "My Profile";
}
else
    $str.=$u->getUserFName()." ".$u->getUserLName()."'s Profile";

  $str.="</h5>";
  $str.="<div class='card-body'>";
    $str.="<p class='card-text'>";

if ($u->getUserID() == $_SESSION['user'])
{
    if ($u->getUserPhone()=="")
        $str.="<div class='red-text-warning'>You haven't entered your phone number yet, so you won't be able to help other people.</div><br>";
    else if ($petsArray == null)
        $str.="<div class='red-text-warning'>You haven't added any pets yet. Why not join our community and get some help?</div><br>";
    else if ($u->getUserAbout()=="")
        $str.="<div class='red-text-warning'>You haven't written anything about yourself. A few words makes a huge difference and help others trust you :)</div><br>";

}

    $str.="<b>Name: </b>".$u->getUserFName()." ".$u->getUserLName()."<br>";
    if ($u->getUserBirthday()=="0000-00-00")
        $str.="<b>Birthday: </b>Not Entered<br>";
    else
        $str.="<b>Birthday: </b>".$u->getUserBirthday()."<br>";

    //rating:
    $userRank = $dbr->getUserRankByID($u->getUserID());
    $userRankStr = $userRank;
    if (number_format((float)$userRankStr, 2, '.', '') != 0)
        $str.="<b>Rating: </b>".number_format((float)$userRankStr, 2, '.', '')."<br>";
    else
        $str.="<b>Rating: </b>No Rating yet.<br>";

    //About:
    if ($u->getUserAbout() == "")
        $str.="<b>About: </b>Not Entered<br>";
    else
        $str.="<b>About: </b>".$u->getUserAbout()."<br>";
    if ($_SESSION['user']==$u->getUserID())
        $str.="<form action='ProfileUser.php' method=post><button type='submit' class='btn btn-primary' name='editP'>Edit Profile</button></form>";
    $str.=$petsStr;
  $str.="</div>";
$str.="</div>";

echo "<div class='d-flex justify-content-center'>";
echo $str;      //profile card
echo "</div>";
//-----------------------------------------------------------------------private information:


if ($u->getUserID() == $_SESSION['user']){
    $str="<br>";

    $str.="<div class='d-flex justify-content-center'>";
    $str .= "<div class='card' id='userReviewCard'>";
    $str .= "<div class='card-header'><b>";

    $str.="Private information visible only to you:";
    $str .= "</b></div>";
    $str .= "<div class='card-body'>";
    $str.="<b>Email: </b>".$u->getUserEmail()."<br>";
    if ($u->getUserPhone() == "")
        $str.="<b>Phone number: </b>Not entered<br>";
    else
        $str.="<b>Phone number: </b>".$u->getUserPhone()."<br>";
    $str.="<div id='changePasswordDiv'><button type='submit' class='btn btn-primary' id='changePasswordButton'>Change password</button></div><br>";
    $str .= "<p class='card-text'>";



    $str.="</p>";
    $str .= "</div>";
    $str .= "</div><br>";


    $str.="</div>";

    echo $str;      //reviews
}
//-----------------------------------------------------------------------reviews:
$str="<br>";
$ranksArr = $db->getObjectsGeneral("rankings", " WHERE `rankedUserID` = '".$u->getUserID()."'", "Ranking");
if (!empty($ranksArr)){

        $str.="<div class='d-flex justify-content-center'>";
        $str .= "<div class='card' id='userReviewCard'>";
        $str .= "<div class='card-header'><b>";

        $str.="Reviews:";
        $str .= "</b></div>";
        $str .= "<div class='card-body'>";
        //$str .= "<h5 class='card-title'>Special title treatment</h5>";
        $str .= "<p class='card-text'>";

    foreach ($ranksArr as $k=>$v) {
            $dbu = new dbClassUserFunctions();
            $ReviewingUser = $dbu->getUserByID($v->getRankingUserID());
            $str .= "Rated ".$v->getRankRank()." By ".$ReviewingUser->getUserFName()." ".$ReviewingUser->getUserLName().": <br>'";
            $str.="<b><i>".$v->getRankAbout()."'</i></b><br><br>";
        }
        $str.="</p>";
        $str .= "</div>";
        $str .= "</div><br>";


        $str.="</div><br>";

    echo $str;      //reviews
}
//---------------------------------------



echo "</div>";

?>
<script>    <!--change password form-->
    $(document).ready(function() {
        $('#changePasswordButton').click(function(){
            $("#changePasswordDiv").html("");
            $('#changePasswordDiv').append('' +
                '<form action="ProfileUser.php" method=post class="form-signin" >'+
                '<input type="password" class="form-control" placeholder="Your current password" required name="pass_old">'+
                '<input type="password" class="form-control" placeholder="A new and improved password" required name="pass" id="pass" pattern="[a-zA-Z0-9]{4,30}" title="at least 4 letters or numbers">'+
                '<input type="password" class="form-control" placeholder="New password again" required name="passconf" pattern="[a-zA-Z0-9]{4,30}" title="at least 4 letters or numbers">'+
                '<button class="btn btn-lg btn-primary btn-block" type="submit" name="PasswordChangeDataSent" id="PasswordChangeDataSent">Change!</button>'+
                '</form>'+
                '');
        });
    });
</script>




