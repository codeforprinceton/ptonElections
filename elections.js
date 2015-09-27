var xmlHttp

function showInfo(str)
{ 
xmlHttp=GetXmlHttpObject();

if (xmlHttp==null)
  {
  alert ("Your browser does not support AJAX!");
  return;
  }
var url = "elections.php";    
url=url+"?q="+str;
url=url+"&sid="+Math.random(); 
xmlHttp.onreadystatechange=stateChanged;
xmlHttp.open("GET",url,true);
xmlHttp.send(null);
}

function stateChanged()
{
if (xmlHttp.readyState==4)
{ 
document.getElementById("input").innerHTML = xmlHttp.responseText; 
}
}

function GetXmlHttpObject()
{
var xmlHttp=null;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlHttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
return xmlHttp;
}
//----------------------//----------------------//----------------------//----------------------


function ajaxGetInfo(votes, candidateID)
  {
     xmlHttp = GetXmlHttpObject();
   var d = document.getElementById("district").value;
   var m = document.getElementById("machine").value;
   
   var info = d + "," + m + "," + candidateID + "," + votes;
   info = info.toString();
   
   if(xmlHttp!=null)
   {
    var url = "save.php";    
    url=url+"?q="+info;
    url=url+"&sid="+Math.random(); 
    xmlHttp.onreadystatechange=stateChangedText;
    xmlHttp.open("GET",url,true);
    xmlHttp.send(null);
   }
   else document.getElementById('info').value = "Error retrieving data!";
  }

function stateChangedText()
{
    if (xmlHttp.readyState==4){ 
        document.getElementById("info").innerHTML = xmlHttp.responseText; 
    }
}

//----------------------//----------------------//----------------------//----------------------

function showSpreadsheet(){
     xmlHttp = GetXmlHttpObject();

if(xmlHttp!=null)
   {
    var url = "spreadsheet.php";
    //url=url+"?q="+"null";
    xmlHttp.onreadystatechange=stateChanged;
    xmlHttp.open("GET",url,true);
    xmlHttp.send(null);
   }
   else document.getElementById('input').value = "Error retrieving data!";
}