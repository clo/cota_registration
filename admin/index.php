<?php
$path = "../";
include "../layout/header.php";
/*
 * Created on 26.04.2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

$aReg = $db->getRegistrationOveriview();
$overview = $db->getOverview();

echo "<h3>Übersicht Anmeldung</h3>";
echo "<table cellpadding=0 cellspacing=1 bgcolor=white>";
foreach ($overview as $key => $value){
  echo "<tr style='font-family: courier;'><td>$key:</td><td align='right'>$value</td></tr>";
}
echo "</table>";

echo "<h3>Detailübersicht</h3>";
echo "<table cellpadding=0 cellspacing=1>";
echo "<tr><th>Personenid</th><th align='left'>Teilnehmer</th><th>Registrationskey</th><th>Teilnehmer</th><th>Status</th><th>Bemerkung</th></tr>";
foreach ($aReg as $id => $val){
  if ( $id % 2 ){
  	$bgcolor = "bgcolor='$bgcolor1'";
  }else{
  	$bgcolor = "bgcolor='$bgcolor2'";
  }
  echo "<tr style='font-family: courier;' $bgcolor><td>$val[1]</td><td>$val[0]</td><td>$val[2]</td><td>$val[3]</td><td>$val[4]</td><td>$val[5]</td></tr>";
}
echo "</table>";

include "../layout/footer.php";

?>