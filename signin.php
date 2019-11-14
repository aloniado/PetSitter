<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "classes/dbClass.php";
require_once "classes/User.php";


//-----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------if form information sent:
if (isset($_POST['SignInDataSent']))  //if sign in form was submitted
{
    $db = new dbClass();
    //$tempUser = new User($_POST['pass'], $_POST['fname'], $_POST['lname'], $_POST['mail']);

    $pass=$_POST['pass'];
    $mail=$_POST['mail'];

    $ans = $db->signinUser($mail, $pass);
    if ($ans ==1){
        $_SESSION['userMessage']="Welcome back!";
        $_SESSION['nextPage']="HelpRequestsAll.php";
        $_SESSION['userMessageStatus']=1;
    }
    else {
        $_SESSION['nextPage']="signin.php";
        $_SESSION['userMessage']=$ans;
        $_SESSION['userMessageStatus']=0;
    }
    header("Location: main.php");
    exit();
}
else if (isset($_POST['signupLink'])){
    $_SESSION['nextPage']="signup.php";
    header("Location: main.php");
    exit();
}



?>

<!------------------------------------------------------->
<div class="text-center" id="signin">
<form action="signin.php" method=post class="form-signin">
    <img class="mb-4" src="Images/Pets/General/Rabbit.png" alt="" width="111" height="111">

        <h1 class="h3 mb-3 font-weight-normal">Sign in</h1>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus name="mail">

        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" required name="pass">

    <button class="btn btn-lg btn-primary btn-block" type="submit" name="SignInDataSent">Sign in</button>

</form>
<br>
    <!--
<form action="signin.php" method=post>
    <label>Don't have an account yet?</label>
    <button class="buttonLooksLikeLink" type="submit" name="signupLink"><b>Sign Up!</b></button>
</form>
-->
    <label>Don't have an account yet?</label>
    <a class="nav-link" href="?signUp">Sign Up!</a>
</div>
