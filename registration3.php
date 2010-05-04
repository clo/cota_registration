<?php
/*
 * Created on 10.04.2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$file="registration3.php";
if ($debug) {
  echo "<pre>";
  echo $_SERVER["PHP_SELF"]." ".$file;
  echo $_SESSION["PHP_SELF"];
  echo "</pre>";
}
$db->saveKontingent($_SESSION['userid'],$_SESSION['anzahl'],$_SESSION['kommentar']);
$userinfo = $db->getUserInfomration($_SESSION['userid']);

echo "<h5>Schritt 3/3</h5>\n";
if ($debug) {
  echo $_SERVER["PHP_SELF"]." ".$file;
}
echo "<table class='info_neutral' id='zuvieleinfo'>\n";
echo "<tr><td><b>Registration für: ".$_SESSION['userid'].", $userinfo</b></td></tr>";
echo "<tr><td >Sie haben sich erfolgreich für den Brunch 2010 angemeldet.<br />";
echo "Wir freuen uns auf Ihren Besuch.<br /><br />";
echo "<b>Zur Erinnerung:</b>";
echo "<li>Anzahl angemeldete Personen: ".$_SESSION['anzahl']."/".$_SESSION['kontingent'];
echo "<li>Datum: Sonntag, 23. Mai 2010";
echo "<li>Zeit: 09:30 Uhr";
echo "<li>Ort: MZH Stapfen, Naters<br /><br />";
echo "Sollten Sie noch Fragen haben, zögern Sie nicht, uns zu kontaktieren.<br />";
echo "<?PHPhref='mailto:info@cupofthealps.ch?Subject=Brunch 2010'>info@cupofthealps.ch</a>";
echo "</td></tr>";
echo "</table>\n";
echo "<br /><br />\n";
//TODO: send mail
$header = "From: $mailfrom\r\nReply-To:$mailto";
mail($mailto,"Brunch 2010 Registration $_SESSION['anzahl']."/".$_SESSION['kontingent']",$userinfo,$header);
//$db->close();
?>
