

function init(where)
{
  var loc = document.location.pathname.split('?')[0];
  var _GET = get_variables();
  
  if(loc == '/' || loc == '/index.php')
  {
    //alert(document.getElementById('home_scroll'));
    //if(_cookie('kiemeltek') == 'lapozas') start_fade('content', 'pager');
    if(document.getElementById('home_scroll')) start_scroll(true, 'home_scroll');
    else start_fade('content', 'pager');
  }
  else if(loc == '/hirdetesek.php' && !isNaN(_GET['hirdetes']))
  {
    open_box(_GET['hirdetes']);
  }
  else if(loc == '/hirdetes-nyomtatasa.php' && !isNaN(_GET['hirdetes']) && _GET['nyomtatas'] == 'azonnal')
  {
    window.print();
  }
}

/* _get változók kigyűjtése */
function get_variables(search)
{
  _get = [];
  if(!search) search = document.location.search.substr(1).split('&');

  for(var i in search)
  {
    var j = search[i].split('=');
    _get[j.shift()] = j.join('=');
  }

  return _get;
}

/* események */
function set_event_status(event, esid)
{
  if(!esid || isNaN(esid)) return false;

  var o = event.currentTarget || event.srcElement;
  
  //o.style.backgroundColor = o.options[o.selectedIndex].style.backgroundColor;
  //alert(o.className);
  
  // vegye át a színt egyből
  var class_names = o.className.split(' ');
  class_names.pop()
  o.className = class_names.join(' ') + ' ' + o.options[o.selectedIndex].className;
  
  //alert(o.value);
  var keres = [];
  keres['esid'] = esid;
  keres['statusz'] = o.value;
  
  ajax_post(keres, 60);
}

/* kereses */
function search_results(that, dec)
{
  if(document.location.pathname.split('?')[0] != '/kereses.php') return true;
  
  numeric_input(that, dec);
  
  var keres = [];
// ?regio=0&telepules=0&varosresz=0&faj=2&fajta=0
// &kor=1&nem=1&pedigre=3&ar_tol=3&ar_ig=30
  keres['regio'] = document.getElementById('reg_regio').value;
  keres['telepules'] = document.getElementById('reg_telepules').value;
  keres['varosresz'] = document.getElementById('reg_varosresz').value;
  
  keres['faj'] = document.getElementById('reg_faj').value;
  keres['fajta'] = document.getElementById('reg_fajta').value;
  
  keres['kor'] = document.getElementById('reg_kor').value;
  keres['nem'] = document.getElementById('reg_nem').value;
  keres['pedigre'] = document.getElementById('reg_pedigre').value;
  
  keres['ar_tol'] = document.getElementById('reg_ar_tol').value;
  keres['ar_ig'] = document.getElementById('reg_ar_ig').value;
  
  ajax_post(keres, 50);
}

function numeric_input(that, decimals)
{
  if(!decimals) var decimals = 0;
  if(!that.value || that == false || that.value == '-') return false;

  that.value = that.value.split(',').join('.');//.split('..').join('.');
  if(isNaN(that.value * 1))
  {
    //alert(that.value);
    //var decimal = that.value.substr((that.value.length - 1), 1);
    
    //while((isNaN(that.value * 1) && decimal != '.') || that.value.split('.').length > 2)// && last_chars == ',' || last_chars == '.'
    while(isNaN(that.value * 1) || that.value.substr((that.value.length - 1), 1) == ' ')// && last_chars == ',' || last_chars == '.'
    {
      that.value = that.value.substr(0, (that.value.length - 1));
    }
    
    //return true;
  }
  
  var num_parts = that.value.split('.');
  if(decimals == 0) that.value = num_parts[0];
  else if(num_parts.length > 1) that.value = num_parts[0] + '.' + num_parts[1].substr(0, decimals);
  
  if(that.value == '.') that.value = '';
  else that.value = that.value.split('.').join(',');
  
  return false;
}


/* home scroll */
var scroller = [];
scroller['delay'] = 1200;
scroller['diff'] = 2;
scroller['interval'] = 25;
scroller['scrolltop'] = 0;

scroller['box'] = false;

