


<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//unset($_SESSION['refinement']);   //del
require_once "classes/dbClass.php";
require_once "classes/Pet.php";
require_once "classes/HelpRequest.php";
require_once "classes/Pet_HelpRequest.php";
require_once "classes/dbClassPetsFunctions.php";
require_once "classes/dbClassHelpRequestFunctions.php";
require_once "classes/dbClassUserFunctions.php";
require_once "classes/dbClassRankingFunctions.php";
require_once "classes/dbClassInterested.php";

//$db = new dbClass();
$dbhr = new dbClassHelpRequestFunctions();
$dbp = new dbClassPetsFunctions();
$dbu = new dbClassUserFunctions();
//------------------------------------------------------


if (isset($_POST['redirect'])) {    //redirecting user after like was clicked
    $_SESSION['helpRequestView']=$_POST['helpRequestView'];
    $_SESSION['nextPage']="helpRequestView.php";
    header("Location: main.php");
    die();
}
//------------------------------------------------------extended help request:
if (isset($_SESSION['helpRequestView']))
{
    $helpRequestID = $_SESSION['helpRequestView'];

    $help = $dbhr->getHelpRequestByID($helpRequestID);  //help request
    $user = $dbu->getUserByID($help->getUserID());          //user
    $pets = $dbp->getPetsArrayByHelpID($helpRequestID); //array of pets in help request
    if ($pets == null)
    {
        $petsStr="No pets in request";
    }
    else
    {
        $petsStr="";
        foreach ($pets as $p) //each $p is a Pet object
        {
            $image_str = "'background-image:url(".$db->getPetImageLocation($p).")';";
            $petsStr.="<div class='pet-img' style=$image_str><div class='pet-name'>".$p->getPetName()." - ".$p->getPetType()."</div></div>";
        }
        $petsStr.="<br>";
    }

    $userName = $user->getUserFName()." ".$user->getUserLName();




?>
    <div class="card"  id="helpRequestExtendedData">
  <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs pull-right"  id="myTab" role="tablist">
      <li class="nav-item">
       <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Help request overview</a>
      </li>
        <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Pets in this request</a>
        </li>
      <li class="nav-item">
        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php echo $userName."'s profile"; ?></a>
      </li>

    </ul>
  </div>
  <div class="card-body">
   <div class="tab-content" id="myTabContent">
             <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                 <?php
                    //------------------------ help request overview:

                 $str="";
                 $helpHeader="<b>Request By:</b> ".$userName;
                 if ($_SESSION['user']==$help->getUserID())
                     $helpHeader="Requested By me";
                 $str.=$helpHeader."<br>";

                 $str.="<table><tr><td width='30%'>";
                 //$str.="Help ID: ".$k->getHelpID()."<br>";

                 $str.="<b>Type: </b>".$help->getHelpType()."<br>";

                 //$str.="<b>Phone Number: </b>".$help->getUserPhone()."<br>";

                 //Help time handling:
                 $helpTimeStr="";
                 if ($help->getHelpEndTime() == "0000-00-00 00:00:00"){
                     if ($help->getHelpStartTime() == "0000-00-00 00:00:00"){
                         $helpTimeStr .= "<b>Time not specified</b><br>";
                     }
                     else
                         $helpTimeStr .= "<b>One day help: </b>".$help->getHelpStartTime()."<br>";
                 }
                 else
                     $helpTimeStr .= "<b>Time: </b>".$help->getHelpStartTime()." - ".$help->getHelpEndTime()."<br>";
                 $str.=$helpTimeStr;

                 $str.="<b>Location: </b>".$help->getHelpLocCity().", ".$help->getHelpLocStreet()."<br>";
                 $str.="<b>Payment offered: </b>".$help->getHelpPayment()."[NIS]<br><br>";
                 $str.="<b>Help description:</b><br>".$help->getHelpAbout()."<br>";

                 $str.="</td><td align='center'>";
                 $str.=$petsStr;

                 $str.="</td></tr></table>";
                 echo $str;
                 ?>
             </div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                  <?php
                  //------------------------ profile:
                  $str="";
                  $str.="";
                      //$str.=$user->getUserFName()." ".$user->getUserLName()."'s Profile";



                  $str.="<b>Name: </b>".$user->getUserFName()." ".$user->getUserLName()."<br>";
                  if ($user->getUserBirthday()=="0000-00-00")
                      $str.="<b>Birthday: </b>Not Entered<br>";
                  else
                      $str.="<b>Birthday: </b>".$user->getUserBirthday()."<br>";

                  //rating:
                  $dbr = new dbClassRankingFunctions();
                  $userRank = $dbr->getUserRankByID($user->getUserID());
                  $userRankStr = $userRank;
                  if (number_format((float)$userRankStr, 2, '.', '') != 0)
                      $str.="<b>Rating: </b>".number_format((float)$userRankStr, 2, '.', '')."<br>";
                  else
                      $str.="<b>Rating: </b>No Rating yet<br>";

                  //About:
                  if ($user->getUserAbout() == "")
                      $str.="<b>About: </b>Not Entered<br>";
                  else
                      $str.="<b>About: </b>".$user->getUserAbout()."<br>";

                  echo $str;      //profile card


                  //getting User's Pets:
                  $petsArray = $db->getObjectsGeneral("pets", " WHERE `userID`='".$user->getUserID()."'", "Pet");

                  //-------------------------------building pets string:
                  if ($petsArray == null){
                      $petsStr="<br><b>No pets found</b>";
                  }
                  else {
                      $petsStr="<br><b>All ".$user->getUserFName()." ".$user->getUserLName()."'s pets:'</b>";

                      $petsStr.="<br>";
                      foreach ($petsArray as $p) //each $p is a Pet object
                      {
                          $image_str = "'background-image:url(".$db->getPetImageLocation($p).")';";
                          $petsStr.="<div class='pet-img' style=$image_str><div class='pet-name'>".$p->getPetName()."</div></div>";
                      }
                  }
                  echo $petsStr."<br>";


                  //-----------------------------------------------------reviews:

                  $ranksArr = $db->getObjectsGeneral("rankings", " WHERE `rankedUserID` = '".$user->getUserID()."'", "Ranking");
                  $str = "";
                  if (!empty($ranksArr)) {
                      $str="<br><b>".$user->getUserFName()." ".$user->getUserLName()."'s  recent reviews:<br><br></b>";
                      foreach ($ranksArr as $k => $v) {
                          $dbu = new dbClassUserFunctions();
                          $ReviewingUser = $dbu->getUserByID($v->getRankingUserID());
                          $str .= "Rated " . $v->getRankRank() . " By " . $ReviewingUser->getUserFName() . " " . $ReviewingUser->getUserLName() . ": <br>'";
                          $str .= "<b><i>" . $v->getRankAbout() . "'</i></b><br>";
                      }
                  }
                  echo "<div id='HelpRequest-reviews'><br>".$str."</div>";      //reviews

                  ?>
  </div>
  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
      <?php
      //------------------------ pets:

      $myPetsArray = $pets;
      //---*******************
      $mypetsstr="";

      if ($myPetsArray == null)
          $mypetsstr.="No pets in this request.";
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

              $petstr.="</div>";
              $petstr.="</div>";

              echo $petstr;

          }
          $mypetsstr="</div>";

          $mypetsstr.="</p></div>";


      }
      $mypetsstr.="</div></div>";
      echo $mypetsstr;
      ?>
  </div>
        </div>
  </div>
