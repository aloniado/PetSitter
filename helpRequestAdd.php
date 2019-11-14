<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "classes/dbClass.php";
require_once "classes/dbClassHelpRequestFunctions.php";
require_once "classes/Pet.php";
require_once "classes/HelpRequest.php";
$db = new dbClass();
$dbhr = new dbClassHelpRequestFunctions();
$signedInUser=$db->getSignedInUserData();

$type = "hidden";                         //"hidden" or "text" : for location form fields (debugging)


//date and time:
$today = date("Y-m-d H:i:s");
$today = substr($today, 0, 10);
$oneyearfromnow = (string)((int)substr($today, 0, 4)+1).substr($today, 4, 6);
//echo "<br>".$oneyearfromnow;

//-----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------if form information sent:
if (isset($_POST['helpRequestSent']))
{

    echo "_POST:<br>";
    echo "<pre>";
    var_dump($_POST);
    echo "</pre>";


    $lnglat = $_POST['addrGPS'];
    $lat = substr($lnglat,1,strpos($lnglat,",")-1);
    $lnglat = substr($lnglat,strlen($lat)+2);
    $lng = substr($lnglat,strpos($lnglat,",")+1, strlen($lnglat)-2);
    $lng=round( $lng, 6);
    $lat=round( $lat, 6);

    if (isset($_POST['payment']))
        $pay=$_POST['paym'];
    else
        $pay=0;

    $help_data = array(
        'helpID' => NULL,
        'userID' => $_SESSION['user'],
        'helpLocLat' => $lat,
        'helpLocLng' => $lng,
        'helpLocCity' => $_POST['addrCity'],
        'helpLocStreet' => $_POST['addrStreet'],
        'helpLocNum' => $_POST['addrNum'],
        'helpLocCountry' => $_POST['addrCountry'],
        'helpRegisterTime' => date("Y-m-d H:i:s"),
        'helpStartTime' => $_POST['startdate']." ".$_POST['starttime'],
        'helpEndTime' => $_POST['enddate']." ".$_POST['endtime'],
        'helpPayment' => $pay,
        'helpAbout' => $_POST['helpAbout'],
        'helpType' => $_POST['type'],
        'userPhone' => $_POST['phone'],
        'helpStatus' => 1
    );
    //$db->insertLine("helprequests",$help_data);
    $dbhr->insertHelpRequest($help_data);

    $userLastHelp = $db->getObjectsGeneral("helprequests", " WHERE `userID`='".$_SESSION['user']."'ORDER  BY `helpRegisterTime` DESC LIMIT  1", "HelpRequest");

    echo "<br>ID= ".$userLastHelp[0]->getHelpID()."<br>";
    echo "<pre>";
    var_dump($userLastHelp);
    echo "</pre>";

//-------------------------------

    $petsArr = Array();             //will hold pet's IDs
    foreach ($_POST['pet'] as $k => $v){
            $petID = (int)$v;
            $petsArr[] = $petID;
            $pet_helpRequest = Array(
                'petID' => $petID,
                'helpID' => $userLastHelp[0]->getHelpID(),
                'userID' => $_SESSION['user'],
                'recordRegisterTime' => date("Y-m-d H:i:s")
            );
            $db->insertLine("pets_helprequests", $pet_helpRequest);
        //}
    }
    $_SESSION['userMessage']="Help request added successfully.";
    $_SESSION['nextPage']="myHelpRequests.php";
    header("Location: main.php");
    die();

}

?>



<!--
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>    <!-- jquery -->

<!-- google maps script:    -->
<script>
    //checkbox field add:
    jQuery(document).ready(function() {

        jQuery('#paymentOffer').change(function() {
            if ($(this).prop('checked')) {
                //$('<p>Text</p>').appendTo('#paymentAmount');
                //alert("You have elected to show your checkout history."); //checked
                $('#paymentAmount').append('<input type="number" min="0" class="form-control" placeholder="Payment amount (optional)" name="paym">');
            }
            else {
                $('#paymentAmount').text('');
            }
        });

    });



    // This example displays an address form, using the autocomplete feature
    // of the Google Places API to help users fill in the information.

    // This example requires the Places library. Include the libraries=places
    // parameter when you first load the API. For example:
    // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

    var placeSearch, autocomplete;
    var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
    };

    function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
    }

    function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

        for (var component in componentForm) {
            document.getElementById(component).value = '';
            document.getElementById(component).disabled = false;
        }

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            if (componentForm[addressType]) {
                var val = place.address_components[i][componentForm[addressType]];
                document.getElementById(addressType).value = val;
            }
        }
        //alert('(sendLocation function) loc var = ' + place.geometry.location);      //alert
        $("#newHelpForm input[name=addrGPS]").val(place.geometry.location);

    }

    // Bias the autocomplete object to the user's geographical location,
    // as supplied by the browser's 'navigator.geolocation' object.
    function geolocate() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                var circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy
                });
                autocomplete.setBounds(circle.getBounds());
            });
        }
    }



</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDWfRbSTsQxwFkIlX2BuvQCgUglN8Hun-U&libraries=places&callback=initAutocomplete"
        async defer></script>


