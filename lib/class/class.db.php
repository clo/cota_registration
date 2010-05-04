<?php
/*
 * Created on 10.04.2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class Db{
	private $reg = array ( "1223" => "12qwasyx",
	                       "2334" => "23wesdxc" );
	private $kon = array ( "1223" => "10",
	                       "2334" => "5" );	 
    public $CONST_VALID = "VALID";
    public $CONST_NOTVALID = "NOTVALID";
    private $_link = null;
    	                                            
	public function connect($dbhost,$dbport,$dbuser,$dbpass,$dbname){
		$this->_link= mysql_connect($dbhost.":".$dbport,$dbuser,$dbpass);
		mysql_select_db($dbname);	
	}
	
	public function close(){
		mysql_close($this->_link);
	}
	
	public function validate_OLD($user,$key) {
		$exists = $this->CONST_NOTVALID;
		if(!is_null($user) && !is_null($key)){
		   //check key and userid
		   foreach($this->reg as $id => $val){
		     if ($id == $user && $val == $key){
		     	$exists = $this->CONST_VALID;
		     }
		   }
		}
		return $exists;
	}
	
	public function validate($user,$key){
	  $exists = $this->CONST_NOTVALID;
	  $sql = "SELECT COUNT(*) FROM registration " .
	  		 "WHERE adressid=$user " .
	  		 "AND registrationskey='$key'";
	  //echo $sql;
	  $res = $this->query($sql);
	  $row = mysql_fetch_row($res);
	  if ($row[0] == 0){
	  	return $this->CONST_NOTVALID;
	  }else{
	  	return $this->CONST_VALID;
	  }
//	  return $this->CONST_VALID;
	}
	
	public function getKontingent_OLD($user){
	   $kontingent = 0;
  	   foreach($this->kon as $id => $val){
	     if ($id == $user){
	       $kontingent = $val;
	     }
	   }
	   return $kontingent;
	}
	
	public function getKontingent($user){
	   $kontingent = 0;
  	   $sql = "SELECT kontingent FROM registration " .
  	   		  "WHERE adressid=$user";
  	   $res = $this->query($sql);
  	   $row = mysql_fetch_row($res);
	   return $row[0];
	}
	
	public function saveKontingent($user,$angemeldet,$bemerkung){
	  $sql = "UPDATE registration SET " .
	  		 "bemerkung='$bemerkung'," .
	  		 "angemeldet=$angemeldet " .
	  		 "WHERE adressid=$user";
	  $this->query($sql);	
	}
	
	public function query($sql){
	   $res = mysql_query($sql,$this->_link);
	   if (mysql_errno($this->_link) > 0) {
	   	  echo "DB ERROR: ".mysql_errno().": ".mysql_error()."\n";
	   }
	   return $res;
	}
	
	public function emptyTable($table){
		$sql = "TRUNCATE TABLE $table";
		mysql_query($sql);
	}
	
	public function getAnzahlAngemeldet($user){
	   $sql = "SELECT angemeldet FROM registration WHERE adressid=$user";
	   $res = $this->query($sql);
	   $row = mysql_fetch_row($res);
	   return $row[0];
	}
	
	public function getRegistrationOveriview(){
	  $aVal = array();
	  $sql = "SELECT " .
	  		 "CONCAT(IF(p.name != '' && p.vorname != '',CONCAT(p.name,' ',p.vorname),''),IF(p.firma != '',CONCAT(',',p.firma),''),IF(p.funktion != '',CONCAT(', ',p.funktion),'')) as Teilnehmer, " .  //0
	  		 "p.personendatenid," .                                                               //1
	  		 "r.registrationskey," .                                                              //2
	  		 "CONCAT(r.angemeldet,'/',r.kontingent) AS `A/K`," .                                  //3
 	  		 "IF(r.angemeldet > 0,'ANGEMELDET','N/A') AS `Status`, " .                            //4
 	  		 "bemerkung " .                                                                       //5
	  		 "FROM personendaten p, registration r " .
             "WHERE p.personendatenid=r.adressid " .
	  		 "ORDER By p.personendatenid ASC";
	  $res = $this->query($sql);
	  $i = 1;
	  while ($row = mysql_fetch_row($res)){
	  	$aVal[$i] = $row;
	  	$i++;
	  }
	  return $aVal;
   }
   
   public function getCategories($year){
     $aVal = array();
     $sql = "SELECT k.kategorieid,k.kategorieName FROM kategorien k,geschichte g WHERE g.jahr=$year " .
     		"ORDER BY k.kategorieName ASC";
     $res = $this->query($sql);
     while ($row = mysql_fetch_row($res)){
	  	$aVal[$row[0]] = $row;
	  }
	  return $aVal;
   }
   
   public function getPersonen($catid,$year){
   	  $aVal = array();
   	  $sql = "SELECT " .
   	  		 "CONCAT(IF(p.name != '' && p.vorname != '',CONCAT(p.name,' ',p.vorname),''),IF(p.firma != '',CONCAT(',',p.firma),''),IF(p.funktion != '',CONCAT(', ',p.funktion),'')) as Teilnehmer, " . 
	  		 "p.personendatenid," .
	  		 "p.`Anzahl Brunch`," .
	  		 "p.`Anzahl Turnierkarten`, " .
	  		 "p.Bemerkungen, " .
 	  		 "IF(r.angemeldet > 0,'ANGEMELDET','N/A') AS `Status` " .
	  		 "FROM personendaten p, geschichte g, kategorien k, registration r " .
	  		 "WHERE p.personendatenid=g.personendatenid " .
	  		 "AND k.kategorieid=g.kategorieid " .
	  		 "AND g.jahr=$year " .
	  		 "AND k.kategorieid=$catid " .
	  		 "AND p.personendatenid=r.adressid";
	  $res = $this->query($sql);
	  $i = 1;
	  while ($row = mysql_fetch_row($res)) {
	  	$aVal[$i] = $row;
	  	$i++;
	  }
	  return $aVal;	  		 
   }
   
   public function getOverview(){
   	 $sql = "SELECT SUM(kontingent) AS `Verschickt`,SUM(angemeldet) AS `Angemeldet` FROM registration ";
   	 $res =$this->query($sql);
   	 return mysql_fetch_assoc($res);
   }
   
   public function getUserInfomration($user){
     $sql = "SELECT CONCAT(IF(p.name != '' && p.vorname != '',CONCAT(p.name,' ',p.vorname),''),IF(p.firma != '',CONCAT(',',p.firma),''),IF(p.funktion != '',CONCAT(', ',p.funktion),'')) as Teilnehmer " .
     		"FROM personendaten p WHERE personendatenid=$user";
     $res = $this->query($sql);
     $row = mysql_fetch_row($res);
     return $row[0];
   }
	  		
	
	
}
?>