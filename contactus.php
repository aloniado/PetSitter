<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "classes/dbClass.php";
require_once "classes/User.php";
require_once "classes/dbClassUserFunctions.php";

//filling forms automatically of signed in users:
$name = "";
$email = "";

if (isset($_SESSION['user'])){

    $dbu = new dbClassUserFunctions();
    $user = $dbu->getUserByID($_SESSION['user']);
    $name = $user->getUserFName()." ".$user->getUserLName();
    $email = $user->getUserEmail();
}


//-----------------form sent handling:
if (isset($_POST['contact_form_sent']))
{

    if (isset($_SESSION['user'])){
        $details = "user was signed in when sending this message. signed in user's ID: ".$_SESSION['user']."\n";
    }
    else {
        $details = "user wasn't signed in when sending message.\n";
    }

    $dbu = new dbClassUserFunctions();
    $user = $dbu->getUserByEmail($_POST['email']);
    if ($user != null){ //email in form corresponds with existing user
        $details .= "email filled out by user matches existing user. ID: ".$user->getUserID();
    }
    else {
        $details .= "email filled out by user doesn't match an existing user.";
    }

    //sending an email to site admin:
    $db = new dbClass();
    $db->sendMail_ContactUs("contactUsMail", $_POST['title'], $_POST['message'], $_POST['name'], $_POST['email'], $details);    //sending an email with user filled details


    $_SESSION['userMessage']="Message sent. we'll contact you at <b>".$_POST['email']."</b> soon.";
    header("Location: main.php");
    die();
}

?>
<div class="d-flex justify-content-center">
    <div class="card">
        <h5 class="card-header">Tell us what's on your mind</h5>
        <div class="card-body">

    <form action="contactus.php" method="post" enctype="multipart/form-data">

        <div class="form-group">
            <div class="form-row">
                <div class="col">
                    <label>Message title:</label>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <input type="text" class="form-control form-control-lg" name="title" pattern="[A-Za-z0-9 -']{2,30}" title="Only letters and numbers, at least 2" required="required">
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="form-row">
                <div class="col">
                    <label>Message content:</label>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <textarea rows="5" cols="50" type="text" class="form-control" name="message" required="required"></textarea>
                </div>
            </div>
        </div>
        <!------->
        <div class="form-group">
            <div class="form-row">
                <div class="col">
                    <label>Your name:</label>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <input type="text" class="form-control" placeholder="Your name" name="name" pattern="[A-Za-z \- ']{2,50}" title="Only letters, at least 2" required="required" value="<?php echo $name?>">
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="form-row">
                <div class="col">
                    <label>Your email:</label>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <input type="text" class="form-control" placeholder="Your email address" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required="required" value="<?php echo $email?>">
                </div>
            </div>
        </div>
        <!------>




        <button type="submit" class="btn btn-primary float-right" name="contact_form_sent">Message us</button>
    </form>
</div>
    </div>
</div>



