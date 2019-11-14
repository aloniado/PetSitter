<?php

require_once "dbClass.php";
require_once "Pet.php";
require_once "Pet_HelpRequest.php";


//class acts as an additional layer between dbClass and site files
//contains functions regarding "pets" table and uses functions in dbClass
class dbClassPetsFunctions
{

    public function getPetsArrayByUserID($userID){
        //gets user ID, returns array of User's Pets
        $db = new dbClass();
        $petsArray = $db->getObjectsGeneral("pets", "WHERE userID='$userID'", "Pet");
        return $petsArray;
    }


    public function getPetsArrayByHelpID($helpID){
        //gets help ID, returns array of Pets in Help Request

        $db = new dbClass();
        $p_hr = $db->getObjectsGeneral("pets_helprequests", "WHERE helpID='$helpID'", "Pet_HelpRequest"); //array of Pet_HelpRequest
        /*
        echo "<pre>";
        var_dump($p_hr);
        echo "</pre>";
        */
        $petsArray = array();

        foreach ($p_hr as $k=>$v){
            $petID = $v->getPetID();
            $pet = $db->getObjectsGeneral("pets", "WHERE petID='$petID'", "Pet")[0];
            array_push($petsArray, $pet);
        }

        return $petsArray;
    }

    public function getPetsArrayByType($type){
        //gets help ID, returns array of Pets in Help Request

        $db = new dbClass();

        $petsArray = $db->getObjectsGeneral("pets", "WHERE petType='$type'", "Pet");


        return $petsArray;
    }

    //-----------------------------------------------------------------------------------------------

    //-----------------------------------------------------------------------------------------------Images:

    function loadPetImageByID($petID) {
        //uploads an image for pet with id given to function

        //todo: check size, more formats maybe?
        //printing $_FILES array:
        //echo "loadPetImage function. ID = ".$petID."<br>";
        //echo "<pre>";
        //print_r($_FILES);
        //echo "</pre>";
        //echo "Type: ".$_FILES['load_user_file']['type']."<br>";

        if ($_FILES['load_user_file']['tmp_name'] != "")                                    //if file was chosen
        {
            if (file_exists($_FILES['load_user_file']['tmp_name']))                         //if file exists
            {
                if (strpos($_FILES['load_user_file']['type'], 'image') !== false)       //if image (v)
                {
                    //echo "POST[id] = ".$_POST['id']."<br>";
                    $db = new dbClass();
                    $p = $db->getPetByID($petID);
                    if ($p != null)                                                         //if pet exists (v)
                    {
                        $location = "Images/Pets/";
                        $filename = trim($petID).".jpg";

                        move_uploaded_file($_FILES['load_user_file']['tmp_name'], $location.$filename);
                    }
                    else
                        return "Pet not found in DB";
                }
                else
                    return "Unknown image format";
            }
            else
                return "File doesn't exist";
        }
        else
            return "No image was selected";

        return "ok";
    }


    public function deletePetImageByID($petID){
        //removes image for pet with id given to function
        $location = "Images/Pets/";
        $filename = trim($petID) . ".jpg";
        if (file_exists($location . $filename)) {
            unlink($location . $filename);
        }
    }

    public function doesPetHaveImage($petID){
        $location = "Images/Pets/";
        $filename = $petID.".JPG";
        $answer=0;
        if (file_exists($location.$filename))
            $answer=1;
        return $answer;
    }


    function loadPetTypeImage($petType) {
        //uploads an image for pet with id given to function

        if ($_FILES['load_user_file']['tmp_name'] != "")                                    //if file was chosen
        {
            if (file_exists($_FILES['load_user_file']['tmp_name']))                         //if file exists
            {
                if (strpos($_FILES['load_user_file']['type'], 'image') !== false)       //if image (v)
                {
                        $location = "Images/Pets/General/";
                        $filename = trim($petType).".png";

                        move_uploaded_file($_FILES['load_user_file']['tmp_name'], $location.$filename);
                }
                else
                    return "Unknown image format";
            }
            else
                return "File doesn't exist";
        }
        else
            return "No image was selected";
        return "ok";
    }

    public function addPetType($typeString){
        $str="";
        $petTypes = file_get_contents("classes/petTypes.txt");   //$words now contains text from file
        echo $petTypes."<br>";
        $words_arr = explode("\n",$petTypes);
        echo "<pre>";
        var_dump($words_arr);
        echo "</pre>";

        foreach ($words_arr as $k=>$v)
        {
            if ($v!="Other")
                $str.=$v."\n";
        }
        $str.=$typeString."\n"."Other";

        echo $str."<br>";
        file_put_contents("classes/petTypes.txt" , $str);

        echo file_get_contents("classes/petTypes.txt");

    }

    public function removePetType($typeString){
        $str="";
        $petTypes = file_get_contents("classes/petTypes.txt");   //$words now contains text from file

        if ($typeString != "Other"){
            $words_arr = explode("\n",$petTypes);
            $key = array_search($typeString, $words_arr);
            unset($words_arr[$key]);

            foreach ($words_arr as $k=>$v) {
                $str.=$v."\n";
            }
            if ($this->getPetsArrayByType($typeString)==null){  //if no pets of that type in db -> deleting image
                //removes image for pet with id given to function
                $location = "Images/Pets/General/";
                $filename = trim($typeString) . ".png";
                if (file_exists($location . $filename)) {
                    unlink($location . $filename);
                }
            }

            file_put_contents("classes/petTypes.txt" , $str);
        }
    }




}