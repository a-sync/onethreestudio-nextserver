<?
//felhaszn�l� be�ll�t�sok
$ots_userName[] = 'Vector';//el�bb n�v
$ots_userPass[] = '13sadmin';//azt�n jelsz�

$ots_userName[] = 'kocsmy';
$ots_userPass[] = '123kurvaanyad';

//kezelend� mapp�k
$workDirRoot[] = '../worx/';//el�bb mappa el�r�s kezel�b�l
$workDirName[] = 'K�pek mapp�ja';//azt�n le�r�s a mapp�r�l
$workDirLink[] = 'http://www.onethreestudio.com/worx/';//v�g�l mappa el�r�s b�ng�sz�b�l

$workDirRoot[] = '../download/';
$workDirName[] = 'Let�lt�sek mapp�ja';
$workDirLink[] = 'http://www.onethreestudio.com/download/';

//adatb�zis be�ll�t�sok
$ots_db_host = 'localhost';
$ots_db_user = 'onethreestudio';
$ots_db_pass = 'va1chaiV';
$ots_db_name = 'onethreestudio';

//config fix
for($i;$i<count($workDirRoot);$i++){if(substr($workDirRoot[$i],-1)!='/'&&$workDirRoot[$i]!=''){$workDirRoot[$i].=$workDirRoot[$i].'/';}}
for($i;$i<count($workDirLink);$i++){if(substr($workDirLink[$i],-1)!='/'&&$workDirLink[$i]!=''){$workDirLink[$i].=$workDirLink[$i].'/';}}
?>