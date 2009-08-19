<?php
/*
This file is part of miniBB. miniBB is free discussion forums/message board software, without any warranty. See COPYING file for more details. Copyright (C) 2004-2008 Paul Puzyrev, Sergei Larionov. www.minibb.com
Latest File Update: 2008-Dec-09
*/
if (!defined('INCLUDED776')) die ('Fatal error.');

$version='2.3';

if($DB=='mysql' or $DB=='pgsql') $caseComp='lower'; elseif($DB=='mssql') $caseComp='lcase';

//--------------->
function makeUp($name,$addDir='') {
if($addDir=='') $addDir=$GLOBALS['pathToFiles'].'templates/';
if(isset($GLOBALS['forumClone']) and $GLOBALS['forumClone']!='' and ($name=='main_header' or $name=='main_footer') ) $addDir=$GLOBALS['pathToFiles'].$GLOBALS['forumClone'].'/templates/';
if (substr($name,0,5)=='email') $ext='txt'; else $ext='html';
$fload=$addDir.$name.'.'.$ext;
if(file_exists($fload)) {
$fd=fopen($fload, 'r');
$tpl=fread($fd, filesize($fload));
fclose($fd);
return $tpl;
}
else die ("TEMPLATE NOT FOUND: $fload");
}

//--------------->
function ParseTpl($tpl){
$qs=array();
$qv=array();
$ex=explode ('{$',$tpl);
$exs=sizeof($ex);
for ($i=0; $i<$exs; $i++) {
if (substr_count($ex[$i],'}')>0) {
$xx=explode('}',$ex[$i]);
if (substr_count($xx[0],'[')>0) {
$clr=explode ('[',$xx[0]); $sp=str_replace('$','',substr($clr[1],0,strlen($clr[1])-1)); if(!is_integer($sp) and isset($GLOBALS[$sp])) $sp=$GLOBALS[$sp]; $clr=$clr[0];
if (!in_array($clr,$qs)) $qs[]=$clr;
if(isset($GLOBALS[$clr][$sp])) $to=$GLOBALS[$clr][$sp]; else $to='';
}
else {
if(!in_array($xx[0], $qv)) $qv[]=$xx[0];
if(isset($GLOBALS[$xx[0]])) $to=$GLOBALS[$xx[0]]; else $to='';
}
$tpl=str_replace('{$'.$xx[0].'}', $to, $tpl);
}
}
return $tpl;
}

