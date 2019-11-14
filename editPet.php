<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "classes/dbClass.php";
require_once "classes/User.php";
require_once "classes/Pet.php";

require_once "classes/dbClassPetsFunctions.php";


$db = new dbClass();

if (isset($_SESSION['petToEdit'])){     //editing pet
    $tempPet = $db->getPetByID($_SESSION['petToEdit']);
}
else{                                   //adding new pet
    $tempPet = new Pet();
}

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
if (isset($_POST['sent']))
{
    $db = new dbClass();
    $dbp = new dbClassPetsFunctions();

    $data_arr = array(
        'petName' => $_POST['pname'],
        'petType' => $_POST['ptype'],
        'petBirthday' => $_POST['pbirth'],
        'petSex' => $_POST['psex'],
        'petFood' => $_POST['pfood'],
        'petTemper' => $_POST['ptemper'],
        'petAbout' => $_POST['pabout'],
        'petStatus' => 1,
        'petRegisterTime' => date("Y-m-d H:i:s"),
        'userID' => $_SESSION['user'],
    );

    if (isset($_SESSION['petToEdit'])) {     //editing pet
        $db->updateLine("pets", "petID", $tempPet->getPetID(), $data_arr);  //updating 'pet'
        $dbp->loadPetImageByID($tempPet->getPetID());                                                 //uploading new image
    }
    else{
        $db->insertLine("pets", $data_arr);                                             //inserting new line to 'pets'
        $petInserted = $db->getObjectsGeneral("pets", " WHERE `userID`='".$_SESSION['user']."'ORDER  BY `petRegisterTime` DESC LIMIT  1", "Pet");    //getting last 'pet' inserted
        $dbp->loadPetImageByID($petInserted[0]->getPetID());                                         //uploading new image attached to ID of last pet inserted
    }

    $_SESSION['userMessage']="Pet Saved.";
    $_SESSION['nextPage']="myPets.php";
    header("Location: main.php");
    die();
}


//function gets file's name (fileName) and referance to string array (line_arr)
//then exploads text from file to string array with each '\n'
function readFileLines($fileName, &$line_arr) {
    $line = file_get_contents($fileName);   //line now contains text from file
    $line_arr = explode("\n",$line);
    return;
}

//fills comboBox with items from string array 'items'
function selectItems($items,$selected=0,$existype)
{
    $text = "";
    if (isset($_SESSION['petToEdit']))
        $text.="<option value='$existype'>$existype</option>\n";
    else
        $text.="<option value=''>Type:</option>\n";

    foreach($items as $k=>$v)
    {
        if ($v != "")   //skipping empty line at end of files
        {
            $text.="<option value='$v'>$v</option>\n";
        }
    }
    return $text;
}
$pet_arr=array();

readFileLines("classes/petTypes.txt", $pet_arr);


if (isset($_SESSION['petToEdit'])){
    $header = "Editing ".$tempPet->getPetName();
}
else{
    $header = "Adding new pet";
}

?>

<!------------------------------------------------------->
<div class="d-flex justify-content-center">
<div class="card">
    <h5 class="card-header"><?php echo $header?></h5>
            <form action="editPet.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <div class="form-row">
                        <div class="col">
                            <label>Name:</label>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col">
                            <input type="text" class="form-control form-control-lg" placeholder="Pet's Name" name="pname" value="<?php echo $tempPet->getPetName()?>" pattern="[A-Za-z -']{2,30}" title="Only letters, at least 2" required="required">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col">
                            <label>Type:</label>
                        </div>
                        <div class="col">
                            <label>Sex:</label>
                        </div>

                        <div class="col">
                            <label>Birtyday:</label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <select class="form-control" name=ptype required><?=selectItems($pet_arr,$_POST['ptype'],$tempPet->getPetType())?></select>
                            <!--<small id="typeHelp" class="form-text text-muted">your pet type doesn't appear? select Other and contact us</small>-->
                        </div>

                        <div class="col">
                            <input type="text" class="form-control" placeholder="Pet's Sex" name="psex" value="<?php echo $tempPet->getPetSex()?>">
                        </div>

                        <div class="col">
                            <input type="date" class="form-control" placeholder="Pet's Birthday" name="pbirth" value="<?php echo $tempPet->getPetBirthday()?>">
                        </div>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col">
                            <label>Food habits:</label><br>
                            <textarea rows="2" cols="50" type="text" class="form-control" placeholder="You may add special food habits" name="pfood"><?php echo $tempPet->getPetFood()?></textarea>
                        </div>
                    </div>
                </div>

<!--
                    <div class="form-row">
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Temper and general behaviour" name="ptemper" value="<?php echo $tempPet->getPetTemper()?>">
                        </div>
                    </div>
-->
                <div class="form-group">
                <div class="form-row">
                        <div class="col">
                            <label>About:</label><br>
                            <textarea rows="4" cols="50" type="text" class="form-control" placeholder="a few words about your pet" name="pabout"  required="required"><?php echo $tempPet->getPetAbout()?></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <input type="file" class="form-control-file" name="load_user_file">
                </div>


                <button type="submit" class="btn btn-primary" name="sent">Save Pet</button>
            </form>
</div>
</div>








