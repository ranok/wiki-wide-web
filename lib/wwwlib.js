/*
    Wiki Wide Javascript Functions
    Copyright: 2006 Wiki Wide Web
    Author: Jacob I. Torrey
*/
var address = 'null';

function startWWW() {
    if(address != parent.page.location.href) {
    	document.getElementById("status").innerHTML = "Wiki Wide Web Status: Applying Diffs";
        address = parent.page.location.href;
        document.getElementById("addressentry").value = address.replace("http://", "");
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

function getDiffs(XMLHttpRequestObject, address) {
    var getaddress = "http://localhost/wikiww/getDiffs.php?address=";
    if(XMLHttpRequestObject) {
    //alert(getaddress + escape(address));
        XMLHttpRequestObject.open("GET", getaddress + escape(address));
        
        XMLHttpRequestObject.onreadystatechange = function() {
            if(XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                var xmlDoc = XMLHttpRequestObject.responseXML;
                var toreplace = xmlDoc.documentElement.getElementsByTagName("toreplace");
                var replacewith = xmlDoc.documentElement.getElementsByTagName("replacewith");
                for(i = 0; i < toreplace.length; i++) {
                    doReplace(toreplace[i].childNodes[0].nodeValue, replacewith[i].childNodes[0].nodeValue);
                }
                document.getElementById("status").innerHTML = "Wiki Wide Web Status: Complete";
            }
        }
    }
    XMLHttpRequestObject.send(null);
}

function restart() {
    window.setTimeout("startWWW();", 1000);
}

function doReplace(toreplace, replacewith) {
    var html = parent.document.getElementById("main").contentDocument.body.innerHTML;
    html = html.replace(toreplace, replacewith);
    parent.document.getElementById("main").contentDocument.body.innerHTML = html;
}