function start_scroll(start, set)
{
  if(set === true) // start the loop
  {
    scroller['setinterval'] = setInterval('move_scroll();', scroller['interval']);
  }
  else
  {
    if(!start) // stop the loop
    {
      clearInterval(scroller['setinterval']);
      clearTimeout(scroller['settimeout']);
    }
    else // start up
    {
      // doboz magasságától függően beállítani a sebességet
      
      if(scroller['box'] == false)
      {
        scroller['direction'] = true;
        scroller['setinterval'] = false;
        scroller['settimeout'] = false;
        
        scroller['box'] = document.getElementById(set);
      }
      scroller['scrolltop'] = scroller['box'].scrollTop;
      
      if(scroller['box'] == false) scroller['settimeout'] = setTimeout('start_scroll(true, false);', scroller['delay']);
      else if(scroller['box'].scrollHeight >= scroller['box'].clientHeight) scroller['settimeout'] = setTimeout('start_scroll(true, true);', scroller['delay']);
      
    }
  }
  
}

function move_scroll()
{
  scroller['last_scrolltop'] = scroller['box'].scrollTop;

  if(scroller['direction'] == true)
  {
    //scroller['box'].scrollTop += scroller['diff'];
    scroller['scrolltop'] += scroller['diff'];
    scroller['box'].scrollTop = scroller['scrolltop'];
    
    if(scroller['last_scrolltop'] >= scroller['box'].scrollTop || scroller['box'].scrollHeight <= scroller['box'].scrollTop)
    {
      start_scroll(false, false);
      scroller['direction'] = false;
      start_scroll(true, false);
    }
  }
  else
  {
    //scroller['box'].scrollTop -= scroller['diff'];
    scroller['scrolltop'] -= scroller['diff'];
    scroller['box'].scrollTop = scroller['scrolltop'];
    
    if(scroller['last_scrolltop'] <= scroller['box'].scrollTop || 0 >= scroller['box'].scrollTop)
    {
      start_scroll(false, false);
      scroller['direction'] = true;
      start_scroll(true, false);
    }
  }
  
}


/* videos */
function qlist_set(n)
{
  if(isNaN(n)) return false;
  
  var keres = [];
  keres['hirdetes'] = n;
  
  var qbutton = document.getElementById('video_qbutton-' + n);
  if(qbutton)
  {
    keres['pic'] = document.getElementById('video_pic-' + n).src;
    keres['title'] = document.getElementById('video_title-' + n).innerHTML;
    if(keres['title'].length > 35) keres['title'] = keres['title'].substr(0, 32) + '...';
    
    qbutton.className = (qbutton.className == 'video_row_quick_add') ? 'video_row_quick_remove' : 'video_row_quick_add';
  }
  
  //keres['html'] = '<div class="qlist_row">'
  //  + '<img alt=" " src="' + keres['pic'] + '" class="qlist_row_pic" />'
  //  + '<h3 class="qlist_row_title">' + keres['title'] + '</h3>'
  //  + '<div class="qlist_row_buttons"><div onclick="qlist_set(' + n + ');" class="qlist_row_remove"></div><div class="qlist_row_play" onclick="open_box(' + n + ');"></div></div>'
  //+ '</div>';
  
  ajax_post(keres, 40);
  
  //draw_qlist_bar();
}



/*
//var qlist_rows = [];
var qlist_bar_id = 'right_bar';
function draw_qlist_bar()
{
  var inner_html = '';
  for(var i in qlist_rows)
  {
    if(qlist_rows[i]['in'] == true) inner_html += qlist_rows[i]['html'];

  }

  document.getElementById(qlist_bar_id).innerHTML = inner_html;
}
*/




/* home */
function get_children_tags(elem, tagname)
{
  var re = [];
  var n = 0;
  var childs = elem.childNodes;
  
  for(var i in childs)
  {
    if(childs[i].nodeName == tagname.toUpperCase())
    {
      re[n] = childs[i];
      n++;
    }
  }
  
  return re;
}

