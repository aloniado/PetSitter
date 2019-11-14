<?php
require_once "User.php";
require_once "Interested.php";
require_once "Ranking.php";

//class connects to data base and performs basic activities like inserting or deleting records
class dbClass
{
    private $host;
    private $db;
    private $charset;
    private $user;
    private $pass;
    private $opt = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    );
    private $connection;    //PDO object

    public function __construct(string $host = "localhost", string $db = "petsitterdb", string $charset = "utf8", string $user = "root", string $pass = "")
    {
        $this->host = $host;
        $this->db = $db;
        $this->charset = $charset;
        $this->user = $user;
        $this->pass = $pass;
    }

    public function connect()
    {
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
        $this->connection = new PDO($dsn, $this->user, $this->pass, $this->opt);
    }

    public function disconnect(){
        $this->connection = null;
    }

    //-------------------------------------------------------------------------General DB functions:
    public function insertLine($table_name, $form_data){
        //function inserts new line to table in DB
        $this->connect();

        // retrieve the keys of the array (column titles)
        $fields = array_keys($form_data);

        // build the query
        //todo: may need to update to CURRENT_TIMESTAMP
        $sql = "INSERT INTO ".$table_name."(`".implode('`,`', $fields)."`)VALUES('".implode("','", $form_data)."')";
        //echo "<br>$sql<br>";
        $this->connection->query($sql);

        $this->disconnect();
    }

    function deleteLine($table_name, $field_name, $field_value){
        $this->connect();
        $sql = "DELETE FROM `".$table_name."` WHERE `".$field_name."`='".$field_value."'";
        $this->connection->query($sql);
        $this->disconnect();
    }

    function deleteLineGeneral($table_name, $condition){
        $this->connect();
        $sql = "DELETE FROM `".$table_name."` ".$condition;
        $this->connection->query($sql);
        $this->disconnect();
    }

    function updateLine($table_name, $field_name, $field_value, $form_data){
        $this->connect();

        $sql = "UPDATE `".$table_name."` SET ";
        $sets = array();
        foreach($form_data as $column => $value) {
            $sets[] = "`".$column."` = '".$value."'";
        }
        $sql .= implode(', ', $sets);
        $sql .= " WHERE ".$field_name."='".$field_value."'";


        echo $sql;
        $this->connection->query($sql);

        $this->disconnect();
    }
    /*
        public function getObject($table_name, $field_name, $field_value, $objectToFetch){
            //function checks if object) exists in table and returns it, or NULL
            $this->connect();

            $sql="SELECT * FROM `".$table_name."` WHERE `".$field_name."`='".$field_value."'";
            $result = $this->connection->query($sql);
            $obj = $result->fetchObject($objectToFetch);
            echo $sql."<br>";
            $this->disconnect();
            return $obj;
        }
    */
    public function getObjects($table_name, $field_name, $field_value, $objectToFetch){
        //function checks if objects exists in table and returns ARRAY(!) of objects, or NULL
        $this->connect();
        $objArray = Array();

        $sql="SELECT * FROM `".$table_name."` WHERE `".$field_name."`='".$field_value."'";
        $result = $this->connection->query($sql);
        while ($row = $result->fetchObject($objectToFetch)){
            $objArray[] = $row;
        }
        $this->disconnect();
        return $objArray;
    }

    public function getObjectsGeneral($table_name, $condition, $objectToFetch){
        //function checks if objects exists in table and returns ARRAY(!) of objects, or NULL
        $this->connect();
        $objArray = Array();

        $sql="SELECT * FROM `".$table_name."`".$condition;
        //echo "<br>".$sql."<br>";                                         //print object

        $result = $this->connection->query($sql);
        while ($row = $result->fetchObject($objectToFetch)){
            $objArray[] = $row;
        }

        $this->disconnect();
        return $objArray;
    }

    public function getObjectsDistinct($table_name, $columns, $objectToFetch){
        //SELECT columns FROM table;
        $this->connect();
        $objArray = Array();

        $sql="SELECT DISTINCT ".$columns." FROM `".$table_name."`";
        //echo $sql."<br>";                                         //print object

        $result = $this->connection->query($sql);
        while ($row = $result->fetchObject($objectToFetch)){
            $objArray[] = $row;
        }
        /*
        echo "<pre>";
        var_dump($objArray);
        echo"</pre>";
        */
        $this->disconnect();
        return $objArray;
    }

    function deleteObjectsGeneral($table_name, $condition){
        $this->connect();
        $sql = "DELETE FROM `".$table_name."`".$condition;
        $this->connection->query($sql);
        $this->disconnect();
    }

    //-------------------------------------------------------------------------Additional (required here):
    function stringToXml($htmlStr)
    {
        $xmlStr = str_replace('<', '&lt;', $htmlStr);
        $xmlStr = str_replace('>', '&gt;', $xmlStr);
        $xmlStr = str_replace('"', '&quot;', $xmlStr);
        $xmlStr = str_replace("'", "&#39;", $xmlStr);
        $xmlStr = str_replace("&", '&amp;', $xmlStr);
        return $xmlStr;
    }
    function xmlToString($htmlStr)
    {
        $xmlStr=str_replace('&lt;','<',$htmlStr);
        $xmlStr=str_replace('&gt;','>',$xmlStr);
        $xmlStr=str_replace('&quot;','"',$xmlStr);
        $xmlStr=str_replace("&#39;","'",$xmlStr);
        $xmlStr=str_replace('&amp;',"&",$xmlStr);
        return $xmlStr;
    }

    //-------------------------------------------------------------------------users:
    public function signinUser(string $signinMail, string $signinPass){
        $this->connect();

        //checking if user exists:
        $statement = $this->connection->prepare("SELECT * FROM users WHERE userEmail=:signinMail");
        $statement->execute([':signinMail'=>$signinMail]);
        $userFromDB=$statement->fetchObject('User');

        if ($userFromDB == null)    //user not found:
        {
            $ans = "Email entered wasn't found in our database.";
        }
        else                        //user found:
        {
            //verifying password:
            $realPassword = $userFromDB->getUserPassword();
            $verify=password_verify($signinPass,$realPassword);

            if ($verify)
            {
                $_SESSION['user']= $userFromDB->getUserID();                     //saving user's ID in $_SESSION
                $ans = 1;
            }
            else
            {
                $ans = "Password is incorrect.";
            }
        }
        $this->disconnect();
        return $ans;
    }



    public function getSignedInUserData()
    {
        if (isset($_SESSION['user'])) {

            $this->connect();

            $statement = $this->connection->prepare("SELECT * FROM users WHERE userID=:id");
            $statement->execute([':id'=>$_SESSION['user']]);
            $userFromDB=$statement->fetchObject('User');
            $userFromDB->setUserPassword("Hi there!");

            $this->disconnect();

            return $userFromDB;
        }
    }

    public function editUserData(User $u){

        $this->connect();
        //var_dump($u);

        $fname = $u->getUserFName();
        $lname = $u->getUserLName();
        $mail = $u->getUserEmail();
        //$pass = $u->getUserPassword();
        $phone = $u->getUserPhone();
        $birth = $u->getUserBirthday();
        $about = $u->getUserAbout();
        $status = 1;

        //Check if user already exists:
        $sql = "SELECT * FROM users WHERE userEmail='$mail'";
        $statement = $this->connection->prepare($sql);
        $statement->execute();

        $result=$statement->fetchObject('User');
        if ($result == null){
            echo "Problem updating data<br>";
        }
        else
        {
            $sql = "UPDATE `users` SET `userFName`='$fname',`userLName`='$lname',`userPhone`='$phone',`userBirthday`='$birth',`userAbout`='$about' WHERE userEmail='$mail'";
            $statement = $this->connection->prepare($sql);
            $statement->execute();
        }

        $this->disconnect();
    }
