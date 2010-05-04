<?php
$path = "../";
include "../layout/header.php";
/*
 * Created on 26.04.2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
$aCat = $db->getCategories(date('Y'));

echo "<h3>Kategorieübersicht</h3>";
echo "<form method='post' action='categories.php'>";
echo "Kategorie:&nbsp;<select name='cat' id='cat'>";
echo "<option value='-1'>--> auswählen <-- </option>";
foreach ($aCat as $catid => $catname){
  $selected = "";
  if (isset($_POST['cat']) && $catid == $_POST['cat']){
  	$selected = "selected";
  }
  echo "<option value='$catid' $selected>$catname[1]</option>";
}
echo "</select>&nbsp;";
echo "<input type='submit' value='senden'>";
echo "</form>";
  
if (isset($_POST['cat'])){
  $aPersonen = $db->getPersonen($_POST['cat'],date('Y'));
  echo "<table cellpadding=0 cellspacing=1>";
  echo "<tr><th>Personenid</th><th align='left'>Teilnehmer</th><th>Brunch</th><th>Turnierkarten</th><th>Bemerkungen</th><th>Angemeldet</th></tr>";
  foreach ($aPersonen as $id => $val){
    if ( $id % 2 ){
  	  $bgcolor = "bgcolor='$bgcolor1'";
    }else{
  	  $bgcolor = "bgcolor='$bgcolor2'";
    }
    echo "<tr style='font-family: courier;' $bgcolor><td>$val[1]</td><td>$val[0]</td><td>$val[2]</td><td>$val[3]</td><td>$val[4]</td><td>$val[5]</td></tr>";
  }
  echo "</table>";
}

include "../layout/footer.php";
 
 ?>