function start_fade(box_id, pager_id)
{
  var box_children = get_children_tags(document.getElementById(box_id), 'DIV');
  var pager_children = get_children_tags(document.getElementById(pager_id), 'DIV');
  //dbg(Array(box_children, pager_children), 1);
  
  var n = 0;
  for(var i in box_children)
  {
    fade_elements[n] = box_children[i];
    if(n > 0)
    {
      box_children[i].style.visibility = 'hidden';
      box_children[i].style.opacity = 0;
    }
    else
    {
      box_children[i].style.visibility = 'visible';
      box_children[i].style.opacity = 100;
    }
    n++;
  }

  n = 0;
  for(var i in pager_children)
  {
    pager_elements[n] = pager_children[i];
    n++;
  }

  fade_control();
}

var fade_options = [];
fade_options['time'] = 5000;
fade_options['loop'] = false;
fade_options['n'] = 0;
fade_options['loader_elem_id'] = 'content_foot';
// fade_control(false, n); //n= 1,2,3,4... // n-en kivul mindegyiknek tunjon el a lathatosaga
function fade_control(stop, n)
{
  if(fade_elements.length < 1 && pager_elements.length < 1) return false;
  
  if(!isNaN(n) && n == fade_options['n']) return false;
  
  if(stop == true)
  {
    clearInterval(fade_options['loop']);
    fade_options['loop'] = false;
    document.getElementById(fade_options['loader_elem_id']).className = 'content_foot_loader_stopped';
  }
  else if(!fade_options['loop'] && isNaN(n))
  {
    if(pager_elements.length > 1)
    {
	    //alert('pager_elements.length: ' + pager_elements.length);//dbg
      fade_options['loop'] = setInterval('fade_control();', fade_options['time']);
      document.getElementById(fade_options['loader_elem_id']).className = 'content_foot_loader';
    }
    else document.getElementById(fade_options['loader_elem_id']).className = 'content_foot_loader_stopped';
    //pager_elements[fade_options['n']].style.backgroundColor = '#f9f6ef';
    fade_elem_bgcolor(fade_options['n'], '#f0e9d8', '#f9f6ef', 0, 10, 1000);
  }
  else// if(fade_options['loop'] || !isNaN(n))
  {
  /*
    fade_options['p'] = fade_options['n'];
    // eltüntetés
    fade_elem_opacity(fade_options['p'], 0, 5, 20);
    fade_elem_bgcolor(fade_options['p'], '#f9f6ef', '#f0e9d8', 1, 10, 1000); // igazabol csak classvalto
  */
  /*
    for(var i in fade_elements)
    {
      fade_elements[i].style.visibility = 'hidden';
      fade_elements[i].style.opacity = 0;
      fade_elem_bgcolor(i, false, false, 1);
    }
    for(var j in pager_elements)
    {
      fade_elem_bgcolor(i, false, false, 1);
    }
    */

    fade_elements[fade_options['n']].style.visibility = 'hidden';
    //fade_elements[fade_options['n']].style.opacity = 0;
    set_opacity(fade_elements[fade_options['n']], 0);
    fade_elem_bgcolor(fade_options['n'], false, false, 1);
  
    if(!isNaN(n)) fade_options['n'] = n;
    else fade_options['n']++;

    if(fade_options['n'] >= pager_elements.length) fade_options['n'] = 0;

    // megjelenítés
    fade_elements[fade_options['n']].style.visibility = 'visible';
    set_opacity(fade_elements[fade_options['n']], 0);

    fade_elem_opacity(fade_options['n'], 100, 10, 30); // e, to, op_diff, time_int, display_none
    fade_elem_bgcolor(fade_options['n'], '#f0e9d8', '#f9f6ef', 0, 10, 1000); // igazabol csak classvalto
  }

  return true;
}

var pager_elements = [];
var n = 0;
function fade_elem_bgcolor(e, from, to, transparent, fps, duration, tagsname) // igazabol csak classvalto
{
  if(!pager_elements[e]) return false;

  if(pager_elements[e].id == '')
  {
    pager_elements[e].id = 'pager-' + n;
    n++;
  }
  
  //fade(pager_elements[e].id, from, to, transparent, fps, duration, tagsname);
  pager_elements[e].className = (transparent == 1) ? 'pager_num' : 'pager_num_active';
}

