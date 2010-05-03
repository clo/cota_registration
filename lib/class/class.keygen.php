<?php
/*
 * Created on 23.04.2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class KeyGen{
  private $_key = null;
  private $_charset = array("0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F",
                            "G","H","I","J","K","L","M","N","O","P","Q","R","S","T","V","W",
                            "X","Y","Z");

  private $length = 8;

  public function generate($length=null){
  	if (!is_null($length)){
  	  $this->length = $length;
  	}
  	for($i=1;$i<=$this->length;$i++){
  	  //$nr = rand(0,count($this->_charset)-1);
  	  $nr = mt_rand(0,count($this->_charset)-1);
  	  $this->_key .= $this->_charset[$nr];
  	}
  }  
  
  public function getKey(){
  	return $this->_key;
  }
}
?>