//--------------->
function load_header() {
//we need to load this template separately, because we load page title
if(isset($GLOBALS['mod_rewrite']) and $GLOBALS['mod_rewrite']) $mrw=TRUE; else $mrw=FALSE;
if(!isset($GLOBALS['forum'])) $GLOBALS['forum']=0;
if(!isset($GLOBALS['topic'])) $GLOBALS['topic']=0;
if(!isset($GLOBALS['page'])) $GLOBALS['page']=0;

define('HEADER_CALLED', 1);

if(!isset($GLOBALS['adminPanel'])) $GLOBALS['adminPanel']=0;
if($mrw) {
$qstr1="{$GLOBALS['forum']}_{$GLOBALS['page']}.html";
$qstr2="{$GLOBALS['forum']}_{$GLOBALS['topic']}_{$GLOBALS['page']}.html";
} else {
$qstr1="{$GLOBALS['indexphp']}action=vtopic&amp;forum={$GLOBALS['forum']}";
if(isset($_GET['sortBy'])) $qstr1.="&amp;sortBy={$_GET['sortBy']}";
if($GLOBALS['page']!=0 and substr_count($GLOBALS['queryStr'], 'page%3D')>0) $qstr1.="&amp;page={$GLOBALS['page']}";
$qstr2="{$GLOBALS['indexphp']}action=vthread&amp;forum={$GLOBALS['forum']}&amp;topic={$GLOBALS['topic']}";
if($GLOBALS['page']!=0 and substr_count($GLOBALS['queryStr'], 'page%3D')>0) $qstr2.="&amp;page={$GLOBALS['page']}";
}
$urlp=$GLOBALS['startIndex'];

if(strlen($GLOBALS['action'])>0||$GLOBALS['adminPanel']==1) {
$GLOBALS['l_menu'][0]="<a href=\"{$GLOBALS['main_url']}/{$urlp}\">{$GLOBALS['l_menu'][0]}</a> {$GLOBALS['l_sepr']} ";
}
else $GLOBALS['l_menu'][0]='';

if($GLOBALS['action']!='stats') $GLOBALS['l_menu'][3]="<a href=\"{$GLOBALS['main_url']}/{$GLOBALS['indexphp']}action=stats\">{$GLOBALS['l_menu'][3]}</a> {$GLOBALS['l_sepr']} "; else $GLOBALS['l_menu'][3]='';

if($GLOBALS['viewTopicsIfOnlyOneForum']==1 and $GLOBALS['action']=='vtopic') {
if($GLOBALS['page']==0) $qstr1=$GLOBALS['startIndex'];
$GLOBALS['l_menu'][7]="<a href=\"{$GLOBALS['main_url']}/{$qstr1}#newtopic\">{$GLOBALS['l_menu'][7]}</a> ".$GLOBALS['l_sepr'].' ';
}
elseif(isset($GLOBALS['nTop'])&&$GLOBALS['nTop']==1&&(!isset($_GET['showSep'])||$_GET['showSep']!=1)){
if($GLOBALS['action']=='vtopic'&&isset($_GET['showSep'])) $GLOBALS['l_menu'][7]=(isset($GLOBALS['newTopicLink'])?$GLOBALS['newTopicLink'].' '.$GLOBALS['l_sepr'].' ':'');
elseif($GLOBALS['action']=='vtopic') $GLOBALS['l_menu'][7]="<a href=\"{$GLOBALS['main_url']}/{$qstr1}#newtopic\">{$GLOBALS['l_menu'][7]}</a> {$GLOBALS['l_sepr']} ";
elseif($GLOBALS['action']=='vthread') $GLOBALS['l_menu'][7]="<a href=\"{$GLOBALS['main_url']}/{$qstr2}#newreply\">{$GLOBALS['l_reply']}</a> {$GLOBALS['l_sepr']} ";
else $GLOBALS['l_menu'][7]='';
}
elseif(isset($GLOBALS['mTop'])&&$GLOBALS['mTop']==1&&$GLOBALS['action']=='') $GLOBALS['l_menu'][7]="<a href=\"{$GLOBALS['main_url']}/{$urlp}#newtopic\">{$GLOBALS['l_menu'][7]}</a> {$GLOBALS['l_sepr']} ";
else $GLOBALS['l_menu'][7]='';

if($GLOBALS['action']!='search') $GLOBALS['l_menu'][1]="<a href=\"{$GLOBALS['main_url']}/{$GLOBALS['indexphp']}action=search\">{$GLOBALS['l_menu'][1]}</a> {$GLOBALS['l_sepr']} "; else $GLOBALS['l_menu'][1]='';

if($GLOBALS['action']!='registernew' and $GLOBALS['user_id']==0 and $GLOBALS['adminPanel']!=1 and $GLOBALS['enableNewRegistrations']) $GLOBALS['l_menu'][2]="<a href=\"{$GLOBALS['main_url']}/{$GLOBALS['indexphp']}action=registernew\">{$GLOBALS['l_menu'][2]}</a> {$GLOBALS['l_sepr']} "; else $GLOBALS['l_menu'][2]='';

if($GLOBALS['action']!='manual') {
if($mrw) $urlp='manual.html'; else $urlp="{$GLOBALS['indexphp']}action=manual";
$GLOBALS['l_menu'][4]="<a href=\"{$GLOBALS['main_url']}/{$urlp}\">{$GLOBALS['l_menu'][4]}</a> {$GLOBALS['l_sepr']} ";
}
else $GLOBALS['l_menu'][4]='';

if( ($GLOBALS['action']=='prefs' and isset($GLOBALS['adminUser']) and $GLOBALS['adminUser']==0) or $GLOBALS['user_id']==0 or !$GLOBALS['enableProfileUpdate']) $GLOBALS['l_menu'][5]='';
else $GLOBALS['l_menu'][5]="<a href=\"{$GLOBALS['main_url']}/{$GLOBALS['indexphp']}action=prefs\">{$GLOBALS['l_menu'][5]}</a> {$GLOBALS['l_sepr']} ";

if($GLOBALS['user_id']!=0) $GLOBALS['l_menu'][6]="<a href=\"{$GLOBALS['main_url']}/{$GLOBALS['indexphp']}mode=logout\">{$GLOBALS['l_menu'][6]}</a> {$GLOBALS['l_sepr']} "; else $GLOBALS['l_menu'][6]='';

if (!isset($GLOBALS['title']) or $GLOBALS['title']=='') $GLOBALS['title']=$GLOBALS['sitename'];
if(isset($GLOBALS['includeHeader']) and $GLOBALS['includeHeader']!='') { include($GLOBALS['includeHeader']); return; }
return ParseTpl(makeUp('main_header'));
}

//--------------->
function getAccess($clForums, $clForumsUsers, $user_id){
$forb=array();
$acc='n';
if ($user_id!=1 and sizeof($clForums)>0){
foreach($clForums as $f){
if (isset($clForumsUsers[$f]) and !in_array($user_id, $clForumsUsers[$f])){
$forb[]=$f; $acc='m';
}
}
}
if ($acc=='m') return $forb; else return $acc;
}