</div>
<?php

    //------------------------------------------------------ map view:
    $str="";
    $str.="<div id='map_singleDot' class='card'></div><br>";
    echo $str;


    //------------------------------------------------------ Like:
    if ($help->getUserID() != $_SESSION['user'])
    {
        $dbi = new dbClassInterested();
        $is_interested = $dbi->GetInterestedByIDs($_SESSION['user'], $helpRequestID);
        //var_dump($is_interested);
        if ($is_interested == null)
        {
            $likeButton = "'background-image:url(images/like-empty.svg)';";
            $tooltipText = "Liking this help request will leave your details to the publisher so he can get back to you";
            $liked = "no";
        }
        else
        {
            $likeButton = "'background-image:url(images/like-full.svg)';";
            $tooltipText = "You've already liked this help request! The publisher has your details";
            $liked = "yes";
        }
        $strLike = "<div id='".$helpRequestID."-".$_SESSION['user']."-".$liked."' style=$likeButton class='like_pressed'><span class='like-tooltip-text' id='tooltip-text-".$helpRequestID."'>".$tooltipText."</span>";
        $strLike .= "</div>";
        echo $strLike;
    }

    //---------------------------------------------------------------------
/*

*/
}
?>

<script type="text/javascript">
    $('div.like_pressed').click(function(){
        var target = event.target || event.srcElement;
        var id = target.id;
        var strArr = id.split("-");
        //alert("userID: "+strArr[1]+"  helpID: "+strArr[0]);

        $.ajax({
            type: "POST",
            data : { user : strArr[1], help : strArr[0] },
            url: "scripts/HelpRequestLikeAndUnlike.php",
            success: function(msg){
                //alert("ok");
                //window.location = "main.php";
                var status = strArr[2];
                if (status === "yes")
                {
                    //alert("need to unlike");
                    document.getElementById(strArr[0]+"-"+strArr[1]+"-yes").style.backgroundImage="url(images/like-empty.svg)";
                    document.getElementById(strArr[0]+"-"+strArr[1]+"-yes").id=strArr[0]+"-"+strArr[1]+"-no";
                    document.getElementById("tooltip-text-"+strArr[0]).innerHTML = "Liking this help request will leave your details to the publisher so he can get back to you";
                }
                else
                {
                    //alert("need to like");
                    document.getElementById(strArr[0]+"-"+strArr[1]+"-no").style.backgroundImage="url(images/like-full.svg)";
                    document.getElementById(strArr[0]+"-"+strArr[1]+"-no").id=strArr[0]+"-"+strArr[1]+"-yes";
                    document.getElementById("tooltip-text-"+strArr[0]).innerHTML = "You've already liked this help request! The publisher has your details";
                }
            }
        });
    });

