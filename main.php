<?php
//unset($_SESSION);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//  -------------------------------------------------------------------Debugging:

$show_nav_help = 0;     //0 or 1
if ($show_nav_help){
    echo "_POST:<br>";
    echo "<pre>";
    var_dump($_POST);
    echo "</pre>";

    echo "_SESSION:<br>";
    echo "<pre>";
    var_dump($_SESSION);
    echo "</pre>";

    echo "_GET:<br>";
    echo "<pre>";
    var_dump($_GET);
    echo "</pre>";
}
//---------------------------------------------------------------------TOP OF PAGE
include_once "headerTop.php";
//  -------------------------------------------------------------------SIGNED IN USER
if (isset($_SESSION) && isset($_SESSION['user']))
{
    //------------------------------header loading:
    include_once "headerUser.php";


    //------------------------------message loading:
    if (isset($_SESSION['userMessage'])){
        if (isset($_SESSION['userMessageStatus']) && $_SESSION['userMessageStatus']==0)
            echo "<div class='userMessageRed'>".$_SESSION['userMessage']."</div>";
        else
            echo "<div class='userMessage'>".$_SESSION['userMessage']."</div>";
        unset($_SESSION['userMessage']);
        unset($_SESSION['userMessageStatus']);

    }

    //------------------------------page loading:
    if (isset($_GET['myProfile'])){
        $_SESSION['UserProfileToShow']=$_SESSION['user'];
        include_once "ProfileUser.php";
    }
    else if (isset($_GET['myPets'])){
        include_once "myPets.php";
    }
    else if (isset($_GET['helpRequestsAll'])){
        include_once "HelpRequestsAll.php";
    }
    else if (isset($_GET['myHelpRequests'])){
        include_once "myHelpRequests.php";
    }
    else if (isset($_GET['helpRequestAdd'])){
        include_once "helpRequestAdd.php";
    }
    else if (isset($_GET['admin'])){
        include_once "admin.php";
    }
    else if (isset($_GET['contactUs'])){
        include_once "contactus.php";
    }
    else if (isset($_GET['signOut'])){
        include_once "signout.php";

    }
    else if (isset($_SESSION['nextPage']) && $_SESSION['nextPage']!=""){
        include_once $_SESSION['nextPage'];
    }
    else
        include_once "HelpRequestsAll.php";

}

else
{
    //  -------------------------------------------------------------------NOT SIGNED IN
    //------------------------------header loading:
    include_once "headerGuest.php";


    //------------------------------message loading:
    if (isset($_SESSION['userMessage'])){
        if (isset($_SESSION['userMessageStatus']) && $_SESSION['userMessageStatus']==0)
            echo "<div class='userMessageRed'>".$_SESSION['userMessage']."</div>";
        else
            echo "<div class='userMessage'>".$_SESSION['userMessage']."</div>";
        unset($_SESSION['userMessage']);
        unset($_SESSION['userMessageStatus']);
    }
    //------------------------------page loading:
    if (isset($_GET['signUp'])){
        include_once "signup.php";
    }
    else if (isset($_GET['contactUs'])){
        include_once "contactus.php";
    }
    else {
        include_once "signin.php";
    }
}





$_SESSION['nextPage']="";




include_once "footer.php";







