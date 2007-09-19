<?php
require('../functions.php');
reqLogin('../login.php');

//todo: feltöltés végén rákérdezzen a véglegesítésre mielõtt felülírja a meglévõ fájlt (aka: nemtetszik a végeredmény,
//      meglévõ képekbõl is lehessen választani,
//      elsõ lépéshez ugró gomb és jelenlegi munkamenet törlése gomb,
//      felül menü)
//todo: jpg minoseget %ban meglehet adni (imagejpeg() utolso attributuma)
//todo: fájlnév megadható (ütközés esetén kérdez)
//todo: kitöltési szín megadható
//teljes kép is megvágható (vagy külön akármelyik)
/* - - - - - CONFIGS - - - - - */
$max_file_size = 2048;          //maximum file size (kB)
$picDir = '../../works/';              //directory to put the files in (need to be CHMOD 777)
$keepOriginal = 'yes';          //keep the original image (yes|no)

$cropPicDir = '../../works/';          //directory to put the cropped files in (need to be CHMOD 777)
$cropPrefix = '_crop';          //prefix for the cropped filename (important when you keep the oroginal, and store them in the same dir)

$maxWidth = 1600;               //maximum width of the pic to be cropped (if you upload a bigger picture, the script will resize it first)
$maxHeight = 2048;              //maximum height of the pic to be cropped (if you upload a bigger picture, the script will resize it first)
$minWidth = 150;                //minimum width of the pic to be cropped (if you upload a smaller picture, the script will resize it first)
$minHeight = 20;                //minimum height of the pic to be cropped (if you upload a smaller picture, the script will resize it first)

$fixDimensions = 'yes';          //resize the cropped file to a fixed size (yes|no)
$resizeWidth = 700;             //fixed width
$resizeHeight = 100;            //fixed height

$picDirLink = '';               //url of the original pictures directory
$cropPicDirLink = '';           //url of the cropped pictures directory

                                //the info form contains two input fields:
                                //original (the path to the original image (based on the scripts path))
                                //cropped (the path to the cropped image (based on the scripts path))
$infoFormAutoSend = 'no';       //automaticly send the information of the info form (yes|no)
$infoFormAction = '';           //action setting for the info form
$infoFormMethod = '';           //method setting for the info form (get|post)

                                //the cropper types are stored in the \lib\init_cropper\ directory,
                                //drop yours in there and add the filename, and short description here
                                //for more info on cropper types and making cropper types visit:
                                //http://mondaybynoon.com/2007/12/17/crop-and-resize-images-with-gd-or-imagemagick-v11/
//$cropperFile[] = 'basic.js';   //first the filename
//$cropperType[] = 'Basic';      //then the description
$cropperFile[] = 'ratio_7_1.js';
$cropperType[] = 'Ratio 7:1';
$cropperFile[] = 'fixed_700x100.js';
$cropperType[] = 'Fixed 700x100';
$cropperFile[] = 'ratio_1_7.js';
$cropperType[] = 'Ratio 1:7';
$cropperFile[] = 'fixed_100x700.js';
$cropperType[] = 'Fixed 100x700';
/* - - - - - CONFIGS - - - - - */

//config fix
if($picDir == '') { $picDir = 'fullpic'; }
if($cropPicDir == '') { $cropPicDir = 'croppedpic'; }
if(substr($picDir, -1) == '/') { $picDir = substr($picDir, 0, -1); }
if(substr($cropPicDir, -1) == '/') { $cropPicDir = substr($cropPicDir, 0, -1); }
if($picDir == $cropPicDir && $cropPrefix == '') { $cropPrefix = '_crop'; }
if($infoFormAction == '') { $infoFormAutoSend = 'no'; }

//debug E_ALL
error_reporting(0);//nemkellenek hibaüzenetek: 0

