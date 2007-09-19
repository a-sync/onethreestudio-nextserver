<?php
//includes
require('init.php');
include('connect_db.php');

//$i is the stuff we get in
//$e is the stuff we put out
//e(), l(), q() and _query() functions in init.php

//_query($query_string) is for sql query's; returns a resource;
//e($string, $start = 0, $length = $string, $escape_quotes_type = ENT_QUOTES) is for filtering the output with htmlescapechars and a built in substr; returns filtered string;
//l($string, $start = 0, $length = $string) is for comparing a part of a string with $i (case insensitive); returns true or false;
//q($string) is for comparing a string with $i (case insensitive); returns true or false;
//p0($name, $data = $name) is for returning a string formatted to use the p0() function on the panel, to send data with the link; returns formatted string;
//p1($name, $data = $name) is for returning a string formatted to use the p1() function on the panel, to copy data to the input field; returns formatted string;
//p2($name, $data = $name) is for returning a string formatted to use the p2() function on the panel, to add data to the input field's data; returns formatted string;

//help
$help['about'] = 'In Your Face Interface 1.0 by Vector Akashi (<a href="http://www.onethreestudio.com" target="_blank">www.onethreestudio.com</a>)';
$help['cls'] = 'CLS clears the screen.';
$help['contact'] = 'CONTACT shows our contact info.';
$help['help'] = 'You can ask for command specific help: help &lt;command&gt;<br/>eg.: help cls';
$help['md5'] = 'MD5 gives back the md5 hash of the given string.<br/>eg.: md5 foobar';
$help['services'] = 'The services we offer.';
$help['time'] = 'TIME shows the current time / date. You can specify the format by giving a php date() format.<br/>eg.: time Y.m.d - H:i:s';
$help['works'] = 'WORKS shows our works and references.';

//command tree
if(l('about')) {
  $e = $help['about'].'<br/>';
}

elseif(l('cls')) {
  die('cls');
}

elseif(l('contact')) {
  $e = 'Contact:
        <br/>&nbsp; &nbsp;web: www.onethreestudio.com
        <br/>&nbsp; &nbsp;mail: info@onethreestudio.com
        <br/>&nbsp; &nbsp;msn: kocsmy@hotmail.com
        <br/>&nbsp; &nbsp;phone: +36 30 487 54 79
        <br/>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Zsolt Kocsmárszky
        <br/>';
}

elseif(l('help ') || q('help')) {
  if(l(' ', 4)) {
    $e = $help[e($i, 5)].'<br/>';
    if($e == '<br/>') $e = 'There is no help for the \''.e($i, 5).'\' command or there is no command under that name.';
  }
  else {
    $e = 'Commands:';
    foreach($help as $k => $v) $e .= ' '.$k.',';//p0($k)
    $e = substr($e, 0, -1).'.<br/><br/>'.$help['help'].'<br/>';
  }
}

elseif(l('md5 ') || q('md5')) {
  if(l(' ', 3)) $e = md5(substr($i, 4));
  else $e = $help['md5'].'<br/>';
}

elseif(q('services')) {
  $e = 'Services:
        <br/>&nbsp; &nbsp;Creative Design (Photoshop, Illustrator, Flash)
        <br/>&nbsp; &nbsp;Website Developement (CSS, xHTML, JavaScript)
        <br/>&nbsp; &nbsp;Site and Portal Building (PhP, MySQL)
        <br/>&nbsp; &nbsp;E-Commerce Solutions
        <br/>&nbsp; &nbsp;Online Creative Banana
        <br/>&nbsp; &nbsp;Search Engine Optimization
        <br/>&nbsp; &nbsp;Website Hosting
        <br/>';
}

elseif(l('time ') || q('time')) {
  if(l(' ', 4)) $e = date(e($i, 5));
  else $e = date('Y.m.d - H:i:s');
}

elseif(q('works')) {
  $e = 'Our Works:';

  $query = "SELECT * FROM `ots_references` WHERE `status` != '0' ORDER BY `status` DESC";
  $queryresult = _query($query);
  $resultnum = mysql_num_rows($queryresult);

  if($resultnum > 0){
    while($a = mysql_fetch_array($queryresult, MYSQL_ASSOC)) {
      $e .= '<br/>&nbsp; &nbsp;<a title="'.$a['pic_text'].'" rel="lightbox[works]" href="'.$a['full_pic'].'">'.$a['pic_title'].'</a>';
      if($a['link']) { $e .= ' :: <a href="'.$a['link'].'">'.$a['link'].'</a>'; }
      if($a['download']) { $e .= ' :: <a href="'.$a['download'].'">Download!</a>'; }
    }
  }
  else { $e .= 'No work entry\'s. Sorry.'; }//Nincsenek Referencia bejegyzések

  $e .= '<br/>';
}

//last command check
else {
  $e = e();
}

//closing connection
@mysql_close();

//log the given commands (CHMOD 0777 cmd.log)
$file = @fopen('cmd.log', 'a');
@fwrite($file, date('Y.m.d - H:i:s').'> '.$i."\n\r");
@fclose($file);

//sending headers and return string
header("Content-Type: text/html; charset=iso-8859-2");
//echo e($i).'<br/>';//debug?
echo $e.'<br/>';
?>