<script>    <!-- form validation -->
    function validateForm() {
    var errorMsg = "";
        //--------------------------------------------address validation:
        if (document.forms["addHRform"]["addrGPS"].value === "") {
            errorMsg += "Could not find location.\nStart entering an address and select from dropdown\n";
        }
        else if (document.forms["addHRform"]["addrCountry"].value === "" || document.forms["addHRform"]["addrCountry"].value !== "Israel") {
            errorMsg += "Address must be in Israel\n";
        }
        else if (document.forms["addHRform"]["addrCity"].value === "") {
            errorMsg += "Address must contain a city\n";
        }

        //--------------------------------------------pets selection:
        var pets = document.getElementsByName('pet[]'), count = 0;
        for (var i=0; i<pets.length; i++)
            if (pets[i].checked)
                count++;
        if (count<1)
            errorMsg += "You must select at least one pet\n";

        //--------------------------------------------dates:
        d1 = new Date(document.getElementById("datestart").value);
        d2 = new Date(document.getElementById("dateend").value);
        if (d2<d1)
            errorMsg += "End date must be left empty, or be after start date.\n";

        //--------------------------------------------error message:
        if (errorMsg !== ""){
            alert(errorMsg);
            return false;
        }
    }
</script>


<!------------------------------------------------------------------------------>
<div class="d-flex justify-content-center">
<div class="card">
    <h5 class="card-header">New help request:</h5>
    <div class="card-body">
    <form action="helpRequestAdd.php" onsubmit="return validateForm()" method=post id="newHelpForm" name="addHRform">
    <label>Select one or more pets:</label>
    <?php
    $myPetsArray = $db->getPetsByUserID($_SESSION['user']);
    if ($myPetsArray == null)
        echo "no pets found in DB.";
    else
    {
        $petsStr="<br><div class='card-deck'>";
        foreach ($myPetsArray as $p) //each $p is a Pet object
        {
            $image_str = "'background-image:url(".$db->getPetImageLocation($p).")';";
            $petsStr.="<div class='pet-img' style=$image_str><div class='pet-name'>".$p->getPetName()." - ".$p->getPetType()."</div></div>";
            $petsStr.="<input type='checkbox' name='pet[]' id='petsInHelp' value='".$p->getPetID()."'>";
        }
        $petsStr.="</div>";
        echo $petsStr;
    }
    ?>
    <!--address google search -->
    <div class="form-group">
        <br><label>Search address, Area or location:</label>
        <div class="form-row">
            <div class="col">
                <input class="form-control" id="autocomplete" placeholder="Help address" onFocus="geolocate()" type="text">
                <small id="emailHelp" class="form-text text-muted">Search address, Area or location, and Select address from dropdown.</small>
            </div>
        </div>
    </div>
    <!--address autofill -->
    <div class="form-group">
        <input name="addrNum" class="field" id="street_number" placeholder="Number" type="<?php echo $type ?>">
        <input name="addrStreet" class="field" id="route" placeholder="Street" type="<?php echo $type ?>">
        <input name="addrCity" class="field" id="locality" placeholder="City" type="<?php echo $type ?>">
        <input name="addrArea" class="field" id="administrative_area_level_1" placeholder="Area" type="<?php echo $type ?>">
        <input name="addrZip" class="field" id="postal_code" placeholder="zip code" type="<?php echo $type ?>">
        <input name="addrCountry" class="field" id="country" placeholder="Country" type="<?php echo $type ?>">
        <input name="addrGPS" placeholder="GPS" id="gpss" type="<?php echo $type ?>">
    </div>
    <!--more fields -->

    <div class="form-group">
        <div class="form-row">
            <div class="col">
                <label>Help type:</label>
                <select class="form-control" name="type">
                    <option value="Feeding">Feeding</option>
                    <option value="Walk">Walk</option>
                    <option value="House Sitting">House Sitting</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group">

        <div class="row">
            <div class="col">
                <label>Help start date:</label>
                <input type="date" class="form-control" id="datestart" placeholder="Start date" name="startdate" min='<?php echo $today;?>' max="<?php echo $oneyearfromnow?>" required>
            </div>
            <div class="col">
                <label>Help end date:</label>
                <input type="date" class="form-control" id="dateend" placeholder="End date date" name="enddate" min='<?php echo $today;?>' max="<?php echo $oneyearfromnow?>">
                <input type="time" class="form-control" placeholder="Start time" name="starttime" hidden>
            </div>
        </div>
    </div>

        <!--
            <div id="enddates"></div>
            <div class="form-group">
                <label>Help end time:</label>
        <div class="row">
            <div class="col">

            </div>
            <div class="col">
                <input type="time" class="form-control" placeholder="End time" name="endtime" hidden>
            </div>
        </div>
    </div>
-->
    <div class="form-group">
        <input type="checkbox" name="payment" value="yes" id="paymentOffer"> offering payment<br>
        <div id="paymentAmount"></div>
    </div>
    <div class="form-group">
        <label>Help description:</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" name="helpAbout" rows="3" required></textarea>
    </div>
    <div class="form-group">
        <label>Phone number for contact:</label>
        <div class="form-group">
            <input type="tel" class="form-control" id="exampleInputPhone1" aria-describedby="emailHelp" placeholder="Enter Phone Number"  name="phone" value="<?php echo $signedInUser->getUserPhone()?>" pattern="[0-9]{9,10}" title="please enter a valid phone number" required>
        </div>
    </div>

    <button type="submit" class="btn btn-primary" name="helpRequestSent">Publish new help request</button>
</form>
    </div>

</div>
</div>
</div>