//--------------->
function getIP(){
$ip1=$_SERVER['REMOTE_ADDR'];
if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip2=$_SERVER['HTTP_X_FORWARDED_FOR']; else $ip2='';
if($ip2!='' and preg_match("/^[0-9.]+$/", $ip2) and ip2long($ip2)!=-1) $finalIP=$ip2; else $finalIP=$ip1;
$finalIP=substr($finalIP,0,15);
return $finalIP;
}

//--------------->
$GLOBALS['today']=date('Y-m-d');
$GLOBALS['yesterday']=date('Y-m-d', time()-86400);

function convert_date($dateR){
$engMon=array('January','February','March','April','May','June','July','August','September','October','November','December',' ');
$months=explode (':', $GLOBALS['l_months']);
$months[]='&nbsp;';
$dfval=strtotime($dateR);
if(isset($GLOBALS['timeDiff']) and $GLOBALS['timeDiff']!=0) $dfval+=$GLOBALS['timeDiff'];

if(isset($GLOBALS['dateOnlyFormat']) and isset($GLOBALS['l_today']) and isset($GLOBALS['l_yesterday'])) {
if(substr($dateR, 0, 10)==$GLOBALS['today']) $dateR=$GLOBALS['l_today'].', '.date($GLOBALS['timeOnlyFormat'], $dfval);
elseif(substr($dateR, 0, 10)==$GLOBALS['yesterday']) $dateR=$GLOBALS['l_yesterday'].', '.date($GLOBALS['timeOnlyFormat'], $dfval);
else $dateR=date($GLOBALS['dateFormat'], $dfval);
}
else $dateR=date($GLOBALS['dateFormat'], $dfval);

$dateR=str_replace($engMon,$months,$dateR);
return $dateR;
}

//--------------->
function pageChk($page,$numRows,$viewMax){
if($numRows>0 and ($page>0 or $page==-1)){
$max=$numRows/$viewMax;
if(intval($max)==$max) $max=intval($max)-1; else $max=intval($max);
if ($page==-1) return $max;
elseif($page>$max) return $max;
else return $page;
}
else return 0;
}

//--------------->
function pgRng($rng, $pars){
$c='';
foreach($rng as $r) {
if($r==$pars[0]) $c.='&nbsp;&nbsp;<span class="navCell">'.$r.'</span>';
else $c.=$pars[3].'<a class="navCell" href="'.$pars[1].($r-1).$pars[2].'" title="'.$GLOBALS['l_page'].' #'.$r.'">'.$r.'</a>';
}
return $c;
}

//--------------->
function pageNav($page, $numRows, $url, $viewMax, $navCell=FALSE, $htmlExt=FALSE){
if(!$navCell){ $sLim=11;$bLim=5;$cLim=4; }
else{ $sLim=6;$bLim=4;$cLim=1; }
$pageNav='';
if(!$navCell) { $stp=1; $frn='&nbsp;&nbsp;'; }
else { $stp=2; $frn='&nbsp;'; }
$spl='&nbsp;&nbsp;...';
if($htmlExt) $mr='.html'; else $mr='';
if((integer)($numRows/$viewMax)<($numRows/$viewMax)) $pages=(integer)($numRows/$viewMax)+1;
else $pages=(integer)($numRows/$viewMax);
if($pages>1){
if($page==-1) $spage=$pages; else $spage=$page+1;
$pars=array($spage, $url, $mr, $frn);
if($page>0 and !$navCell) $pageNav.='&nbsp;&nbsp;<a href="'.$url.($page-1).$mr.'" title="'.$GLOBALS['l_page'].' #'.$page.'">&laquo;&laquo;</a>';
if($pages>$sLim){
if($spage>0 and $spage<$bLim){
$pageNav.=pgRng(range($stp,$bLim), $pars).$spl.pgRng(range($pages-$cLim,$pages), $pars);
}
elseif($spage==$bLim){
$pageNav.=pgRng(range($stp,$bLim+2), $pars).$spl.pgRng(range($pages-2,$pages), $pars);
}
elseif($spage>$bLim and $spage<=$pages-6){
$pageNav.=pgRng(range($stp,2), $pars).$spl.pgRng(range($spage-2,$spage+2), $pars).'&nbsp;&nbsp;...'.pgRng(range($pages-1,$pages), $pars);
}
elseif($spage==$pages-$bLim){
$pageNav.=pgRng(range($stp,2), $pars).$spl.pgRng(range($pages-7,$pages), $pars);
}
elseif($spage>=$pages-4 and $spage<=$pages){
$pageNav.=pgRng(range($stp,3), $pars).$spl.pgRng(range($pages-6,$pages), $pars);
}
}
else $pageNav.=pgRng(range($stp,$pages), $pars);
if($page+1<$pages and !$navCell) $pageNav.='&nbsp;&nbsp;<a href="'.$url.($page+1).$mr.'" title="'.$GLOBALS['l_page'].' #'.($page+2).'">&raquo;&raquo;</a>';
if(!$navCell) $pageNav='&nbsp;'.$GLOBALS['l_page'].':'.$pageNav;
}
return $pageNav;
}

