/*
    Wiki Wide Javascript Functions
    Copyright: 2007 Wiki Wide Web
    Author: Jacob I. Torrey
*/

// Begin Global Vars
var enabled = true;
var siteroot = "http://dev.wikiwideweb.com/";
var address = 'null';
var checktime = 1500;
var toreplacearray = new Array();
var replacewitharray = new Array();
var commentarray = new Array();
var userarray = new Array();
var diffidarray = new Array();
var diffnum;
// End Global Vars

// Begin Startup
window.addEventListener("load", function() {loadPrefs();}, false);
// End Startup

function startWWW() {
    if(enabled) {
    	if(address != window.content.document.location.href && notModified()) {
            clearMenu();
	    diffnum = 0;
    	    toreplacearray = new Array();
	    replacewitharray = new Array();
	    commentarray = new Array();
   	    userarray = new Array();
   	    diffidarray = new Array();
    	    document.getElementById("www-status").label = "WWW Status: Loading";
    	    address = window.content.document.location.href;
   	    var XMLHttpRequestObject = false;
     	   if(window.XMLHttpRequest) {
      	      XMLHttpRequestObject = new XMLHttpRequest();
     	   } else {
     	       XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
     	   }
      	  getDiffs(XMLHttpRequestObject, address);
      	  restart();
   	 } else {
		restart();
   	 }
   }
}

function refresh() {
	if(enabled) {
		var temp = address;
		address = 'null';
		gotoPage(temp);		
	}
}

function notModified() {
	if(window.content.document.getElementById("www-modded")) {
		return false;
	}
	return true;
}

function setModified() {
	var html = window.content.document.body.innerHTML;
	html += '<span id="www-modded"><!-- Modded by Wiki Wide Web --></span>';
	window.content.document.body.innerHTML = html;
}

function restart() {
	window.setTimeout("startWWW()", checktime);
}

function getDiffs(XMLHttpRequestObject, address) {
    var getaddress = siteroot + "getdiffs/address/";
    if(XMLHttpRequestObject) {
        XMLHttpRequestObject.open("GET", getaddress + escape(address));
        
        XMLHttpRequestObject.onreadystatechange = function() {
            if(XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                var xmlDoc = XMLHttpRequestObject.responseXML;
                var toreplace = xmlDoc.documentElement.getElementsByTagName("toreplace");
                var replacewith = xmlDoc.documentElement.getElementsByTagName("replacewith");
                var diffs = xmlDoc.documentElement.getElementsByTagName("diff");
                var comments = xmlDoc.documentElement.getElementsByTagName("comment");
                var users = xmlDoc.documentElement.getElementsByTagName("user");
                for(i = 0; i < toreplace.length; i++) {
                    diffnum++;
                    toreplacearray[i] = toreplace[i].childNodes[0].nodeValue;
                    replacewitharray[i] = replacewith[i].childNodes[0].nodeValue;
                    if(comments[i].hasChildNodes()) {
                    	commentarray[i] = comments[i].childNodes[0].nodeValue;
                    } else {
                    	commentarray[i] = '';
                    }
                    diffidarray[i] = diffs[i].attributes[0].nodeValue;
                    userarray[i] = users[i].childNodes[0].nodeValue;
                    doReplace(toreplace[i].childNodes[0].nodeValue, "<div style=\"font-style: italic; display: inline;\" title=\"" + userarray[i] + " - " + commentarray[i] + "\">" + replacewitharray[i] + "</div>");
                }
                populateList();
                setModified();
                document.getElementById("www-status").label = "WWW Status: Complete";
            }
        }
    }
    XMLHttpRequestObject.send(null);
}

function rank(dir) {
	var did = diffidarray[diffnum];
	if(diffnum > -1 && did > 0) {
		window.open(siteroot + "rate/did/" + did + "/dir/" + dir, "popupeditor", "location=0,height=425,width=600,status=1,resizeable=1");
	}
}

function populateList() {
	var difflist = document.getElementById("www-difflist");
	i = 0;
	j = 0;
	while(i < toreplacearray.length) {
		if(i == 0 || (userarray[i] != userarray[i - 1] || commentarray[i] != commentarray[i - 1])) {
			var temp = document.createElement("menuitem");
			temp.setAttribute("oncommand", "gotoDiff(" + i + ")");
			temp.setAttribute("label", (j + 1) + ". " + userarray[i] + " - " + commentarray[i]);
			difflist.appendChild(temp);
			j++;
		}
		i++
	}
}

function clearMenu() {
	var difflist = document.getElementById("www-difflist");
	var list = difflist.firstChild;
	while(list.nextSibling) {
		difflist.removeChild(list.nextSibling);
	}
}

function gotoDiff(num) {
	if(num < diffnum) {
		for(;diffnum > num; diffnum--) {
//			doReplace(replacewitharray[diffnum], toreplacearray[diffnum]);
			doReplace("<div style=\"font-style: italic;\" title=\"" + userarray[diffnum] + " - " + commentarray[diffnum] + "\">" + replacewitharray[diffnum] + "</div>", toreplacearray[diffnum]);
		}
	} else {
		for(;diffnum <= num; diffnum++) {
//			doReplace(toreplacearray[diffnum], replacewitharray[diffnum]);
                    	doReplace(toreplacearray[diffnum], "<div style=\"font-style: italic;\" title=\"" + userarray[diffnum] + " - " + commentarray[diffnum] + "\">" + replacewitharray[diffnum] + "</div>");	
		}
	}
	diffnum = num;
}

function doReplace(toreplace, replacewith) {
    var html = window.content.document.body.innerHTML;
    //    alert(toreplace);
    //alert(replacewith);
    html = html.replace(toreplace, replacewith);
    window.content.document.body.innerHTML = html;
}

function toggle() {
	if(enabled) {
		enabled = false;
		document.getElementById("www-enable").label = "WWW Disabled";
		var prefServ = Components.classes["@mozilla.org/preferences-service;1"].getService(Components.interfaces.nsIPrefBranch);
		prefServ = prefServ.getBranch("extensions.wikiwideweb.");
		prefServ.setBoolPref("enabled", false);
	} else {
		enabled = true;
		document.getElementById("www-enable").label = "WWW Enabled";
		var prefServ = Components.classes["@mozilla.org/preferences-service;1"].getService(Components.interfaces.nsIPrefBranch);
		prefServ = prefServ.getBranch("extensions.wikiwideweb.");
		prefServ.setBoolPref("enabled", true);
		restart();
	}	
}

function edit() {
	var editwin = window.open(siteroot + "edit/address/" + escape(address), "popupeditor", "location=0,height=425,width=600,status=1,resizeable=1");
	
}

function gotoPage(url) {
	window.content.document.location = url;
	window.content.focus();
}

function loadPrefs() {
	var prefServ = Components.classes["@mozilla.org/preferences-service;1"].getService(Components.interfaces.nsIPrefBranch);
	prefServ = prefServ.getBranch("extensions.wikiwideweb.");
	enabled = prefServ.getBoolPref("enabled");
	checktime = prefServ.getIntPref("refreshtime");
	siteroot = prefServ.getCharPref("server");

	if(!enabled) {
		document.getElementById("www-enable").label = "WWW Disabled";
	} else {
		startWWW();
	}	
}	
