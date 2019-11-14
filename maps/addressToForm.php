<!DOCTYPE html>
<html>
<head>
    <title>Place Autocomplete Address Form</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">

    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">

</head>

<body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>    <!-- jquery -->
<script>
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
            document.getElementById(component).disabled = true;
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



<form action="" method=post id="newHelpForm">
    <!--address google search -->
    <div class="form-group">
        <input id="autocomplete" placeholder="Enter your address" onFocus="geolocate()" type="text">
    </div>
    <!--address autofill -->
    <div class="form-group">
        <input name="addrNum" class="field" id="street_number" disabled="true" placeholder="Number">
        <input name="addrStreet" class="field" id="route" disabled="true" placeholder="Street">
        <input name="addrCity" class="field" id="locality" disabled="true" placeholder="City">
        <input name="addrArea" class="field" id="administrative_area_level_1" disabled="true" placeholder="Area">
        <input name="addrZip" class="field" id="postal_code" disabled="true" placeholder="zip code">
        <input name="addrCountry" class="field" id="country" disabled="true" placeholder="Country">
        <input name="addrGPS" type="text" placeholder="GPS" id="gpss" disabled="true">
    </div>
    <!--more fields -->
    <div class="form-group">
        <label for="exampleFormControlTextarea1">Example textarea</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" name="helpAbout" rows="3"></textarea>
    </div>





</form>



</body>
</html>