//-------------------------------------------------------------------------pets:
    public function printPetBasic(Pet $p)
    {
        $this->connect();
        //echo "<tr><td>".$p->getPetName()."</td><td>".$p->getPetType()."</td><td>".$p->getPetBirthday()."</td><td>".$p->getPetSex()."</td><td>edit</td><td>delete</td>";
        $str="";

        //$str.="ID: ".$p->getPetID()."<br>";
        $str.="Name: ".$p->getPetName()."<br>";
        $str.="Type: ".$p->getPetType()."<br>";
        $str.="Sex: ".$p->getPetSex()."<br>";
        $str.="Birthday: ".$p->getPetBirthday()."<br>";
        $this->disconnect();
        return $str;
    }
    public function printPetExtra(Pet $p)
    {
        $this->connect();
        //echo "<tr><td>".$p->getPetName()."</td><td>".$p->getPetType()."</td><td>".$p->getPetBirthday()."</td><td>".$p->getPetSex()."</td><td>edit</td><td>delete</td>";
        $str="";
        $str.="Food Habbits: ".$p->getPetFood()."<br>";
        $str.="Temper: ".$p->getPetTemper()."<br>";
        $str.="About: ".$p->getPetAbout()."<br>";
        $this->disconnect();
        return $str;
    }

    public function getPetsByUserID($id)
    {
        $this->connect();
        $petsArray = Array();
        $result = $this->connection->query("SELECT * FROM `pets` WHERE userID='$id'");

        while ($row = $result->fetchObject('Pet')) {
            $petsArray[] = $row;
        }
        $this->disconnect();
        /*
        echo "<pre>";
        print_r($personsArray);
        echo "</pre>";
        */
        return $petsArray;
    }

    public function getPetByID($id)
    {
        $this->connect();
        $result = $this->connection->query("SELECT * FROM `pets` WHERE petID='$id'");
        $row = $result->fetchObject('Pet');
        $this->disconnect();
        return $row;
    }


    //delete function:
    public function getPetImageString(Pet $p)
    {
        $this->connect();
        $location = "Images/Pets/";
        $filename = $p->getPetID().".JPG";
        if (!file_exists($location.$filename)){
            $location = "Images/Pets/General/";
            $filename = $p->getPetType().".png";
        }
        $altname = $p->getPetName();

        $this->disconnect();

        return "<img src='$location$filename' alt='$altname' title='$altname' class='img-fluid img-thumbnail'>";
    }
    //replaced with:
    public function getPetImageLocation(Pet $p)
    {
        $this->connect();
        $location = "Images/Pets/";
        $filename = $p->getPetID().".JPG";
        if (!file_exists($location.$filename)){
            $location = "Images/Pets/General/";
            $filename = $p->getPetType().".png";
            $filename = preg_replace('/\s+/ ', '', $filename);

        }
        //$altname = $p->getPetName();

        $this->disconnect();

        return $location.$filename;
    }





    //-----------------------------------------------------------------------------------

    public function sendMail($userEmail, $emlFileName){

        $text="Welcome to petSitter!\nPlease contact us with any question.\n\n";
        $text.="Start by adding your pets, exploring existing help requests and add your own.\n\n";
        $text.="*Please be careful when contacting people from across the web.";
        $tpl=file_get_contents("mail\\".$emlFileName.".eml");     //gets email template from eml file

        $mail=$tpl;
        $mail=strtr($mail,array("{TO}"=>$userEmail,"{TEXT}"=>$text,));
        list($head,$body)=preg_split("/\r?\n\r?\n/s",$mail,2);
        mail("","",$body,$head);

        return 1;
    }

    public function sendMail_ContactUs($emlFileName, $title, $message, $user_name, $user_email, $details) {
        $tpl=file_get_contents("mail\\".$emlFileName.".eml");     //gets email template from eml file
        $mail=$tpl;
        $mail=strtr($mail,array("{TO}"=>"aloniado@gmail.com","{MESSAGE}"=>$message, "{TOPIC}"=>$title, "{EMAIL}"=>$user_email, "{NAME}"=>$user_name, "{USER_DETAILS}"=>$details));


        list($head,$body)=preg_split("/\r?\n\r?\n/s",$mail,2);
        mail("","",$body,$head);

        return 1;
    }
//-------------------------------------------------------------------------help requests:



//-----}
}


