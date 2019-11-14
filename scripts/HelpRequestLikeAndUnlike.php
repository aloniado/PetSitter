<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['user']) && isset($_POST['help'])){
    //$_SESSION['done']="added";

    $helpID = $_POST['help'];
    $userID = $_POST['user'];


    require_once "../classes/dbClassInterested.php";
    $dbi = new dbClassInterested();

    $is_interested = $dbi->GetInterestedByIDs($_SESSION['user'], $helpID);
    //$_SESSION['LikeDecisionAnswer']=$is_interested;

    if ($is_interested == null)
    {
        $dbi->insertInterestedRecord($helpID, $userID);
        //$_SESSION['likeOperation']="added";
    }

    else
    {
        $dbi->RemoveInterestedRecord($helpID, $userID);
        //$_SESSION['likeOperation']="removed";
    }


    //$_SESSION['helpRequestView']=$helpID;
    //$_SESSION['nextPage']="helpRequestView.php";

    //unset($_SESSION['LikeDecisionAnswer']);
    //unset($_SESSION['likeOperation']);
}