//---------------------->
function sendMail($email, $subject, $msg, $from_email, $errors_email) {
// Function sends mail with return-path */

if (!isset($GLOBALS['genEmailDisable']) or $GLOBALS['genEmailDisable']!=1){

if(substr_count($from_email,"\n")>0) $from_email=$GLOBALS['admin_email'];
if(substr_count($errors_email,"\n")>0) $errors_email=$GLOBALS['admin_email'];

if(!isset($GLOBALS['enablePhpMailer'])){
if(isset($GLOBALS['emailCharset'])) $charset="; charset={$GLOBALS['emailCharset']}"; else $charset='';
$from_email="From: $from_email\r\nReply-To: $from_email\r\nErrors-To: $errors_email\r\nReturn-Path: $errors_email\r\nMIME-Version: 1.0\r\nContent-Type: text/plain{$charset}\r\nUser-Agent: PHP";
mail($email, $subject, $msg, $from_email);
}
else{
require_once ($GLOBALS['pathToFiles'].'class.phpmailer.php');
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Host = $GLOBALS['enablePhpMailer']['smtp_host'];
$mail->SMTPAuth = $GLOBALS['enablePhpMailer']['smtp_auth'];
$mail->FromName = $GLOBALS['sitename'];
$mail->Username = $GLOBALS['enablePhpMailer']['smtp_username'];
$mail->Password = $GLOBALS['enablePhpMailer']['smtp_pass'];
$mail->From = $from_email;
$mail->AddAddress($email);
$mail->IsHTML(FALSE);
$mail->Subject = $subject;
$mail->Body = $msg;
$mail->Send();
}

}
}

//---------------------->
function emailCheckBox() {

$checkEmail='';
if($GLOBALS['genEmailDisable']!=1){

if(isset($GLOBALS['sendid']) and is_array($GLOBALS['sendid']) and $GLOBALS['sendid'][2]==$GLOBALS['user_id']) $isInDb=TRUE; else $isInDb=FALSE;

$true0=($GLOBALS['emailusers']>0);
$true1=($GLOBALS['user_id']!=0);
$true2=($GLOBALS['action']=='vtopic' or $GLOBALS['action'] == 'vthread' or $GLOBALS['action']=='ptopic' or $GLOBALS['action']=='pthread');
$true3a=($GLOBALS['user_id']==1 and (!isset($GLOBALS['emailadmposts']) or $GLOBALS['emailadmposts']==0) and !$isInDb);
$true3b=($GLOBALS['user_id']!=1 and !$isInDb);
$true3=($true3a or $true3b);

if ($true0 and $true1 and $true2 and $true3) {
$checkEmail="<input type=\"checkbox\" name=\"CheckSendMail\" /> {$GLOBALS['l_emailNotify']}";
}
elseif($isInDb) $checkEmail="<!--U--><a href=\"{$GLOBALS['main_url']}/{$GLOBALS['indexphp']}action=unsubscribe&amp;topic={$GLOBALS['topic']}&amp;usrid={$GLOBALS['user_id']}\">{$GLOBALS['l_unsubscribe']}</a>";
}
return $checkEmail;
}

//---------------------->
function makeValuedDropDown($listArray,$selectName, $additional=''){
$out='';
if(isset($GLOBALS[$selectName])) $curVal=$GLOBALS[$selectName]; else $curVal='';
foreach($listArray as $key=>$val){
if($curVal==$key) $sel=' selected="selected"'; else $sel='';
$out.="<option value=\"$key\"{$sel}>$val</option>";
}
return "<select name=\"$selectName\" id=\"$selectName\" class=\"selectTxt\"{$additional}>$out</select>";
}

//---------------------->
function setCSRFCheckCookie(){
setcookie($GLOBALS['cookiename'].'_csrfchk', '', (time()-2592000), $GLOBALS['cookiepath'], $GLOBALS['cookiedomain'], $GLOBALS['cookiesecure']);
setcookie($GLOBALS['cookiename'].'_csrfchk', substr(preg_replace("[^0-9A-Za-z]", 'A', md5(uniqid(rand()))),0,20), 0, $GLOBALS['cookiepath'], $GLOBALS['cookiedomain'], $GLOBALS['cookiesecure']);
}

//---------------------->
if(!function_exists('get_magic_quotes_gpc')) {
function get_magic_quotes_gpc() {
return 0;
}
}

?>