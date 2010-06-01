<?php
if(!defined('_HAB_'))die('*');

if($_POST['mentes'] != '')
{
  $cim = e(substr($_POST['cim'], 0, 250));
  $telepules = e(substr($_POST['telepules'], 0, 250));
  $leiras = e(substr($_POST['leiras'], 0, 65000));
  $jegyzet = e(substr($_POST['jegyzet'], 0, 65000));
  
  $regio = (isset($regiok[$_POST['regio']])) ? $_POST['regio'] : 0;
  $kategoria = (isset($kategoriak[$_POST['kategoria']])) ? $_POST['kategoria'] : 0;
  
  $szoba = (is_numeric($_POST['szoba'])) ? substr($_POST['szoba'], 0, 3) : 0;
  $alapterulet = (is_numeric($_POST['alapterulet'])) ? substr($_POST['alapterulet'], 0, 10) : 0;
  $ar = (is_numeric($_POST['ar'])) ? substr($_POST['ar'], 0, 10) : 0;
  $ido = strtotime($_POST['ido']);
  if($ido == false || $ido == -1) $ido = time();
  
  $status = ($_POST['status'] == 1) ? 1 : 0;
  
  $q = "INSERT INTO `hirdetesek` (`cim`, `telepules`, `leiras`, `jegyzet`, `regio`, `kategoria`, `szoba`, `alapterulet`, `ar`, `ido`, `status`) VALUE ('{$cim}', '{$telepules}', '{$leiras}', '{$jegyzet}', '{$regio}', '{$kategoria}', '{$szoba}', '{$alapterulet}', '{$ar}', '{$ido}', '{$status}');";
  
  //sql($q);
  die($q.'<br/><h1>HIBAKEZELÉS!!!!</h1>');
}

?>
<form action="" method="post">

<table cellspacing="0" cellpadding="0" border="0">

<tr>
  <td>Cím:</td>
  <td>
    <input type="text" name="cim" value="" maxlength="250"/>
  </td>
</tr>

<tr>
  <td>Régió:</td>
  <td>
    <select name="regio">
      <option value=""> --- </option>
<?php

foreach($regiok as $i => $n)
{
  echo '<option value="'.$i.'">'.htmlspecialchars($n).'</option>';
}

?>
    </select>
  </td>
</tr>

<tr>
  <td>Település:</td>
  <td>
    <input type="text" name="telepules" value="" maxlength="250"/>
  </td>
</tr>

<tr>
  <td>Kategória:</td>
  <td>
    <select name="kategoria">
      <option value=""> --- </option>
<?php

foreach($kategoriak as $i => $n)
{
  echo '<option value="'.$i.'">'.htmlspecialchars($n).'</option>';
}

?>
    </select>
  </td>
</tr>

<tr>
  <td>Szobák száma:</td>
  <td>
    <input type="text" name="szoba" value="" maxlength="3"/>
  </td>
</tr>

<tr>
  <td>Alapterület:</td>
  <td>
    <input type="text" name="alapterulet" value="" maxlength="10"/> m&sup2;
  </td>
</tr>

<tr>
  <td>Ár:</td>
  <td>
    <input type="text" name="ar" value="" maxlength="10"/> Ft
  </td>
</tr>

<tr>
  <td>Leírás:</td>
  <td>
    <textarea name="leiras"></textarea>
  </td>
</tr>

<tr>
  <td>Megjelenés dátuma:</td>
  <td>
    <input type="text" name="ido" value="<?php echo date('Y-m-d'); ?>" maxlength=""/>
  </td>
</tr>

<tr>
  <td>Jegyzet:</td>
  <td>
    <textarea name="jegyzet"></textarea>
  </td>
</tr>

<tr>
  <td>Státusz:</td>
  <td>
    <select name="status">
      <option value="0">Inaktív</option>
      <option value="1">Aktív</option>
    </select>
  </td>
</tr>

<tr>
  <td colspan="2">
    <input type="submit" name="mentes" value="Mentés" />
  </td>
</tr>

</table>

</form>