<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "classes/dbClass.php";
require_once "classes/User.php";

$db = new dbClass();
$signedInUser=$db->getSignedInUserData();
//-----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------if form information sent:
if (isset($_POST['sent']))  //if form was submitted
{
    //TODO: js validation to fields

    //$tempUser = new User($_POST['pass'], $_POST['fname'], $_POST['lname'], $_POST['mail']);

    $signedInUser->setUserFName($_POST['fname']);
    $signedInUser->setUserLName($_POST['lname']);
    $signedInUser->setUserPhone($_POST['phone']);
    $signedInUser->setUserBirthday($_POST['birth']);
    $signedInUser->setUserAbout($_POST['about']);

    var_dump($signedInUser);

    $db->editUserData($signedInUser);

    $_SESSION['userMessage']="Details saved successfully.";
    $_SESSION['nextPage']="ProfileUser.php";
    $_SESSION['UserProfileToShow']=$signedInUser->getUserID();
    header("Location: main.php");
    die();


    //header("Location: main.php");
    //die();
    /*
    echo "<pre>";
    var_dump($signedInUser);
    echo "</pre>";
    */
}


?>
<!-------------------------------------------------------->
<div class="card">
    <h5 class="card-header">Edit Profile:</h5>
    <div class="card-body">
        <form action="editProfile.php" method=post>
            <div class="form-group">
                <div class="form-row">
                    <div class="col">
                        <label>Email Address:</label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col">
                        <input type="text" disabled class="form-control" placeholder="<?php echo $signedInUser->getUserEmail()?>" name="email">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-row">
                    <div class="col">
                        <label>First Name:</label>
                    </div>
                    <div class="col">
                        <label>Last Name:</label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col">
                        <input type="text" class="form-control" placeholder="First name" name="fname" value="<?php echo $signedInUser->getUserFName()?>" pattern="[A-Za-z \- ']{2,30}" title="Only letters, at least 2" required="required">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" placeholder="Last name" name="lname" value="<?php echo $signedInUser->getUserLName()?>" pattern="[A-Za-z \- ']{2,30}" title="Only letters, at least 2" required="required">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="form-row">
                    <div class="col">
                        <label>Phone Number:</label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col">
                        <input type="tel" class="form-control" id="exampleInputPhone1" aria-describedby="emailHelp" placeholder="Enter Phone Number"  name="phone" value="<?php echo $signedInUser->getUserPhone()?>" pattern="[0-9]{9,10}" title="please enter a valid phone number">
                    </div>
                </div>
            </div>



            <div class="form-group">
                <div class="form-row">
                    <div class="col">
                        <label>Birthday:</label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col">
                        <input type="date" class="form-control" id="exampleInputDate1" aria-describedby="emailHelp" placeholder="<?php echo $signedInUser->getUserBirthday()?>"  name="birth" value="<?php echo $signedInUser->getUserBirthday()?>">

                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-row">
                    <div class="col">
                        <label>About me:</label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col">
                        <input type="text" class="form-control" id="exampleInputAbout1" aria-describedby="emailHelp" placeholder="Tell People About Yourself"  name="about" value="<?php echo $signedInUser->getUserAbout()?>">            </div>
                </div>
            </div>



            <div class="form-group">

                <div class="form-row">
                    <div class="col">
                        <button type="submit" class="btn btn-primary" name="sent">Update profile</button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
<!------------------------------------------------------->


