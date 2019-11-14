'use strict'

function ValidatePassword() {
    if (document.getElementById('pass').value !== document.getElementById('passconf').value) {
        document.getElementById('message').innerHTML = "*Passwords don't match";
        document.getElementById('message').style.color = "red";
        document.getElementById('signupbutton').disabled = true;
    }
    else
    {
        document.getElementById('message').innerHTML = "";
            document.getElementById('signupbutton').disabled = false;
    }

}