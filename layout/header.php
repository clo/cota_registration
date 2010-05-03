<?php
/*
 * Created on 10.04.2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
session_start();
/*
 * Libraries
 */
if (!isset($path)){
  $path = "./";
}
include_once("$path/cfg/config.inc.php");
include_once("$path/lib/class/class.db.php");
$db = new Db();
$db->connect($dbhost,$dbport,$dbuser,$dbpass,$dbname);
include_once("$path/lib/ajax.php");    	
?>
<html>
<head>
<title><?PHP echo $title?></title>
<link href="css/cota.css" rel="stylesheet" media="screen" type="text/css" />
<?php
/* 
 * AJAX library
 */
include($path."/".$libLiveX);
$ajax = new PHPLiveX();
$ajax->Export("ajax_getContingent");
$ajax->Export("ajax_checkValidation");
?>
<script language='JavaScript' src='js/cota.js' type='text/javascript'></script>
</head>
<body bgcolor="<?PHP echo $body_backgroundcolor;?>">
<?PHP
$ajax->Run();
?>