//functions
function scaleImage($image,$width,$height,$scale) {
  $newImageWidth = ceil($width * $scale);
  $newImageHeight = ceil($height * $scale);
  $newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
  $source = imagecreatefromjpeg($image);
  imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
  imagejpeg($newImage,$image,100);
  chmod($image, 0777);
  return $image;
}
function getHeight($image) {
  $size = array_values(getimagesize($image));
  list($width,$height,$type,$attr) = $size;
  return $height;
}
function getWidth($image) {
  $size = array_values(getimagesize($image));
  list($width,$height,$type,$attr) = $size;
  return $width;
}
function rotate($image,$degrees) {
  if(function_exists("imagerotate")) { $image = imagerotate($image, $degrees, 0); }
  else {
    function imagerotate($src_img, $angle) {
      $src_x = imagesx($src_img);
      $src_y = imagesy($src_img);
      if ($angle == 180) { $dest_x = $src_x; $dest_y = $src_y; }
      elseif ($src_x <= $src_y) { $dest_x = $src_y; $dest_y = $src_x; }
      elseif ($src_x >= $src_y) { $dest_x = $src_y; $dest_y = $src_x; }
      $rotate=imagecreatetruecolor($dest_x,$dest_y);
      imagealphablending($rotate, false);
      switch ($angle){
        case 90:
          for ($y = 0; $y < ($src_y); $y++) { for ($x = 0; $x < ($src_x); $x++) { $color = imagecolorat($src_img, $x, $y); imagesetpixel($rotate, $dest_x - $y - 1, $x, $color); } }
          break;
        case 270:
          for ($y = 0; $y < ($src_y); $y++) { for ($x = 0; $x < ($src_x); $x++) { $color = imagecolorat($src_img, $x, $y); imagesetpixel($rotate, $y, $dest_y - $x - 1, $color); } }
          break;
        case 180:
          for ($y = 0; $y < ($src_y); $y++) { for ($x = 0; $x < ($src_x); $x++) { $color = imagecolorat($src_img, $x, $y); imagesetpixel($rotate, $dest_x - $x - 1, $dest_y - $y - 1, $color); } }
          break;
        default: $rotate = $src_img;
      };
      return $rotate;
    } $image = imagerotate($image, $degrees);
  }	return $image;
}
function rotateImage($source,$destination,$degrees) {
  copy($source,$destination);
  chmod($destination,0777);
  $final = imagecreatefromjpeg($destination);
  $final = rotate($final,$degrees);
  imagejpeg($final,$destination,100);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-us" lang="en-us">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-2" />
    <meta http-equiv="content-language" content="en-us" />

    <title>JSGD - Cropper and Resizer Tool 1.0</title>

    <link type="text/css" rel="stylesheet" href="screen.css" media="screen, projection" />

<?php
if($_POST['step'] == 'step1') {
?>
    <link type="text/css" rel="stylesheet" href="cropper.css" media="screen, projection" />
    <link type="text/css" rel="stylesheet" href="imig.css" media="screen, projection" />
    <!--[if IE 6]><link type="text/css" rel="stylesheet" href="cropper_ie6.css" media="screen, projection" /><![endif]-->
    <!--[if lte IE 5]><link type="text/css" rel="stylesheet" href="cropper_ie5.css" media="screen, projection" /><![endif]-->

    <script src="lib/prototype.js" type="text/javascript"></script> 
    <script src="lib/scriptaculous.js?load=builder,dragdrop" type="text/javascript"></script>
    <script src="lib/cropper.js" type="text/javascript"></script>

<?php
  if($_FILES['image']['type']=="image/jpeg"||$_FILES['image']['type']=="image/pjpeg") {

    $uploadedFile = $_FILES['image']['tmp_name'];
    $croptype = $_POST['croptype'];

    for($i=0;$i<count($cropperFile);$i++) {
      if($cropperFile[$i] == $croptype) {
        echo '<script src="lib/init_cropper/'.$cropperFile[$i].'" type="text/javascript"></script>';
      }
    }

    echo '</head><body>';

    // SETUP DIRECTORY STRUCTURE WITH GOOD PERMS
    if(!is_dir($picDir)) { mkdir($picDir, 0777); chmod($picDir, 0777); }

    // DEFINE VARIABLES
    $imageFileName = basename($_FILES['image']['name']);
    $imageFileName = str_replace(" ","",$imageFileName);
    $imageFileName = str_replace("'","",$imageFileName);
    $imageLocation = $picDir.'/'.$imageFileName;
    $fileTypeError = false;

    // DELETE FILE IF EXISTING
    if(file_exists($imageLocation)) { chmod($imageLocation, 0777); unlink($imageLocation); }

    // DELETE CROPPED FILE IF EXISTING
    $fullFileName = explode('.',$imageFileName);
    for($i=0;$i<count($fullFileName)-1;$i++){ $fileName .= $fullFileName[$i]; }

    $cropImageLocation = $cropPicDir.'/'.$fileName.$cropPrefix.'.jpg';

    if(file_exists($cropImageLocation)) { chmod($cropImageLocation, 0777); unlink($cropImageLocation); }

    // CHECK FOR IMAGE UPLOAD
    if(move_uploaded_file($uploadedFile, $imageLocation)) {
      chmod($imageLocation, 0777);

      // GET UPLOADED IMAGE DIMENSIONS
      $dimensions['width'] = getWidth($imageLocation);
      $dimensions['height'] = getHeight($imageLocation);

      // RESIZE IF WIDTH IS TOO WIDE
      if(($dimensions['width']>$maxWidth)){
        $scale = $maxWidth/$dimensions['width'];
        $imageLocation = scaleImage($imageLocation,$dimensions['width'],$dimensions['height'],$scale);
        $dimensions['width'] = getWidth($imageLocation);
        $dimensions['height'] = getHeight($imageLocation);
      }

      // RESIZE IF WIDTH IS TOO TALL
      if(($dimensions['height']>$maxHeight)){
        $scale = $maxHeight/$dimensions['height'];
        $imageLocation = scaleImage($imageLocation,$dimensions['width'],$dimensions['height'],$scale);
        $dimensions['width'] = getWidth($imageLocation);
        $dimensions['height'] = getHeight($imageLocation);
      }

      // RESIZE IF WIDTH IS TOO NARROW
      if(($dimensions['width']<$minWidth)){
        $scale = $minWidth/$dimensions['width'];
        $imageLocation = scaleImage($imageLocation,$dimensions['width'],$dimensions['height'],$scale);
        $dimensions['width'] = getWidth($imageLocation);
        $dimensions['height'] = getHeight($imageLocation);
      }

      // RESIZE IF HEIGHT IS TOO SHORT
      if(($dimensions['height']<$minHeight)){
        $scale = $minHeight/$dimensions['height'];
        $imageLocation = scaleImage($imageLocation,$dimensions['width'],$dimensions['height'],$scale);
        $dimensions['width'] = getWidth($imageLocation);
        $dimensions['height'] = getHeight($imageLocation);
      }
?>

  <div class="info">

    <h1>Crop Your Image</h1>
    <p>You may click and drag an area within the image to crop.	
    <?php if($fixDimensions != 'yes') { ?>
      Once complete you will be able to scale/resize your image before saving.
    <?php } else { ?>
      This is the final step. Once complete the image will be resized to <strong><?php echo $resizeWidth.'x'.$resizeHeight; ?></strong> before saving.
    <?php } ?>
    </p>

  </div>

  <div id="cropContainer">
    <?php
      for($i=0;$i<count($cropperFile);$i++) {
        if($cropperFile[$i] == $croptype) {
          echo '<h2>'.$cropperType[$i].'</h2>';
        }
      }
    ?>
    <div id="crop">
      <div id="cropWrap">
        <img src="<?php echo $imageLocation ?>" alt="Image to crop" id="cropImage" />
      </div>
    </div>

    <div id="crop_save">
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="frmCrop">

        <fieldset>

          <legend>Continue</legend>
          <?php if($fixDimensions == 'yes') { ?>
            <input type="hidden" class="hidden" name="step" value="step4" />
          <?php } else { ?>
            <input type="hidden" class="hidden" name="step" value="step2" />
          <?php } ?>
          <input type="hidden" class="hidden" name="imageWidth" id="imageWidth" value="<?php echo $dimensions['width'] ?>" />
          <input type="hidden" class="hidden" name="imageHeight" id="imageHeight" value="<?php echo $dimensions['height'] ?>" />
          <input type="hidden" class="hidden" name="imageFileName" id="imageFileName" value="<?php echo $imageFileName ?>" />
          <input type="hidden" class="hidden" name="cropX" id="cropX" value="0" />
          <input type="hidden" class="hidden" name="cropY" id="cropY" value="0" />
          <input type="hidden" class="hidden" name="cropWidth" id="cropWidth" value="<?php echo $dimensions['width'] ?>" />
          <input type="hidden" class="hidden" name="cropHeight" id="cropHeight" value="<?php echo $dimensions['height'] ?>" />

          <div id="submit">
            <input type="submit" value="Save" name="save" id="save" />
          </div>

          <div id="rotate">
            <input type="radio" name="rotate" value="0" checked="checked" />No rotate
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="rotate" value="90" />Rotate 90 degrees
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="rotate" value="180" />Rotate 180 degrees
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="rotate" value="270" />Rotate 270 degrees
          </div>

        </fieldset>

      </form>
    </div>

  </div>

  <?php
    }
    else { die('</head><body><div class="info"><h1>File Upload Error</h1><p>You need to set CHMOD 777 on these directory\'s: <b>'.$picDir.'</b>, <b>'.$cropPicDir.'</b>.</p></div></body></html>'); }
  }
  else {
    if($_FILES['image'] ['error']) {
      switch ($_FILES['image'] ['error']) {
        case 1:
          $error = 'The file is bigger than this PHP installation allows.';
          break;
        case 2:
          $error = 'The file is bigger than '.$max_file_size.'kB.';
          break;
        case 3:
          $error = 'Only part of the file was uploaded.';
          break;
        case 4:
          $error = 'No file was uploaded.';
          break;
      }
    }
    else { $error = 'The file is not a .jpg type.'; }
  ?>

  </head>
  <body>

    <div class="info">

      <h1>File Upload Error</h1>
      <p>There was an error uploading the file.	 <?php echo $error; ?></p>

    </div>
<?php
  }
}
elseif($_POST['step'] == 'step2' && $fixDimensions != 'yes') {
?>
    <link type="text/css" rel="stylesheet" href="css/imig.css" media="screen, projection" />

    <script src="lib/prototype.js" type="text/javascript"></script>
    <script src="lib/slider.js" type="text/javascript"></script>
    <script src="lib/init_resize.js" type="text/javascript"></script>

  </head>
  <body>

<?php

  if(isset($_POST['imageFileName'])) {

    // DEFINE VARIABLES
    $imageWidth = $_POST['imageWidth'];
    $imageHeight = $_POST['imageHeight'];
    $imageFileName = $_POST['imageFileName'];

    $cropX = $_POST['cropX'];
    $cropY = $_POST['cropY'];
    $cropWidth = $_POST['cropWidth'];
    $cropHeight = $_POST['cropHeight'];

    if($cropWidth == 0) { $cropWidth = $imageWidth; }
    if($cropHeight == 0) { $cropHeight = $imageHeight; }

    // GET THE FILENAME
    $fullFileName = explode('.',$imageFileName);
    for($i=0;$i<count($fullFileName)-1;$i++){ $fileName .= $fullFileName[$i]; }

    $sourceFile = $picDir.'/'.$imageFileName;
    $destinationFile = $cropPicDir.'/'.$fileName.$cropPrefix.'.jpg';

    if(file_exists($destinationFile)) { chmod($destinationFile, 0777); unlink($destinationFile); }

    // CREATE TEMPORARY FILE NAME
    $tempFileName1 = substr(md5(microtime(TRUE)), 0, 8);
    $tempFile1 = $cropPicDir.'/'.$tempFileName1.'.jpg';

    // CHECK TO SEE IF WE NEED TO CROP
    if($imageWidth != $cropWidth || $imageHeight != $cropHeight) {
      $canvas = imagecreatetruecolor($cropWidth,$cropHeight);
      $piece = imagecreatefromjpeg($sourceFile);
      imagecopy($canvas,$piece,0,0,$cropX,$cropY,$cropWidth,$cropHeight);
      imagejpeg($canvas,$tempFile1,100);
      imagedestroy($canvas);
      imagedestroy($piece);
      chmod($tempFile1, 0777);
    }
    else {
      // CROP WAS SKIPPED -- MOVE TO CROPPED FOLDER ANYWAY	
      copy($sourceFile,$tempFile1);
      chmod($tempFile1, 0777);
    }

    // DELETE UPLOADED PIC IF WE DONT NEED IT
    if($keepOriginal != 'yes') { if(file_exists($sourceFile)) { chmod($sourceFile, 0777); unlink($sourceFile); } }

    // CREATE ANOTHER TEMPORARY FILE NAME TO AVOID THE USE OF A CASHED IMAGE
    $tempFileName2 = substr(md5(microtime(TRUE)), 0, 8);
    $tempFile2 = $cropPicDir.'/'.$tempFileName2.'.jpg';

    if($_POST['rotate'] != 0) {
      // ROTATE IMAGE
      rotateImage($tempFile1,$tempFile2,$_POST['rotate']);
      chmod($tempFile2, 0777);
      if(file_exists($tempFile1)) { chmod($tempFile1, 0777); unlink($tempFile1); }
    }
    else {
      copy($tempFile1,$tempFile2);
      chmod($tempFile2, 0777);
      if(file_exists($tempFile1)) { chmod($tempFile1, 0777); unlink($tempFile1); }
    }

    // GET THE NEW IMAGE SIZES
    list($width, $height) = getimagesize($tempFile2);
      $imageWidth = $width;
      $imageHeight = $height;

    // FRESH START FOR RESIZE
    $cropX = 0;
    $cropy = 0;
    $cropWidth = $imageWidth;
    $cropHeight = $imageHeight;
?>


    <div class="info">
      <h1>Resize</h1>
      <p>You can now scale your image to a size you prefer.	This is the final step.</p>
    </div>

    <div id="resize_save">
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="frmResize">

        <fieldset>

          <legend>Save Resize</legend>
          <input type="hidden" class="hidden" name="step" value="step3" />
          <input type="hidden" class="hidden" name="tempFileName" value="<?php echo $tempFileName2 ?>" />
          <input type="hidden" class="hidden" name="imageWidth" id="imageWidth" value="<?php echo $imageWidth ?>" />
          <input type="hidden" class="hidden" name="imageHeight" id="imageHeight" value="<?php echo $imageHeight ?>" />
          <input type="hidden" class="hidden" name="imageFileName" id="imageFileName" value="<?php echo $imageFileName ?>" />
          <input type="hidden" class="hidden" name="cropX" id="cropX" value="<?php echo $cropX ?>" />
          <input type="hidden" class="hidden" name="cropY" id="cropY" value="<?php echo $cropY ?>" />
          <input type="hidden" class="hidden" name="cropWidth" id="cropWidth" value="<?php echo $cropWidth ?>" />
          <input type="hidden" class="hidden" name="cropHeight" id="cropHeight" value="<?php echo $cropHeight ?>" />
          <input type="hidden" class="hidden" name="resizeWidth" id="resizeWidth" value="<?php echo $cropWidth ?>" />
          <input type="hidden" class="hidden" name="resizeHeight" id="resizeHeight" value="<?php echo $cropHeight ?>" />
          <input type="hidden" class="hidden" name="resizeScale" id="resizeScale" value="1" />

          <div id="submit">
            <input type="submit" value="Save" name="save" id="save" />
          </div>			

        </fieldset>

      </form>			 
    </div>

    <div id='resizeTrack'>
      <div id='resizeHandle'></div>
    </div>

    <div id="resizeImage">
      <?php echo '	<img src="'.$tempFile2.'" id="theImage" alt="Image to Resize" />'; ?>
    </div>

<?php
  }
  else {
?> 

    <div class="info">
      <h1>Error</h1>
      <p>There was an error.</p> 
    </div>

<?php
  }
}
elseif($_POST['step'] == 'step3' && $fixDimensions != 'yes') {
?>
    <link type="text/css" rel="stylesheet" href="imig.css" media="screen, projection" />

  </head>
  <body>

<?php
  menu('../');

  if(isset($_POST['imageFileName']) && isset($_POST['tempFileName'])) {

    $tempFileName = $_POST['tempFileName'];
    $tempFile = $cropPicDir.'/'.$tempFileName.'.jpg';

    $imageFileName = $_POST['imageFileName'];
    $scale = $_POST['resizeScale'];

    $imageWidth = $_POST['imageWidth'];
    $imageHeight = $_POST['imageHeight'];
    $resizeWidth = floor($imageWidth * $scale);
    $resizeHeight = floor($imageHeight * $scale);

    if($cropWidth == 0) { $cropWidth = $imageWidth; }
    if($cropHeight == 0) { $cropHeight = $imageHeight; }

    // GET THE FILENAME
    $fullFileName = explode('.',$imageFileName);
    for($i=0;$i<count($fullFileName)-1;$i++){ $fileName .= $fullFileName[$i]; }

    $sourceFile = $picDir.'/'.$imageFileName;
    $destinationFile = $cropPicDir.'/'.$fileName.$cropPrefix.'.jpg';

    // CHECK TO SEE IF WE NEED TO CROP
    if($imageWidth != $resizeWidth) {
      $newImage = imagecreatetruecolor($resizeWidth,$resizeHeight);
      $source = imagecreatefromjpeg($tempFile);
      imagecopyresampled($newImage,$source,0,0,0,0,$resizeWidth,$resizeHeight,$imageWidth,$imageHeight);
      imagejpeg($newImage,$destinationFile,100);
      chmod($destinationFile, 0777);

    } else { // RESIZE WAS SKIPPED
      copy($tempFile,$destinationFile);
      chmod($destinationFile, 0777);
    }
    if(file_exists($tempFile)) { chmod($tempFile, 0777); unlink($tempFile); }

    $originalFile = $imageFileName;
    $croppedFile = $fileName.$cropPrefix.'.jpg';
?>

    <div class="info">
      <h1>All Done</h1>
      <p>Your image has been cropped and resized as you'd like.	The image below can be used as you see it.</p>
      <p><a href="<?php echo $_SERVER['PHP_SELF']; ?>">Lets go to the start!</a></p>

        <form name="form" id="infoForm" action="<?php echo $infoFormAction; ?>" method="<?php echo $infoFormMethod; ?>">
        <?php if($keepOriginal == 'yes') { ?>
          <input type="text" name="original" size="64" value="<?php echo $picDirLink.$originalFile; ?>" />
          <br />
        <?php } ?>
          <input type="text" name="cropped" size="64" value="<?php echo $cropPicDirLink.$croppedFile; ?>" />

        <?php if($infoFormAction != '' && $infoFormMethod != '') { ?>
          <div id="submit"><input type="submit" value="Send" /></div>
        <?php } ?>
        </form>
        <?php if($infoFormAutoSend == 'yes') { echo '<script>document.form.submit();</script>'; } ?>
    </div>

    <div id="completedImage">
      <?php
      echo '<img src="'.$destinationFile.'" id="theImage" alt="Final Image" />';
      ?>
    </div>

<?php
  }
  else {
?> 
  </head>
  <body>

    <div class="info">

      <h1>Error</h1>
      <p>There was an error.</p> 

    </div>

<?php
  }
}
elseif($_POST['step'] == 'step4' && $fixDimensions == 'yes') {
?>
    <link type="text/css" rel="stylesheet" href="imig.css" media="screen, projection" />

  </head>
  <body>

<?php
  menu('../');

  if(isset($_POST['imageFileName'])) {

    // DEFINE VARIABLES
    $imageWidth = $_POST['imageWidth'];
    $imageHeight = $_POST['imageHeight'];
    $imageFileName = $_POST['imageFileName'];
    $cropX = $_POST['cropX'];
    $cropY = $_POST['cropY'];
    $cropWidth = $_POST['cropWidth'];
    $cropHeight = $_POST['cropHeight'];
    
    if($cropWidth == 0) { $cropWidth = $imageWidth; }
    if($cropHeight == 0) { $cropHeight = $imageHeight; }

    // GET THE FILENAME
    $fullFileName = explode('.',$imageFileName);
    for($i=0;$i<count($fullFileName)-1;$i++){ $fileName .= $fullFileName[$i]; }

    $sourceFile = $picDir.'/'.$imageFileName;
    $destinationFile = $cropPicDir.'/'.$fileName.$cropPrefix.'.jpg';

    if(file_exists($destinationFile)) { chmod($destinationFile, 0777); unlink($destinationFile); }

    // CREATE TEMPORARY FILE NAME
    $tempFileName = substr(md5(microtime(TRUE)), 0, 8);
    $tempFile = $cropPicDir.'/'.$tempFileName.'.jpg';

    // CHECK TO SEE IF WE NEED TO CROP
    if($imageWidth != $cropWidth || $imageHeight != $cropHeight) {
      $canvas = imagecreatetruecolor($cropWidth,$cropHeight);
      $piece = imagecreatefromjpeg($sourceFile);
      imagecopy($canvas,$piece,0,0,$cropX,$cropY,$cropWidth,$cropHeight);
      imagejpeg($canvas,$tempFile,100);
      imagedestroy($canvas);
      imagedestroy($piece);
      chmod($tempFile, 0777);
    }
    else {
      // CROP WAS SKIPPED -- MOVE TO CROPPED FOLDER ANYWAY	
      copy($sourceFile,$tempFile);
      chmod($tempFile, 0777);
    }

    // DELETE UPLOADED PIC IF WE DONT NEED IT
    if($keepOriginal != 'yes') { if(file_exists($sourceFile)) { chmod($sourceFile, 0777); unlink($sourceFile); } }

    if($_POST['rotate'] != 0) {
      // ROTATE IMAGE
      rotateImage($tempFile,$destinationFile,$_POST['rotate']);
      chmod($destinationFile, 0777);
    }
    else {
      copy($tempFile,$destinationFile);
      chmod($destinationFile, 0777);
    }
    if(file_exists($tempFile)) { chmod($tempFile, 0777); unlink($tempFile); }

    // GET THE NEW IMAGE SIZES
    list($width, $height) = getimagesize($destinationFile);
    $imageWidth = $width;
    $imageHeight = $height;

    // CHECK TO SEE IF WE NEED TO CROP
    if($imageWidth != $resizeWidth || $imageHeight != $resizeHeight) {
      $newImage = imagecreatetruecolor($resizeWidth,$resizeHeight);
      $source = imagecreatefromjpeg($destinationFile);
      imagecopyresampled($newImage,$source,0,0,0,0,$resizeWidth,$resizeHeight,$imageWidth,$imageHeight);
      imagejpeg($newImage,$destinationFile,100);
      chmod($destinationFile, 0777);
    }

    $originalFile = $imageFileName;
    $croppedFile = $fileName.$cropPrefix.'.jpg';
?>

    <div class="info">
      <h1>All Done</h1>
      <p>Your image has been cropped and resized as you'd like.	The image below can be used as you see it.</p>
      <p><a href="<?php echo $_SERVER['PHP_SELF']; ?>">Lets go to the start!</a></p>

        <form name="form" id="infoForm" action="<?php echo $infoFormAction; ?>" method="<?php echo $infoFormMethod; ?>">
        <?php if($keepOriginal == 'yes') { ?>
          <input type="text" name="original" size="64" value="<?php echo $picDirLink.$originalFile; ?>" />
          <br />
        <?php } ?>
          <input type="text" name="cropped" size="64" value="<?php echo $cropPicDirLink.$croppedFile; ?>" />

        <?php if($infoFormAction != '' && $infoFormMethod != '') { ?>
          <div id="submit"><input type="submit" value="Send" /></div>
        <?php } ?>
        </form>
        <?php if($infoFormAutoSend == 'yes') { echo '<script>document.form.submit();</script>'; } ?>
    </div>

    <div id="completedImage">
      <?php
      echo '<img src="'.$destinationFile.'" id="theImage" alt="Final Image" />';
      ?>
    </div>

<?php
  }
  else {
?> 
  </head>
  <body>

    <div class="info">

      <h1>Error</h1>
      <p>There was an error.</p> 

    </div>

<?php
  }
}
else {
?>
    <script type="text/javascript" src="lib/prototype.js"></script>
    <script type="text/javascript" src="lib/scriptaculous.js"></script>
    <script type="text/javascript" src="lib/init_wait.js"></script>
  </head>
  <body>

  <?php menu('../'); ?>

    <div class="info">

      <h1>JSGD - Cropper and Resizer Tool</h1>
      <p>The following is a JavaScript &amp; PhP based Crop and Resize tool. Using a combination of PHP, 
      LibGD, Prototype, script.aculo.us, and the JavaScript Image Cropper UI by Dave Spurr, 
      this script provides a visitor the ability to upload an image, crop it to their liking, 
      rotate it, and then resize the picture.</p>

      <p>
      Current filesize limit is <strong><?php echo $max_file_size; ?></strong> kB, and <strong>.jpg</strong> filetype.<br />
      <?php if($fixDimensions == 'yes') { ?>
        <br />
        The image will be resized to <strong><?php echo $resizeWidth.'x'.$resizeHeight; ?></strong> automaticly before saving.<br />
      <?php } ?>
      <br />
      Minimum image resolution width/height is: <strong><?php echo $minWidth.'/'.$minHeight; ?></strong>.<br />
      Maximum image resolution width/height is: <strong><?php echo $maxWidth.'/'.$maxHeight; ?></strong>.<br />
      Anything smaller or bigger then the minimum or maximum will be first converted to the nearest limit.<br />
      <br />
      The original uploaded file is <strong><?php if($keepOriginal == 'yes') { echo 'also'; } else { echo 'not'; } ?></strong> stored.
      </p>

      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="imageUpload" id="imageUpload" enctype="multipart/form-data">
      <fieldset>

        <legend>Upload Image</legend>
        <input type="hidden" class="hidden" name="max_file_size" value="<?php echo ($max_file_size * 1024); ?>" />
        <input type="hidden" class="hidden" name="step" value="step1" />

        <div class="file">
          <label for="image">Image</label>
          <input type="file" name="image" id="image" />
        </div>

        <div class="select">
          <label for="croptype">Crop type</label>
          <select id="croptype" name="croptype">
            <?php
              for($i=0;$i<count($cropperFile);$i++) {
                echo '<option value="'.$cropperFile[$i].'">'.$cropperType[$i].'</option>';
              }
            ?>
          </select>
        </div>

        <div id="submit">
          <input class="submit" type="submit" name="submit" value="Upload" id="upload" />
        </div>

        <div class="hidden" id="wait">
          <img src="images/wait.gif" alt="Please wait..." />
        </div>

      </fieldset>
      </form>

    </div>
<?php
}
?>
<!-- modded by Vector Akashi -->
  </body>
</html>