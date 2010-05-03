<?PHP
/*
 * Created on 11.04.2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
function ajax_getContingent($valid,$userid) {
  global $db;
  return int($db->getKontingent($valid,$userid));
}

function ajax_checkValidation($userid,$key){
  global $db;
  return  $db->validate($userid,$key);
}
?>
