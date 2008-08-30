<?php
/* ADD_ICON.PHP */

include('functions.php');

$_USER = checkLogin();
if(!minClass($_USER, 20)) { redir('index.php'); }//log

$error = esc($_GET['error'], 1);
$add_icon_errors = array(
  1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
  2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive.',
  3 => 'The uploaded file was only partially uploaded.',
  4 => 'No file was uploaded.',
  6 => 'Missing a temporary folder.',
  7 => 'Failed to write file to disk.',
  8 => 'File upload stopped by extension.',

  9 => 'The uploaded file was empty. (0 byte)',
  10 => 'The uploaded file exceeds the $max_f_size directive.',
  11 => 'The given file is not passed by the post method.',
  12 => 'Not allowed filetype.',
  13 => 'Failed to move the uploaded file.',
  14 => 'Failed to insert row into MySQL.',
  15 => 'Invalid title.'
);
$max_f_size = 8 * 1024 * 1024 * 1;//1MB

head(false, 'Icons &gt; New icon', $_USER);
box(0);

  if($e = $add_icon_errors[$error]) { echo '<p class="admin_error">'.$e.'</p>'; }
?>

  <form id="add_icon" method="post" enctype="multipart/form-data" action="<?php echo $_HOST; ?>add_icon_handler.php">
    Ikon megnevezése*:
    <br/>
    <input type="text" name="title" maxlength="200"/>
    <br/>
    <br/>
    <br/>

    Képfájl*:
    <br/>
    (max. <?php echo number_format(floor($max_f_size / 1024 / 1024 / 8), 2, '.', ' '); ?> MB, .jpg or .png)
    <br/>
    <input type="file" name="icon"/>
    <br/>
    <br/>

    Ikon permalink címe:
    <br/>
    (ha nincs megadva, átalakítja a címet, ha foglalt, megszámozza)
    <br/>
    <input type="text" name="perma" maxlength="200"/>
    <br/>
    <br/>

    <input type="hidden" name="max_file_size" value="<?php echo $max_f_size; ?>"/>
    <input type="submit" value="Mehet">
  </form>

<?php
box(1);
foot();
?>