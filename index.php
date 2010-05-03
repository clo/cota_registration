<?php
include "layout/header.php";
/*
 * Created on 10.04.2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

if (session_id()) {
  if (isset($_POST['_userid_'])){
    $_SESSION['userid'] = $_POST['_userid_'];
  }
  if (isset($_POST['_registrationkey_'])){
    $_SESSION['regkey'] = $_POST['_registrationkey_'];
  }
  $_SESSION['sid'] = session_id();
  if (isset($_POST['anzahl'])){
    $_SESSION['anzahl'] = $_POST['anzahl'];
  }
  if (isset($_POST['_kontingent_'])){
    $_SESSION['kontingent'] = $_POST['_kontingent_'];
  }
  if (isset($_POST['kommentar'])){
    $_SESSION['kommentar'] = $_POST['kommentar'];
  }
}

if ($debug){
  echo "<pre>";
  echo "<br />POST:<br />";
  print_r($_POST);
  echo "<br />SESSION: <br />";
  print_r($_SESSION);
  echo "</pre>";
}

echo "<table width='$width' border='0' cellspacing='0' cellpadding='0' align='center''>\n";
echo "<tr><td valign='center' align='middle'><img src='img/$logo' border='0'></td></tr>\n";
echo "<tr><td class='white' valign='center' align='middle'></td></tr>\n";
echo "<tr><td class='bottom' valign='center' align='middle' height='20'>&nbsp;</td></tr>\n";
echo "<tr><td valign='center' align='middle'><h3>$title</h3></td></tr>\n";
echo "<tr><td align='middle' valign='center'>\n";
if(date("Ymd")>$expireddate){
  include("expired.php");
}elseif(isset($_POST['anmelden'])){
  include_once('registration3.php');
}elseif (!isset($_SESSION['sid'])) {
  include_once("registration1.php");
}elseif($_POST['registrated']==$db->CONST_VALID){
  $_SESSION['kontingent'] = $db->getKontingent($_SESSION['userid']);
  include_once("registration2.php");
}else{
  include_once("registration1.php");
}
echo "</td></tr>\n";
echo "<tr><td align='center' valign='center' class='bottom'>$bottom</td></tr>";
echo "</table>";
$db->close();
include_once("layout/footer.php");
?>