var fade_elements = [];
var fade_timeouts = [];
function fade_elem_opacity(e, to, op_diff, time_int, display_none)
{
  if(!op_diff) op_diff = 5;
  if(!time_int) time_int = 50;
  if(!display_none) display_none = 0;

  var elem = fade_elements[e];
  var eo = elem.style.opacity;

  if(eo !== 0 && (eo == '' || isNaN(eo))) eo = 100;
  else eo = eo * 100;

  if(to > eo)
  {
    var no = eo + op_diff;
    if(no > to) no = to;
  }
  else// if(to < eo)
  {
    var no = eo - op_diff;
    if(no < to) no = to;
  }
  //dbg(no, 5);

  set_opacity(elem, no);
  
  if(no == 0)
  {
    if(display_none == 1) elem.style.display = 'none';
    else elem.style.visibility = 'hidden';
  }

  if(!fade_timeouts[e])
  {
    fade_timeouts[e] = setInterval('fade_elem_opacity(' + e + ', ' + to + ', ' + op_diff + ', ' + time_int + ', ' + display_none + ');', time_int);
  }
  else if(no == to)
  {
    clearInterval(fade_timeouts[e]);
    fade_timeouts[e] = false;
  }

  return true;
}

function set_opacity(elem, op) {
    if(!op) op = 0;
    elem.style.opacity = (op * 0.01);
    elem.style.MozOpacity = (op * 0.01);
    elem.style.KhtmlOpacity = (op * 0.01);
    elem.style.filter = 'alpha(opacity=' + op + ')';
    return true;
}


// reg
function regio_valasztas(that)
{
  var keres = [];
  keres['regio'] = document.getElementById('reg_regio').value;
  
  ajax_post(keres, 10);
}

function telepules_valasztas(that)
{
  var keres = [];
  keres['regio'] = document.getElementById('reg_regio').value;
  keres['telepules'] = document.getElementById('reg_telepules').value;
  
  ajax_post(keres, 20);
}

function faj_valasztas(that)
{
  var keres = [];
  keres['faj'] = document.getElementById('reg_faj').value;
  
  ajax_post(keres, 80);
}

// email küldése
function send_to_friend(hiid) {
  
  if(isNaN(hiid)) return false;
  
  var keres = [];
  keres['hiid'] = hiid;
  keres['email'] = document.getElementById('send_to_friend_email').value;
  
  ajax_post(keres, 70);
  return false;
}

// ajax poster
var req = false;
function ajax_post(data, type)
{
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
   //if (!req) alert('An error occurred during the XMLHttpRequest!');

   var parameters = '';
   var amp = '';
   for(var i in data)
   {
     parameters += amp + i + '=' + escape(encodeURI( data[i] ));
     if(amp == '') amp = '&';
   }

   req.onreadystatechange = ajax_response;
   req.open("POST", "ajax.php?type=" + type, true);
   req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
   //req.setRequestHeader("Content-type", "text/plain;charset=utf-8");
   req.setRequestHeader("Content-length", parameters.length);
   req.setRequestHeader("Connection", "close");
   req.send(parameters);

   return true;
}

