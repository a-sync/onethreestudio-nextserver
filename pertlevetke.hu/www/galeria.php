<?php
require('admin/config.php');

//$categories[0]['name'] = 'Főkategória';
//$categories[0]['cat_id'] = 0;

$cat_res = mysql_query("SELECT * FROM `galeria` ORDER BY `name` ASC");
while($cat = mysql_fetch_assoc($cat_res)) { $categories[$cat['cat_id']] = $cat; }


if(!$categories[$_GET['cat']]) { $_cat = 0; }
else { $_cat = mysql_real_escape_string($_GET['cat']); }

$_tag = false;
if($_GET['tag'] != '') {
  $_cat = false;
  $_tag = mysql_real_escape_string(htmlspecialchars($_GET['tag']));
  $pic_res = mysql_query("SELECT * FROM `kepek` WHERE `tags` LIKE '%,$_tag,%' ORDER BY `pic_id` ASC");
}

if($_cat !== false) {
  $pic_res = mysql_query("SELECT * FROM `kepek` WHERE `cat_id` = '$_cat' ORDER BY `pic_id` ASC");
}

while($pic = mysql_fetch_assoc($pic_res)) { $pictures[$pic['pic_id']] = $pic; }

include 'head.php';
?>
      <div id="side_panel"><!--side_panel-->
        <div id="kepek_head">Kategóriák</div>
        <div id="catlist">
<?php
  foreach($categories as $cat)
  {
    $link = '<a href="?cat='.$cat['cat_id'].'">'.htmlspecialchars($cat['name']).'</a>';
    $link = ($cat['cat_id'] == $_cat && $_cat !== false) ? '<i>'.$link.'</i>' : $link;
    echo $link.'<br/>';
  }
?>
        </div>
      </div><!--/side_panel-->

      <div id="content"><!--content-->

        <!--képek-->
<?php
  if(count($pictures) > 0) {
    $egy_sor = 3; // hány kép mehet egy sorba
    $szamlalo = 0; // minden harmadikat új sorba kell rakni
    foreach($pictures as $pic)
    {
      if($szamlalo == 0) {
        echo '<div class="gal_row">';
      }

      $szamlalo++;

      $tags_a = explode(',', substr($pic['tags'], 1, -1));
      $tags = '';
      foreach($tags_a as $tag) {
        $link = '<a href="?tag='.htmlspecialchars($tag).'">'.htmlspecialchars($tag).'</a>';
        $tags .= ($tag == $_tag) ? '<b>'.$link.'</b>, ' : $link.', ';
      }
      $tags = ($tags != '') ? substr($tags, 0, -2) : '';

      $desc = ($pic['desc'] == '') ? '' : ' - '.nl2br(htmlspecialchars($pic['desc']));
      //$grp = ($_cat !== false) ? $pic['cat_id'] : htmlspecialchars($_tag);
      $grp = 'galeria';
      echo '<div class="pic">'
          .'<div class="pic_title" title="Kategória: '.htmlspecialchars($categories[$pic['cat_id']]['name']).'">'.htmlspecialchars($pic['title']).'</div>'
          .'<a rel="lightbox['.$grp.']" title="'.htmlspecialchars($pic['title']).$desc.'" href="'.$gal['kepek_mappa'].$pic['file'].'"><img src="'.$gal['kepek_thumb'].$pic['file'].'" alt="'.htmlspecialchars($pic['title']).'"/></a>'
          .'<div class="pic_tags">'.$tags.'</div>'
          .'</div>';

      if($szamlalo == $egy_sor) {
        echo '</div>';
        $szamlalo = 0;
      }
    }

    if($szamlalo != 0) {
      echo '</div>';
    }
  }
  elseif($_tag === false) {
    echo '<div id="nincs_kep">Ebben a kategóriában nincsenek még képek...</div>';
  }
  else {
    echo '<div id="nincs_kep">Ilyen cimkével nincsenek képek...</div>';
  }
?>
        <!--/képek-->

      </div><!--/content-->
<?php include 'foot.php'; ?>