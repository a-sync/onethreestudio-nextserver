//ajax posztoló
var req = false;
function ajax_post(url, parameters) {//küldendõ tömb feldolgozás
//a tömb egyik eleme a küldött szöveg (lefelére elõszedi (üres enterre beírja?))
   req = false;
   if (window.XMLHttpRequest)
   {//Mozilla, Safari
      req = new XMLHttpRequest();
      if (req.overrideMimeType) req.overrideMimeType("text/html");
   }
   else if (window.ActiveXObject)
   {//IE
      try { req = new ActiveXObject("Msxml2.XMLHTTP"); }
      catch (e)
      {
        try { req = new ActiveXObject("Microsoft.XMLHTTP"); }
        catch (e) {}
      }
   }
   if (!req) return false;
   
   req.onreadystatechange = filter;
   req.open("POST", url, true);
   req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
   req.setRequestHeader("Content-length", parameters.length);
   req.setRequestHeader("Connection", "close");
   req.send(parameters);
   
   return true;
}

//panel adatküldõ
var input_elem = "i";
var textfield_elem = "t";
var post_url = "exec.php";
function filter()//kapott tömb feldolgozás
{
   if (req.readyState == 4)
   {
      var et = document.getElementById(textfield_elem);
      if (req.status == 200)
      {
         var result = req.responseText;
         if (result == "cls") et.innerHTML = "";
         else et.innerHTML += result;

         //scroll to bottom
         //et.scrollTop = 0;//fix?
         et.scrollTop = et.scrollHeight; 
      }
      else et.innerHTML += "There was a problem with the request.<br/>";
   }
}

//panel kezelõ
function i(e)
{
  if (e.keyCode == 13 || e.which == 13)
  {
    var ei = document.getElementById(input_elem);
    var data = "i=" + escape(encodeURI(ei.value));
    if(ajax_post(post_url, data)) ei.value = "";
    else
    {
      var et = document.getElementById(textfield_elem);
      et.innerHTML += "Cannot create XMLHTTP instance.<br/>";
    }
  }
}

var ctrl = false;
function t0(e)
{
  if (e.keyCode == 17 || e.which == 17) ctrl = true;
  if (ctrl == false) f();
}

function t1(e)
{
  if (e.keyCode == 17 || e.which == 17) ctrl = false;
  if (ctrl == false) f();
}

function f()
{
  document.getElementById(input_elem).focus();
}

//aktív panel funkciók
function p0(d)
{
  var data = "i=" + escape(encodeURI(d));
  if(!ajax_post(post_url, data))
  {
    var et = document.getElementById(textfield_elem);
    et.innerHTML += "Cannot create XMLHTTP instance.<br/>";
  }
}

function p1(d)
{
  document.getElementById(input_elem).value = d;
}

function p2(d)
{
  document.getElementById(input_elem).value += d;
}

//event befogás
if (!document.all) { document.captureEvents(Event.KEYDOWN); }
document.onkeydown = t0;
if (!document.all) { document.captureEvents(Event.KEYUP); }
document.onkeyup = t1;