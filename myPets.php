<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "classes/dbClass.php";
require_once "classes/dbClassPetsFunctions.php";
require_once "classes/Pet.php";


$db = new dbClass();
$dbp = new dbClassPetsFunctions();


//-----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------if form information sent:
reset($_POST);
$first_key = key($_POST);   //command + ID of pet to edit

//echo $first_key."<br>";
if (isset($_POST['addpet']))                                    //if add pet clicked
{
    //checking number of user's pets in database:
    $petsArr = $dbp->getPetsArrayByUserID($_SESSION['user']);
    if (count($petsArr) >= 10)
    {
        $_SESSION['userMessage']="You've already reached maximum pets allowed.";
        $_SESSION['nextPage']="myPets.php";
        header("Location: main.php");
        die();
    }
    else
    {
        $_SESSION['nextPage']="editPet.php";
        header("Location: main.php");
        die();
    }
}

else if (substr($first_key, 0, 4) === "edit")       //if edit pet details button is pressed
{
    $first_key = substr($first_key, 4);

    $_SESSION['petToEdit']=$first_key;
    $_SESSION['nextPage']="editPet.php";

    header("Location: main.php");
    die();
}

else if (substr($first_key, 0, 6) === "delete")     //if delete pet button pressed
{
    $first_key = substr($first_key, 6);
/*
    $db->deleteLine("pets", "petID", $first_key);
    $_SESSION['nextPage']="myPets.php";
*/

    $_SESSION['petToDelete']=$first_key;
    $_SESSION['nextPage']="petDelete.php";

    header("Location: main.php");
    die();
}

else if (substr($first_key, 0, 8) === "delimage")  //if delete pet's image button pressed
{
    $petID = substr($first_key, 8);
    $dbp = new dbClassPetsFunctions();
    $dbp->deletePetImageByID($petID);

    $_SESSION['nextPage']="myPets.php";
    header("Location: main.php");
    die();
}
//-----------------------------------------------------------------------------------------------------------------

unset($_SESSION['petToDelete']);
unset($_SESSION['petToEdit']);          //$_SESSION['petToEdit'] is used in page to determine which pet to edit

//echo "<h3>My Pets</h3>";




//---*******************    changes (18.7.2018 evgenia example)
//$myPetsArray = $db->getPetsByEmail($_SESSION['user']);        //replaced with next line:
$dbp = new dbClassPetsFunctions();
$myPetsArray = $dbp->getPetsArrayByUserID($_SESSION['user']);
//---*******************





    $mypetsstr="";
    $mypetsstr.="<div class='card' id='userProfileCard'>";
    $mypetsstr.="<h5 class='card-header'>My pets</h5>";
    $mypetsstr.="<div class='card-body'>";


    $mypetsstr.="<p class='card-text'>";
    $mypetsstr.="<form action='myPets.php' method=post>";
    $mypetsstr.="<button type='submit' class='btn btn-primary' name='addpet'>Add Pet</button>";
    $mypetsstr.="</form><br>";
if ($myPetsArray == null)
    $mypetsstr.="It's a little empty here! Add your pets and get some help :).";
else
{


    $mypetsstr.="<div class='card-deck'>";

    echo $mypetsstr;
    foreach ($myPetsArray as $k)
    {
        $petstr="";
        //----------------------------------------------------------------new card circle:
        $petstr.="<div id='petCard' class='card  mx-auto'>";
        //$petstr.="<img class='card-img-top' src='".$db->getPetImageLocation($k)."' alt='".$k->getPetName()."'>";

        $petstr.="<h5 class='card-header'>".$k->getPetName()."  ( ".$k->getPetType().")</h5>";

        $petstr.="<div class='pet-img-in-mypets'>";
        $image_str = "'background-image:url(".$db->getPetImageLocation($k).")';";
        $petstr.="<div class='pet-img' style=$image_str></div>";
        $petstr.="</div>";

        $petstr.="<div class='card-body'>";


        $petstr.="<p class='card-text'>";
        $petstr.="Sex: ".$k->getPetSex()."<br>";
        $petstr.="Birthday: ".$k->getPetBirthday()."<br>";
        $petstr.="Food habbits: ".$k->getPetFood()."<br><br>";
        $petstr.=$k->getPetAbout();
        $petstr.="</p>";

        $petstr.="<form action='myPets.php' method=post>";
        $petstr.="<button type='submit' class='btn btn-group-sm btn-primary btn-block' name='"."edit".$k->getPetID()."'>Edit Pet</button>";
        $petstr.="<button type='submit' class='btn btn-group-sm btn-primary btn-block' name='"."delete".$k->getPetID()."'>Delete Pet</button>";
        if ($dbp->doesPetHaveImage($k->getPetID()))
            $petstr.="<button type='submit' class='btn btn-group-sm btn-primary btn-block' name='"."delimage".$k->getPetID()."'>Delete Image</button>";
        $petstr.="</form>";

        $petstr.="</div>";
        $petstr.="</div>";

        echo $petstr;

    }
    $mypetsstr="</div>";

    $mypetsstr.="</p></div>";


}
$mypetsstr.="</div></div>";
echo $mypetsstr;






