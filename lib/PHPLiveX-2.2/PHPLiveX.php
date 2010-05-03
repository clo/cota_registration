<?PHP
#########################################
# PHPLiveX Library						#
# (C) Copyright 2006 Arda Beyazoðlu		#
# Version: 2.2							#
# Home Page: phplivex.sourceforge.net	#
# Contact: ardabeyazoglu@gmail.com      #
#########################################

class PHPLiveX {

var $FileUrl;
var $Url;
var $Error = "";

var $FList = array();

function PHPLiveX($funcList = "", $url = ""){
	$this->Export($funcList);
	
	if(!empty($_SERVER['QUERY_STRING'])) $query = "?" . $_SERVER['QUERY_STRING'];
	else $query = "";
	
	if($url == ""){
		$this->Url = $_SERVER['PHP_SELF'].$query;
	}else{
		 $this->FileUrl = $url;
		 $this->Url = $url . $query;
	}
	
}

function Export($funcList = ""){
	if($funcList == "") return;
	$funcArray = explode(",", $funcList);
	for($i=0;$i<count($funcArray);$i++){
		$funcArray[$i] = stripslashes(trim($funcArray[$i]));
		if(function_exists($funcArray[$i])){
			if(!in_array($funcArray[$i], $this->FList)){
				$this->FList[] = $funcArray[$i];
			}
		}else{
			$this->Error .= "'".$funcArray[$i]."' function doesn't exist!";
			?>
			<script language="javascript">
			alert("<?PHP echo $this->Error ?>");
			</script>
			<?PHP
		}
	}
	$this->Execute();
	reset($this->FList);
}

function ExportClassFunctions($funcList = ""){
	if($funcList == "") return;
	$funcArray = explode(",", $funcList);
	for($i=0;$i<count($funcArray);$i++){
		$funcArray[$i] = stripslashes(trim($funcArray[$i]));
		if(strpos($funcArray[$i], "->") === false) continue;
		
		$parts = explode("->", $funcArray[$i]);
		$instanceName = $parts[0];
		$funcName = $parts[1];
		$newName = $instanceName . "__" . $funcName;
		global $$instanceName;
		 
		if(!in_array($newName, $this->FList)){
			$_SESSION["plx_" . $instanceName] = $$instanceName;
	 		$this->FList[] = $newName;
		}
	}
	reset($this->FList);
	$this->Execute();
}

function ExportClass($instanceList){
	if($instanceList == "") return;
	$instanceArray = explode(",", $instanceList);
	for($i=0;$i<count($instanceArray);$i++){
		$instanceArray[$i] = stripslashes(trim($instanceArray[$i]));
		
		$instanceName = $instanceArray[$i];
		global $$instanceName;
		$methods = get_class_methods($$instanceName);
	
		for($k=1;$k<count($methods);$k++){
			$newName = $instanceName . "__" . $methods[$k];
			if(!in_array($newName, $this->FList)){
				$_SESSION["plx_" . $instanceName] = $$instanceName;
				$this->FList[] = $newName;
			}
		}
	}
	reset($this->FList);
	$this->Execute();
}

function Execute(){
	$funcName = ""; $funcArgs = array();
	if(isset($_REQUEST['funcName'])){
		$funcName = $_REQUEST['funcName'];
		if(isset($_REQUEST['funcArgs'])) $funcArgs = $_REQUEST['funcArgs'];
		
		if(strpos($funcName, "__") === false){
			if(in_array($funcName, $this->FList)){
				$runFunc = call_user_func_array($funcName, $funcArgs);
				echo "<PHP  livex>"  .$runFunc . "</phplivex>";
				exit();
			}
		}else{
			if(in_array($funcName, $this->FList)){
				$parts = explode("__", $funcName);
				$object = $_SESSION["plx_" . $parts[0]];
				echo "<PHP  livex>" . $object->$parts[1]($funcArgs) . "</phplivex>";
				exit();
			}
		}
	}
}

function CreateJS(){
	ob_start();
	
	?>
	/////////////////////////////////////////
	// PHPLiveX Library                    //	
	// (C) Copyright 2006 Arda Beyazoðlu   //
	// Version: 2.2                        //
	// Home Page: phplivex.sourceforge.net //
	/////////////////////////////////////////
	
	function PHPLiveX(type, target, mode, preload, method){
		this.TYPE = type;
		this.MODE = mode;
		this.TARGET = target;
		this.PRELOAD = preload;
		this.METHOD = method;
	}
	
	PHPLiveX.prototype.GetSpecialChars = function(str){
		str = str.replace(/%E7/g,"ç");
		str = str.replace(/%C7/g,"Ç");
		str = str.replace(/%F0/g,"ð");
		str = str.replace(/%D0/g,"Ð");
		str = str.replace(/%FD/g,"ý");
		str = str.replace(/%DD/g,"Ý");
		str = str.replace(/%F6/g,"ö");
		str = str.replace(/%D6/g,"Ö");
		str = str.replace(/%FE/g,"þ");
		str = str.replace(/%DE/g,"Þ");
		str = str.replace(/%FC/g,"ü");
		str = str.replace(/%DC/g,"Ü");
		return unescape(str);
	}

	PHPLiveX.prototype.EscapeSpecialChars = function(str){
		str = str.replace(/ç/g,escape("ç"));
		str = str.replace(/Ç/g,escape("Ç"));
		str = str.replace(/ð/g,escape("ð"));
		str = str.replace(/Ð/g,escape("Ð"));
		str = str.replace(/ý/g,escape("ý"));
		str = str.replace(/Ý/g,escape("Ý"));
		str = str.replace(/ö/g,escape("ö"));
		str = str.replace(/Ö/g,escape("Ö"));
		str = str.replace(/þ/g,escape("þ"));
		str = str.replace(/Þ/g,escape("Þ"));
		str = str.replace(/ü/g,escape("ü"));
		str = str.replace(/Ü/g,escape("Ü"));
		return str;
	}
	
	PHPLiveX.prototype.ShowError = function(errorMsg){
		if(errorMsg != ""){
			alert(errorMsg);
			return false;
		}else{
			return true;
		}
	}
	
	PHPLiveX.prototype.GetXmlHttp = function(){
		objXmlHttp = false;
        if (window.XMLHttpRequest) {
            objXmlHttp = new XMLHttpRequest();
            if (objXmlHttp.overrideMimeType) {
                objXmlHttp.overrideMimeType('text/xml');
            }
        } else if (window.ActiveXObject) {
            try {
                objXmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    objXmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {}
            }
        }

        if (!objXmlHttp) {
            alert('Giving up :( Cannot create an XMLHTTP instance');
            return false;
        }

		return objXmlHttp;
	}
	
	PHPLiveX.prototype.CreatePreloading = function(preloadId){
		document.getElementById(preloadId).style.visibility = "visible";
	}
	
	PHPLiveX.prototype.CreateOutput = function(funcName, funcArgs, funcUrl){
		var data = "funcName=" + escape(funcName);
		var args = new Array();
		if(funcUrl == null){ funcUrl = "<?PHP echo $this->Url ?>"; }
		if(funcArgs != ""){
			if(funcArgs.indexOf(",") != -1){
				args = funcArgs.split(",");
				for (i=0;i<args.length;i++) data += "&funcArgs[]=" + escape(args[i]);
			}else{
				data += "&funcArgs[]=" + escape(funcArgs);
			}
		}
		var parentObject = this;
		
		var XmlHttp = this.GetXmlHttp();
		var ajaxType = false;
		if(this.TYPE != "r") ajaxType = true;
		
		if(this.METHOD == "get"){
			if(funcUrl.indexOf("?") != -1){
				XmlHttp.open("GET", funcUrl + "&" + this.EscapeSpecialChars(data), ajaxType);
			}else{
				XmlHttp.open("GET", funcUrl + "?" + this.EscapeSpecialChars(data), ajaxType);
			}
		}else{ XmlHttp.open("post", funcUrl, ajaxType); }
		
		XmlHttp.setRequestHeader("Method", this.METHOD + " " + funcUrl + " HTTP/1.1");
		XmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=iso-8859-9");
		XmlHttp.setRequestHeader("Pragma", "no-cache");
		XmlHttp.setRequestHeader("Cache-Control", "must-revalidate");
		XmlHttp.setRequestHeader("Cache-Control", "no-cache");
		XmlHttp.setRequestHeader("Cache-Control", "no-store");
		
		if(this.TYPE != "r"){
			if(this.PRELOAD != null){ this.CreatePreloading(this.PRELOAD); }
			
			XmlHttp.onreadystatechange = function(){
				if(XmlHttp.readyState == 0){
					XmlHttp.abort();
					if(parentObject.PRELOAD != null){
						document.getElementById(parentObejct.PRELOAD).style.visibility = "hidden";
					}
				}else if(XmlHttp.readyState == 4){
					var output = parentObject.GetSpecialChars(XmlHttp.responseText);
					var outparts = output.split("<PHP  livex>");
					output = outparts[outparts.length-1].split("</phplivex>")[0];
					
					//If JS Code Exists
					var jscode = "";
					var parts = output.match(/<script[^>]*>(.|\n|\t|\r)*?<\/script>/gi);
					if(parts){
						for(i=0;i<parts.length;i++){
							jscode += parts[i].replace(/<script[^>]*>|<\/script>/gi, "");
							output = output.replace(parts[i], "");
						}
					}
					if(jscode != ""){
						var script = document.createElement("script");
						script.type = 'text/javascript'; 
						script.lang = 'javascript';
						script.text = jscode;
						document.getElementsByTagName('head')[0].appendChild(script);
					}
					//
					
					if(parentObject.PRELOAD != null){ document.getElementById(parentObject.PRELOAD).style.visibility = "hidden"; }
					
					if(parentObject.TYPE == "e"){ return; }
					else if(parentObject.TARGET == "alert"){ alert(output); }
					else if(parentObject.MODE == "aw"){ document.getElementById(parentObject.TARGET).innerHTML += output; }
					else if(parentObject.MODE == "rw"){ document.getElementById(parentObject.TARGET).innerHTML = output; }
				}
			}
			
			if(this.METHOD == "get"){ XmlHttp.send(null); }
			else{ XmlHttp.send(this.EscapeSpecialChars(data)); }
		}else{
			if(this.METHOD == "get"){ XmlHttp.send(null); }
			else{ XmlHttp.send(this.EscapeSpecialChars(data)); }
			var output = this.GetSpecialChars(XmlHttp.responseText);
			var outparts = output.split("<PHP  livex>");
			output = outparts[outparts.length-1].split("</phplivex>")[0];
			
			//If JS Code Exists
			var jscode = "";
			var parts = output.match(/<script[^>]*>(.|\n|\t|\r)*?<\/script>/gi);
			if(parts){
				for(i=0;i<parts.length;i++){
					jscode += parts[i].replace(/<script[^>]*>|<\/script>/gi, "");
					output = output.replace(parts[i], "");
				}
			}
			if(jscode != ""){
				var script = document.createElement("script");
				script.type = 'text/javascript'; 
				script.lang = 'javascript';
				script.text = jscode;
				document.getElementsByTagName('head')[0].appendChild(script);
			}
			//
			
			return output;
		}
	}
	
	<?PHP
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
	
}

function CreateFunction($funcName){
	ob_start();
	?>
	
	function <?PHP echo $funcName ?>() {
		var args = new Array(); var plx_args = new Array();
		var plxType = "e"; var plxTarget = null;
		var plxMode = "rw"; var plxPreload = null;
		var plxMethod = "get";
		
		for(i=0;i<<?PHP echo $funcName ?>.arguments.length;i++){ args[i] = <?PHP echo $funcName ?>.arguments[i]; }
		if(args[args.length-1].indexOf(",") != -1){ plx_args = args[args.length-1].split(","); }
		else{ plx_args[0] = args[args.length-1]; }
		
		for(i=0;i<plx_args.length;i++){
			if(plx_args[i].indexOf("type=") != -1){
				plxType = plx_args[i].substr(5);
			}else if(plx_args[i].indexOf("target=") != -1){
				plxTarget = plx_args[i].substr(7);
				plxType = "print";
			}else if(plx_args[i].indexOf("mode=") != -1){
				plxMode = plx_args[i].substr(5);
			}else if(plx_args[i].indexOf("preload=") != -1){
				plxPreload = plx_args[i].substr(8);
			}else if(plx_args[i].indexOf("method=") != -1){
				plxMethod = plx_args[i].substr(7);
			}
		}

		args.splice(args.length-1, 1);
		var FuncArgs = args.join();
		
		var PLX_<?PHP echo $funcName ?> = new PHPLiveX(plxType, plxTarget, plxMode, plxPreload, plxMethod);
				
		try{
			if(plxType == "r"){ 
				return PLX_<?PHP echo $funcName ?>.CreateOutput("<?PHP echo $funcName ?>", FuncArgs<?=!empty($this->FileUrl) ? ",\"".$this->Url."\"" : "" ; ?>);
			}else{
				PLX_<?PHP echo $funcName ?>.CreateOutput("<?PHP echo $funcName ?>", FuncArgs<?=!empty($this->FileUrl) ? ",\"".$this->Url."\"" : "" ;?>);
			}
		}catch(ex){
			PLX_<?PHP echo $funcName ?>.ShowError(ex);
		}
		
			
	}
	<?PHP
	$html = ob_get_contents();
	ob_end_clean();
	return $html;

}

function Run(){
	$html = $this->CreateJS();
	for($i=0;$i<count($this->FList);$i++){
		$html .= $this->CreateFunction($this->FList[$i]);
	}
	echo $html;
}

function GetFunctions(){
	$html = "";
	for($i=0;$i<count($this->FList);$i++){
		$html .= $this->CreateFunction($this->FList[$i]);
	}
	return $html;
}


}

?>