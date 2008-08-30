/* fader */

alert('dbg1');
alert('get_children_tags: 15');
alert('get_children_tags(masik): 2');
alert('dbg2');
alert('loader: 163');

function get_children_tags(elem, tagname)
{
  var re = [];
  var n = 0;
  var childs = elem.childNodes;
  
  for(var i in childs)
  {
    if(childs[i].nodeName == tagname.toUpperCase()) // nodeName to upppercase?
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