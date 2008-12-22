<?php
if(!defined('_OTS_ADMIN_')) { header("Location: ../admin.php"); exit; } // ha nincs bejelentkezve (nem admin)
?>
      <form action="" method="post">

        <h2>Jelentkezz be!</h2>
        <?php
         echo '<font color="red">'.$error.'</font>';
        ?>
        <br/>
        Név: <br/><input type="text" name="name"/>
        <br/>
        Jelszó: <br/><input type="password" name="pass"/>
        <br/><br/>
        <input type="submit" name="login" value="Mehet!">
      </form>