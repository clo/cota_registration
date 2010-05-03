<?php
/*
 * Created on 23.04.2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include_once("../lib/class/class.keygen.php");
include_once("../lib/class/class.db.php");
include_once("../cfg/config.inc.php");

//basic stuff
$db = new Db();
$db->connect($dbhost,$dbport,$dbuser,$dbpass,$dbname);
$db->emptyTable($dbtableregistration);
$year = date('Y');

//get all with brunch
$sql = "SELECT personendatenID,Vorname,Name,Firma,`Anzahl Brunch` " .
	   "FROM $dbtablepersonen " .
	   "WHERE Brunch=-1";
$res =$db->query($sql);

while ($row = mysql_fetch_row($res)){
  $gen = new KeyGen();
  $id = $row[0];
  $firstname = $row[1];
  $lastname = $row[2];
  $company = $row[3];
  $kontingent = $row[4];
  
  $gen->generate();
  $key = $gen->getKey();
  
  $sql = "INSERT INTO $dbtableregistration SET " .
  		 "adressid=$id,jahr=$year,kontingent=$kontingent,angemeldet=0,registrationskey='$key'";
  $db->query($sql);
  $gen = null;
}

$db->close();
?>