function ajax_response()
{
  if (req.readyState == 4)
  {
    if (req.status == 200)
    {
      var type = req.responseText.substr(0, 2);
      var data = req.responseText.substr(2);

      if(type == '00') alert(" - Debug:\n"+req.responseText);// debug

      // response router
      if (type == 10)
      {
        document.getElementById('reg_telepules_div').innerHTML = data;
		
        // ürítsük a városrészeket
        var keres = [];
        keres['regio'] = '-';
        keres['telepules'] = '-';
        ajax_post(keres, 20);
      }
      else if (type == 11)
      {
        document.getElementById('reg_telepules_div').innerHTML = data;
        
        // állítsuk be városrészeket
        var keres = [];
        keres['regio'] = document.getElementById('reg_regio').value;
        keres['telepules'] = document.getElementById('reg_telepules').value;
        ajax_post(keres, 20);
      }
      else if (type == 20)
      {
        document.getElementById('reg_varosresz_div').innerHTML = data;
        search_results(false);
      }
      else if (type == 30)
      {
        document.getElementById('video_box_content').innerHTML = data;
        so.write('player');
        //initfb(); //floatbox betöltés
		    fb.tagAnchors(document.getElementById('video_box_pictures'));
        
        fade_elem_opacity(27, 80, 15, 5); // key, toOp, opDiff, timeInt, display_none
        fade_elem_opacity(34, 100, 20, 5); // key, toOp, opDiff, timeInt, display_none
      }
      else if (type == 40)
      {
        document.getElementById('right_bar').innerHTML = data;
      }
      else if (type == 50)
      {
        document.getElementById('reg_submit').value = 'Keresés (Találat: ' + data + 'db)';
      }
      else if (type == 70)
      {
        document.getElementById('send_to_friend_error').innerHTML = data;
      }
      else if (type == 71)
      {
        document.getElementById('send_to_friend_error').innerHTML = data;
        document.getElementById('send_to_friend_email').value = '';
      }
      else if (type == 80)
      {
        document.getElementById('reg_fajta_div').innerHTML = data;
        search_results(false);
      }
    }
    else return false;
    //else alert('An error occurred during the ajax request!');
  }
}

function print_now(hirdetes)
{
  if(isNaN(hirdetes)) return false;

  var iframe = document.createElement('IFRAME');

  iframe.setAttribute('border', '0');
  iframe.setAttribute('frameborder', '0');
  iframe.setAttribute('width', '0');
  iframe.setAttribute('height', '0');
  iframe.setAttribute('style', 'border:none;');
  iframe.setAttribute('scrolling', 'no');

  iframe.setAttribute('src', 'hirdetes-nyomtatasa.php?nyomtatas=azonnal&hirdetes=' + hirdetes);
  document.body.appendChild(iframe);
}

var so = false;
function open_box(id)
{
  //alert('document.body.scrollTop: ' + document.body.scrollTop);
  //alert('document.body.scrollTop: ' + document.body.scrollTop);
  //return true;

//var a = document_height();
//alert(a.join(' - '));
//alert('offsetheight: ' + document.body.offsetHeight);
//alert('scrollHeight: ' + document.body.scrollHeight);
//alert('clientHeight: ' + document.body.clientHeight);
//return false;
  so = new SWFObject('player.swf', 'ply', '470', '320', '9', '#000000');
  so.addParam('allowscriptaccess','always');
  so.addParam('allowfullscreen','true');
  so.addParam('flashvars','&file=hirdetesek/' + id + '/video.flv&frontcolor=666666&lightcolor=aaaaaa&logo=default_images/mini_logo.png&width=470&height=320');
  //so.write('player');
  
  var keres = [];
  keres['hirdetes'] = id;
  
  //ajax_post(keres, 30);
  
  // effect
  var dark = document.getElementById('dark_overlay');
  dark.style.opacity = 0;
  dark.style.display = '';
  
  var box = document.getElementById('video_box_frame');
  box.style.opacity = 0;
  box.style.display = '';
  
  //var scroll = document_height();
  //dark.style.width = scroll[0] + 'px';
  //dark.style.height = document.body.clientHeight + 'px';
  
  //dark.style.height = document.clientHeight;
  //var document_height = document.clientHeight || document.documentElement.clientHeight;
  var document_height = document.clientHeight || document.body.clientHeight;
  //alert(document_height);
  dark.style.height = (document_height < 1000) ? '1000px' : document_height + 'px';
  //dark.style.height = document.body.clientHeight + 'px';
  
  //dark.style.height = document.offsetHeight + 'px';
  //dark.style.height = scroll[1] + 'px';
  //dark.style.height = '1000px'; // ide még kell írni egy funkciót ami visszaadja a valós lap magasságot
  //dark.style.height = document.body.scrollHeight;
  //scrolltop, scrollheight
  //dark.style.cssText = 'height: 1000px;';
  
  //var a = (window.innerHeight || document.body.clientHeight) +;
  //var b = (document.height || document.body.scrollHeight || document.body.style.pixelHeight);
  /*
  fade_elements[27] = dark;
  fade_elem_opacity(27, 80, 10, 5); // key, toOp, opDiff, timeInt, display_none
  fade_elements[34] = box;
  fade_elem_opacity(34, 100, 10, 5); // key, toOp, opDiff, timeInt, display_none
  */
  
  
  //box.style.top = (document.body.scrollTop + 10) + 'px';
  
  var scroll_top = document.body.scrollTop || document.documentElement.scrollTop;
  //alert(scroll_top);
  box.style.top = (10 + scroll_top) + 'px';
  
  fade_elements[27] = dark;
  fade_elements[34] = box;
  ajax_post(keres, 30);
  
  //container op: 80%;
  //body-bgcolor: #302f2b
}

