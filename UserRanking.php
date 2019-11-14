<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "classes/dbClass.php";
require_once "classes/dbClassRankingFunctions.php";
require_once "classes/dbClassUserFunctions.php";




//----------------------------------------------------------------------------------------if form information sent:
/*
echo "_POST:<br>";
echo "<pre>";
var_dump($_POST);
echo "</pre>";

echo "_SESSION:<br>";
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";
echo $first_key;
*/
if (isset($_POST['sendRanking']))   //if form was sent:
{
    $db = new dbClass();
    $dbr = new dbClassRankingFunctions();

    $data_arr = array(
        'rankingUserID' => (int)$_SESSION['user'],
        'rankedUserID' => (int)$_POST['rankedUserID'],
        'helpID' => (int)$_POST['helpID'],
        'rankRank' => (int)$_POST['rank'],
        'rankAbout' => $_POST['rabout'],
    );

    $dbr->insertRanking($data_arr);


    $_SESSION['userMessage']="You've successfully added a user ranking and dismissed a help request.";

    $_SESSION['UserProfileToShow']=(int)$_POST['rankedUserID'];  //***
    $_SESSION['nextPage']="ProfileUser.php";

    header("Location: main.php");
    die();
}
else {                              //form was not yet sent

    $dbu = new dbClassUserFunctions();
    $userRanked = $dbu->getUserByID($_SESSION['RankedUser']);   //user being ranked on a help request
    $userRanking = $dbu->getUserByID($_SESSION['user']);        //signed in user
    $helpRequest = $_SESSION['RankedOnHelp'];   //help request number

    unset($_SESSION['RankedUser']);
    unset($_SESSION['RankedOnHelp']);
    /*
    echo "ranking user: ".$_SESSION['user']."<br>";
    echo "ranked user: ".$rankedUser."<br>";
    echo "help request done: ".$helpRequest."<br>";
    */
}



?>
<h3>Ranking <?php echo $userRanked->getUserFName()." ".$userRanked->getUserLName() ?>:</h3>


<!------------------------------------------------------->
<div class="card">
    <form action="UserRanking.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <div class="form-row">
                <div class="col">
                    <label>Please rate your experience with <?php echo $userRanked->getUserFName()." ".$userRanked->getUserLName()?> honestly:</label>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <select name="rank" required>
                        <option value="10">Excellent</option>
                        <option value="8">Very good</option>
                        <option value="6">OK</option>
                        <option value="4">Not good</option>
                        <option value="2">Very poor</option>
                    </select>
                </div>
            </div>
        </div>
        <br>

        <div class="form-group">
            <div class="form-row">
                <div class="col">
                    <label>Elaborate and explain:</label><br>
                    <textarea rows="4" cols="50" type="text" class="form-control" placeholder="Tell other people how was it" name="rabout"  required="required"></textarea>
                    <small id="emailHelp" class="form-text text-muted">Your rating and experience are publicly posted, and are visible to everyone.</small>
                </div>
            </div>
        </div>

        <!-- passing additional information: -->
        <input type="hidden" id="rankedUserID" name="rankedUserID" value="<?php echo $userRanked->getUserID()?>">
        <input type="hidden" id="helpID" name="helpID" value="<?php echo $helpRequest?>">


        <button type="submit" class="btn btn-primary" name="sendRanking">Publicly post to <?php echo $userRanked->getUserFName()." ".$userRanked->getUserLName()?>'s profile</button>
    </form>
</div>

