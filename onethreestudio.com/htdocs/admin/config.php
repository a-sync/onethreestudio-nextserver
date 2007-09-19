<?
//felhasználó beállítások
$ots_userName[] = 'Vector';//elõbb név
$ots_userPass[] = '13sadmin';//aztán jelszó

$ots_userName[] = 'kocsmy';
$ots_userPass[] = '123kurvaanyad';

//kezelendõ mappák
$workDirRoot[] = '../worx/';//elõbb mappa elérés kezelõbõl
$workDirName[] = 'Képek mappája';//aztán leírás a mappáról
$workDirLink[] = 'http://www.onethreestudio.com/worx/';//végül mappa elérés böngészõbõl

$workDirRoot[] = '../download/';
$workDirName[] = 'Letöltések mappája';
$workDirLink[] = 'http://www.onethreestudio.com/download/';

//adatbázis beállítások
$ots_db_host = 'localhost';
$ots_db_user = 'onethreestudio';
$ots_db_pass = 'va1chaiV';
$ots_db_name = 'onethreestudio';

//config fix
for($i;$i<count($workDirRoot);$i++){if(substr($workDirRoot[$i],-1)!='/'&&$workDirRoot[$i]!=''){$workDirRoot[$i].=$workDirRoot[$i].'/';}}
for($i;$i<count($workDirLink);$i++){if(substr($workDirLink[$i],-1)!='/'&&$workDirLink[$i]!=''){$workDirLink[$i].=$workDirLink[$i].'/';}}
?>