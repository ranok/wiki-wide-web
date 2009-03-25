<!--
var divid;

function showSignUp() {
	var loginbox = document.getElementById("login");
	loginbox.innerHTML = '<p class="center big bold">Signup for Wiki Wide Web Beta</p><form action="dosignup" method="post">Please fill out the information below:<br /><br /><fieldset>Username: <input type="text" name="username" /><br />Password: <input type="password" name="password" /><br />E-mail: <input type="text" name="email" /></fieldset><p class="center"><input type="submit" value="Sign Up" /></p></form>';
	return false;
}

function addFriend() {
	var username = document.getElementById("friendusername").value;
	document.getElementById("friendstatus").innerHTML = 'Adding friend: ' + username;
	divid = document.getElementById("friendstatus");
	ajaxGetRequest("ajax/doaddfriend.php?fusername=" + escape(username));
}

function clearFriendSpan() {
	document.getElementById("friendstatus").innerHTML = '';
}

function ajaxGetRequest(url) {
	var xmlHttp;
	try {
          // Firefox, Opera 8.0+, Safari
          xmlHttp = new XMLHttpRequest();
        } catch (e) {
          // Internet Explorer
          try {
            xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
          } catch (e) {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
          }
        }

        if (xmlHttp == null) {
          alert ("Your browser does not support AJAX!");
          return;
        } 
        xmlHttp.onreadystatechange = function() {
        	if(xmlHttp.readyState == 4) {
        		divid.innerHTML += "<br />" + xmlHttp.responseText;
        		window.setTimeout("clearFriendSpan();", 3000);
        	}
        }
        xmlHttp.open("GET", url, true);
        xmlHttp.send(null);
}
-->