function close_box()
{
  setTimeout('document.getElementById(\'video_box_content\').innerHTML = \'\';', 500); // stop event
  fade_elem_opacity(27, 0, 20, 5, 1); // key, toOp, opDiff, timeInt, display_none
  fade_elem_opacity(34, 0, 15, 5, 1); // key, toOp, opDiff, timeInt, display_none
}

function document_height()
{
  var scrOfX = 0, scrOfY = 0, myWidth = 0, myHeight = 0;
  if( typeof( window.pageYOffset ) == 'number' ) {
    //Netscape compliant
    scrOfY = window.pageYOffset;
    scrOfX = window.pageXOffset;
  } else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
    //DOM compliant
    scrOfY = document.body.scrollTop;
    scrOfX = document.body.scrollLeft;
  } else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
    //IE6 standards compliant mode
    scrOfY = document.documentElement.scrollTop;
    scrOfX = document.documentElement.scrollLeft;
  }
  
  if( typeof( window.innerWidth ) == 'number' ) {
    //Non-IE
    myWidth = window.innerWidth;
    myHeight = window.innerHeight;
  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    //IE 6+ in 'standards compliant mode'
    myWidth = document.documentElement.clientWidth;
    myHeight = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    //IE 4 compatible
    myWidth = document.body.clientWidth;
    myHeight = document.body.clientHeight;
  }
  
  return [ (scrOfX + myWidth), (scrOfY + myHeight) ];
}
/*
function getScrollXY() {
  var scrOfX = 0, scrOfY = 0;
  if( typeof( window.pageYOffset ) == 'number' ) {
    //Netscape compliant
    scrOfY = window.pageYOffset;
    scrOfX = window.pageXOffset;
  } else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
    //DOM compliant
    scrOfY = document.body.scrollTop;
    scrOfX = document.body.scrollLeft;
  } else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
    //IE6 standards compliant mode
    scrOfY = document.documentElement.scrollTop;
    scrOfX = document.documentElement.scrollLeft;
  }
  return [ scrOfX, scrOfY ];
}
*/
/*
SmokeScreen.resize = function (event) {
    if (!SmokeScreen.actual)
        return;
    SmokeScreen.actual.style.height = "1px";
    SmokeScreen.actual.style.height = Math.max(window.innerHeight ? window.innerHeight : 0, document.documentElement.clientHeight, document.documentElement.scrollHeight) + "px";
    if (document.all)
        SmokeScreen.actual.style.width = document.documentElement.clientWidth + "px";
}
*/

/* szabályzat lenyitása */
function toggle_szabalyzat(id, e)
{
  var box = document.getElementById(id);
  
  box.style.height = (box.style.height == 'auto') ? '200px' : 'auto';
}

// random string
function rand_str(length, chars)
{
	if(!length) length = 1;
	if(!chars) chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	//var chars = "0123456789abcdef";//ez kell csak md5 szerű-höz
	
	var randString = '';
	for(var i = 0; i < length; i++) {
		var rnum = Math.floor(Math.random() * chars.length);
		randString += chars.substring(rnum, rnum+1);
	}
	
	return randString;
}

/* adott süti kinyerése */
function _cookie(name)
{
  var cookie_place = document.cookie.indexOf(name + '=');

  if(cookie_place != -1)
  {
    return document.cookie.substr(cookie_place + name.length + 1).split('; ')[0];
  }
  else
  {
    return '';
  }
}






