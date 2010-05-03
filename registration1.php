<?PHP	
/*
 * Created on 10.04.2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$file="registration1.php";
echo "<table><tr><td>" .
	  "Aus <b>organisatorischen Gründen</b>, hat sich das Organisationskomitee entschieden, für den Brunch eine <b>Registration</b> durchzuführen. " .
	  "Am Sonntagmorgen werden auf Grund dieser Registration die <b>Bändel</b> verteilt, welche für die Teilnahme am Brunch berechtigen." .
	  "</td></tr></table>";
echo "<h5>Schritt 1/3</h5>\n";
if ($debug) {
  echo $_SERVER["PHP_SELF"]." ".$file;
}
echo "<table id='preuserinfo' style=';'>\n";
echo "<tr><td class='info_neutral'>Bitte geben Sie Ihre Benutzeridentifikation und Ihren Registrationscode ein, welche Sie mit der Einladung erhalten haben.</td></tr>";
echo "</table>\n";

echo "<table class='info_neg' id='infonegative' style='display: none;'>\n";
echo "<tr><td>Validierung fehlgeschlagen! Bitte überpüfen Sie Ihre Benuzteridentifikation und Ihren Registrationscode. " .
	 "Kontrollieren Sie Gross- und Kleinschreibung." .
	 "Sollten weiterhin Probleme auftreten, kontaktieren Sie <a href='mailto:info@cupofthealps.ch'>info@cupofthealps.ch</a></td></tr>";
echo "</table>\n";

echo "<table class='info_pos' id='userinfo' style='display: none;'>\n";
echo "<tr><td>Validierung erfolgreich durchgeführt. Bitte klicken Sie auf \"nächster Schritt\".</td></tr>";
echo "</table>\n";
echo "<form name='login' method='POST' action='index.php'>\n";
echo "<table>\n";
//echo "<tr><td>Benutzername:</td><td><input type='text' id='id' name='id' value='' onBlur=\"javascript:showValue(ajax_getContingent(this));\"></td></tr>\n";
?>
<tr><td>Benutzeridentifikation:</td>
<td><input type='text' id='userid' name='userid' value=''></td></tr>
<tr><td>Registrationscode:</td>
<td><input type='text' id='registrationkey' name='registrationkey' value=''"></td></tr>
<?PHP
echo "<tr><td>&nbsp;</td><td>";
?>
<a href="javascript:validate();" class='button' type='submit' id='validieren' value='validieren'>&nbsp;validieren&nbsp;</a>&nbsp;
<?PHP
echo "<input disabled='true' type='submit' id='next' value='nächster Schritt'>";
echo "</td></tr>";
echo "</table>\n";
if (!$debug){
  $hidden="hidden";
}else{
  $hidden="text";
}
echo "<input type='$hidden' id='registrated' name='registrated' value='NOTVALID' />";	
echo "<input type='$hidden' id='_userid_' name='_userid_' value='NOTVALID' />";
echo "<input type='$hidden' id='_registrationkey_' name='_registrated_' value='NOTVALID' />";
echo "</form>\n";
?>
