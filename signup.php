<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "classes/dbClass.php";
require_once "classes/User.php";
require_once "classes/dbClassUserFunctions.php";

//-----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------if form information sent:
if (isset($_POST['newUserSent']))  //if sign up form was submitted
{
    $db = new dbClass();
    $data_arr = array(
        'userEmail' => $_POST['mail'],
        'userPassword' => password_hash($_POST['pass'],PASSWORD_DEFAULT),
        'userFName' => $_POST['fname'],
        'userLName' => $_POST['lname'],
        'userPhone' => "",
        'userBirthday' => "",
        'userAbout' => "",
        'userRegisterTime' => date("Y-m-d H:i:s"),
        'userStatus' => "user",
    );

    if ($db->getObjects("users","userEmail", $_POST['mail'], "User") == null)                                   //checking if user's mail already exists
    {
        //$db->insertLine("users", $data_arr);            //inserting new user
        $dbu = new dbClassUserFunctions();
        $dbu->insertUser($data_arr);

        $db->signinUser($_POST['mail'], $_POST['pass']);           //signing in user

        $db->sendMail($_POST['mail'],"welcomeMail");    //sending user a welcome mail

        $_SESSION['userMessage']="Welcome to petSitter! You've signed up successfully.";
        $_SESSION['userMessageStatus']=1;
        header("Location: main.php");
        exit();
    }
    else
    {
        $_SESSION['userMessage']="email is already registered to petSitter. Please sign in.";
        $_SESSION['userMessageStatus']=0;
        $_SESSION['nextPage']="signup.php";
        header("Location: main.php");
        exit();
    }
}
else if (isset($_POST['signinLink'])){
    $_SESSION['nextPage']="signin.php";
    header("Location: main.php");
    exit();
}
?>


<!------------------------------------------------------->
<!-- signup validation: -->
<script src="scripts/signup.js"></script>

<div class="text-center" id="signin">
<form name="signupForm" action="signup.php" method=post onsubmit="return validateSignup(this)" class="form-signin">
    <img class="mb-4" src="Images/Pets/General/Rabbit.png" alt="" width="111" height="111">
    <h1 class="h3 mb-3 font-weight-normal">Sign up</h1>
    <div class="form-group">
        <div class="form-row">
            <div class="col">
                <input type="text" id="fname" name="fname" class="form-control" placeholder="First name"  pattern="[A-Za-z \- ']{2,30}" title="Only letters, at least 2" required="required">
            </div>
            <div class="col">
                <input type="text" class="form-control" placeholder="Last name" name="lname" pattern="[A-Za-z \- ']{2,30}" title="Only letters, at least 2" required="required">
            </div>
        </div>
    </div>
    <div class="form-group">
        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email"  name="mail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required="required" title="Please enter a valid email address">
        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
    </div>
    <div class="form-group">
        <div class="form-row">
            <div class="col">
                <input type="password" class="form-control" id="pass" placeholder="Password" name="pass" pattern="[a-zA-Z0-9]{4,30}" title="at least 4 letters or numbers" onchange='ValidatePassword();' required="required">
            </div>
            <div class="col">
                <input type="password" class="form-control" id="passconf" placeholder="Password Confirmation" name="passconf" onchange='ValidatePassword();' required="required">
            </div>
        </div>
    </div>

    <button class="btn btn-lg btn-primary btn-block" type="submit" name="newUserSent" id="signupbutton">Sign up</button>
    <label id="message"> </label>
</form>
    <br>
    <!--
<form action="signup.php" method=post>
    <label>Already have an account?</label>
    <button class="buttonLooksLikeLink" type="submit" name="signinLink"><b>Sign In!</b></button>
</form>
-->
    <label>Already have an account?</label>
    <a class="nav-link" href="?signIn">Sign in!</a>
</div>

