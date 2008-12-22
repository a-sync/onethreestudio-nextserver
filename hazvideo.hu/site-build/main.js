

function init(where)
{
  //var o = event.currentTarget || event.srcElement;
  dbg('Init...');
  
  if(where == 'home')
  {
    start_fade('content', 'pager');
  }
  else if(where == 'videos')
  {
    
  }
}

/* videos */
function qlist_set(n)
{
  if(isNaN(n)) return false;
  
  if(!qlist_rows[n])
  {
    qlist_rows[n] = [];
    qlist_rows[n]['in'] = true;
    qlist_rows[n]['pic'] = document.getElementById('video_pic-' + n).src;
    qlist_rows[n]['title'] = document.getElementById('video_title-' + n).innerHTML;
    
    if(qlist_rows[n]['title'].length > 35) qlist_rows[n]['title'] = qlist_rows[n]['title'].substr(0, 32) + '...';
    
    qlist_rows[n]['html'] = '<div class="qlist_row">'
                  + '<img alt=" " src="' + qlist_rows[n]['pic'] + '" class="qlist_row_pic" />'
                  + '<h3 class="qlist_row_title">' + qlist_rows[n]['title'] + '</h3>'
                  + '<div class="qlist_row_buttons"><div onclick="qlist_set(' + n + ');" class="qlist_row_remove"></div><div class="qlist_row_play"></div></div>'
                  + '</div>';
  }
  else if(qlist_rows[n]['in'] == true) qlist_rows[n]['in'] = false;
  else qlist_rows[n]['in'] = true;

  var qbutton = document.getElementById('video_qbutton-' + n);
  qbutton.className = (qbutton.className == 'video_row_quick_add') ? 'video_row_quick_remove' : 'video_row_quick_add';

  draw_qlist_bar();
}




var qlist_rows = [];
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
  dbg(Array(box_children, pager_children), 1);
  
  var n = 0;
  for(var i in box_children)
  {
    fade_elements[n] = box_children[i];
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
function fade_control(stop, n)
{
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
      fade_options['loop'] = setInterval('fade_control();', fade_options['time']);
      document.getElementById(fade_options['loader_elem_id']).className = 'content_foot_loader';
    }
    //pager_elements[fade_options['n']].style.backgroundColor = '#f9f6ef';
    fade_elem_bgcolor(fade_options['n'], '#f0e9d8', '#f9f6ef', 0, 10, 1000);
  }
  else// if(fade_options['loop'] || !isNaN(n))
  {
    fade_options['p'] = fade_options['n'];

    if(!isNaN(n)) fade_options['n'] = n;
    else fade_options['n']++;

    if(fade_options['n'] >= pager_elements.length) fade_options['n'] = 0;
    
    // eltüntetés
    fade_elem_opacity(fade_options['p'], 0, 5, 20);
    fade_elem_bgcolor(fade_options['p'], '#f9f6ef', '#f0e9d8', 1, 10, 1000);

    // megjelenítés
    fade_elements[fade_options['n']].style.visibility = 'visible';
    set_opacity(fade_elements[fade_options['n']], 0);

    fade_elem_opacity(fade_options['n'], 100, 2, 30);
    fade_elem_bgcolor(fade_options['n'], '#f0e9d8', '#f9f6ef', 0, 10, 1000);
  }

  return true;
}

var pager_elements = [];
var n = 0;
function fade_elem_bgcolor(e, from, to, transparent, fps, duration, tagsname)
{
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
function fade_elem_opacity(e, to, op_diff, time_int)
{
  if(!op_diff) op_diff = 5;
  if(!time_int) time_int = 50;

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
  
  if(no == 0) elem.style.visibility = 'hidden';

  if(!fade_timeouts[e])
  {
    fade_timeouts[e] = setInterval('fade_elem_opacity(' + e + ', ' + to + ', ' + op_diff + ', ' + time_int + ');', time_int);
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