</script>


<script>
    var customLabel = {
        Walk: {
            label: 'W'
        },
        Feeding: {
            label: 'F'
        },
        HouseSitting: {
            label: 'H'
        }
    };

    function initMap() {
        var map = new google.maps.Map(document.getElementById('map_singleDot'), {
            center: new google.maps.LatLng(32.085300, 34.781800),
            zoom: 7
        });
        var infoWindow = new google.maps.InfoWindow;

        // Change this depending on the name of your PHP or XML file
        downloadUrl('maps/PHPtoXML_oneDot.php', function(data) {
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName('marker');
            Array.prototype.forEach.call(markers, function(markerElem) {
                var id = markerElem.getAttribute('id');
                var name = markerElem.getAttribute('name');
                var address = markerElem.getAttribute('address');
                var type = markerElem.getAttribute('type');
                var point = new google.maps.LatLng(
                    parseFloat(markerElem.getAttribute('lat')),
                    parseFloat(markerElem.getAttribute('lng')));

                var infowincontent = document.createElement('div');
                var strong = document.createElement('strong');
                strong.textContent = name
                infowincontent.appendChild(strong);
                infowincontent.appendChild(document.createElement('br'));

                var text = document.createElement('text');
                text.textContent = address
                infowincontent.appendChild(text);
                var icon = customLabel[type] || {};
                var marker = new google.maps.Marker({
                    map: map,
                    position: point,
                    label: icon.label
                });
                marker.addListener('click', function() {
                    infoWindow.setContent(infowincontent);
                    infoWindow.open(map, marker);
                });
            });
        });
    }



    function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
            if (request.readyState == 4) {
                request.onreadystatechange = doNothing;
                callback(request, request.status);
            }
        };

        request.open('GET', url, true);
        request.send(null);
    }

    function doNothing() {}
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDWfRbSTsQxwFkIlX2BuvQCgUglN8Hun-U&callback=initMap">
</script>


<?php

//echo $_SESSION['helpRequestView'];
//unset($_SESSION['helpRequestView']);
?>





