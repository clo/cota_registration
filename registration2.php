<?php
/*
 * Created on 10.04.2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$file="registration2.php";
echo "<h5>Schritt 2/3</h5>\n";
if ($debug) {
  echo $_SERVER["PHP_SELF"]." ".$file;
}

echo "<table class='info_neutral' id='preuserinfo' style=';'>\n";
echo "<tr><td>Bitte geben Sie an, wieviele Personen am Brunch teilnehmen.<br />Ihr Kontingent beträgt: ".$_SESSION['kontingent'];
if ($_SESSION['kontingent']==1){
  echo " Person";
}else{
  echo " Personen";
}
echo "</td></tr>";
echo "</table>\n";

echo "<table class='info_neg' id='zuvieleinfo' style='display: none;'>\n";
echo "<tr><td>Sie habe das Kontingent überschritten. Bitte passen Sie dies an, damit Sie sich anmelden können.</td></tr>";
echo "</table>\n";

echo "<table class='info_pos' id='userinfo' style='display: none;'>\n";
echo "<tr><td >Klicken Sie bitte auf anmelden, um die Anzahl der teilnehmenden Personen am Brunch zu bestätigen.</td></tr>";
echo "</table>\n";


echo "<form name='login' method='post' action='index.php'>\n";
echo "<table>\n";
echo "<tr><td>Kontingent:</td><td><input type='text' id='kontingent' name='kontingent' value='".$_SESSION['kontingent']."' disabled></td></tr>\n";
$anzahl = $db->getAnzahlAngemeldet($_SESSION['userid']);
?>
<tr><td>Anzahl Personen:</td><td><input type='text' id='anzahl' name='anzahl' value='<?php echo $anzahl; ?>' onKeyUp="validateKontingent()" onChange="validateKontingent()" onBlur="validateKontingent()"></td></tr>
<?php
echo "<tr><td>Kommentar:</td><td><textarea id='kommentar' name='kommentar'></textarea></td></tr>\n";
echo "<tr><td>&nbsp;</td><td><input disabled='true' type='submit' id='anmelden' name='anmelden' value='anmelden'></td></tr>\n";
if (!$debug){
  $hidden="hidden";
}else{
  $hidden="text";
}
echo "<input type='$hidden' id='_kontingent_' name='_kontingent_' value='".$_SESSION['kontingent']."'/>";	
echo "</table>\n";
echo "</form>\n";
?>
