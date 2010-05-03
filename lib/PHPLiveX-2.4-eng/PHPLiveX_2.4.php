<?PHP
#########################################
# PHPLiveX Library						#
# (C) Copyright 2006 Arda Beyazoğlu		#
# Version: 2.4							#
# Home Page: www.phplivex.com			#
# Contact: ardabeyazoglu@gmail.com      #
# License: LGPL      					#
#########################################

class PHPLiveX {
	
	/**
	 * Holds the self url
	 *
	 * @var String
	 */
	private $Url;
	/**
	 * Holds the caught errors
	 *
	 * @var String
	 */
	private $Error = "";
	/**
	 * Holds the character encoding of ajax requests
	 *
	 * @var String
	 */
	private $Encoding = "UNICODE";
	/**
	 * Holds the exported functions
	 *
	 * @var Array
	 */
	private $FList = array();
	/**
	 * Determines whether external file is used for java script
	 *
	 * @var Boolean
	 */
	public $ExternalJS = false;
	
	/**
	 * Constructs the class and exports specified functions
	 *
	 * @param String $funcList: includes function names separated by comma
	 * @param String $url: indicates the self url for complex URLs (e.g. URLs manipulated by a .htaccess file)
	 */
	public function __construct($funcList = null, $url = null){
		$this->Export($funcList);

		$query = !empty($_SERVER["QUERY_STRING"]) ? "?" . $_SERVER["QUERY_STRING"] : "";

		if(!$url) $this->Url = $_SERVER["PHP_SELF"] . $query;
		else $this->Url = $url;
	}
	
	/**
	 * Exports specified class methods for ajax requests
	 *
	 * @param String $funcList: includes function names separated by commas
	 */
	public function Export($funcList = null){
		if(!$funcList) return;
		if($funcList == "ALL"){
			$functions = get_defined_functions();
			$funcList = implode(",", $functions["user"]);
		}
		$funcArray = explode(",", $funcList);
		for($i=0;$i<count($funcArray);$i++){
			$funcArray[$i] = stripslashes(trim($funcArray[$i]));
			if(function_exists($funcArray[$i])){
				if(!in_array($funcArray[$i], $this->FList)){
					$this->FList[] = $funcArray[$i];
				}
			}else{
				$this->Error .= "'" . $funcArray[$i] . "' function doesn't exist!";
				?>
				<script language="javascript">
					alert("<?PHP echo $this->Error ?>");
				</script>
				<?PHP
			}
		}
		reset($this->FList);
		$this->Execute();
	}
	
	/**
	 * Exports specified class methods separated by commas for ajax requests
	 *
	 * @param String $funcList: includes functions in style objectName->methodName separated by commas
	 */
	public function ExportObjectMethods($funcList = null){
		if(!$funcList) return;
		$funcArray = explode(",", $funcList);
		for($i=0;$i<count($funcArray);$i++){
			$funcArray[$i] = stripslashes(trim($funcArray[$i]));
			if(strpos($funcArray[$i], "->") !== false) $symbol = "->";
			else if(strpos($funcArray[$i], "::") !== false) $symbol = "::";
			else continue;

			$parts = explode($symbol, $funcArray[$i]);
			$instanceName = $parts[0];
			$funcName = $parts[1];

			global $$instanceName;
			$newName = $instanceName . "__" . $funcName;

			if(!in_array($newName, $this->FList)){
				$_SESSION["plx_" . $newName] = $$instanceName;
	 			$this->FList[] = $newName;
			}
		}
		reset($this->FList);
		$this->Execute();
	}
	
	/**
	 * Exports specified classes for ajax requests
	 *
	 * @param String $funcList: includes object instance names separated by commas
	 */
	public function ExportObjects($instanceList = ""){
		if($instanceList == "") return;
		$instanceArray = explode(",", $instanceList);
		for($i=0;$i<count($instanceArray);$i++){
			$instanceArray[$i] = stripslashes(trim($instanceArray[$i]));

			$instanceName = $instanceArray[$i];
			global $$instanceName;
			$methods = get_class_methods($$instanceName);

			for($k=1;$k<count($methods);$k++){
				$newName = $$instanceName . "__" . $methods[$k];
				if(!in_array($newName, $this->FList)){
					$_SESSION["plx_" . $newName] = $$instanceName;
					$this->FList[] = $newName;
				}
			}
		}
		reset($this->FList);
		$this->Execute();
	}
	
	/**
	 * Calls the function specified by the incoming ajax request and gives the output
	 *
	 */
	private function Execute(){
		$funcName = ""; $funcArgs = array();
		if(isset($_REQUEST["funcName"])){
			$funcName = $_REQUEST["funcName"];
			if(isset($_REQUEST["funcArgs"])) $funcArgs = $_REQUEST["funcArgs"];
			
			if(in_array($funcName, $this->FList)){
				if(strtoupper($this->Encoding) == "UNICODE") $funcArgs = array_map("utf8_encode", $funcArgs);
				if(function_exists("json_decode")){
					foreach ($funcArgs as $key => $val){
						if(preg_match('/<plxobj[^>]*>(.|\n|\t|\r)*?<\/plxobj>/', $val, $matches)){
							$jsobj = substr($matches[0], 8, -9);
							$funcArgs[$key] = json_decode(urldecode($jsobj), true);
						}
					}
				}
				
				if(strpos($funcName, "__") === false){
					$output = call_user_func_array($funcName, $funcArgs);
				}else{
					$parts = explode("__", $funcName);
					$object = $_SESSION["plx_" . $funcName];
					$output = call_user_func_array(array($object, $parts[1]), $funcArgs);
				}
				
				if(function_exists("json_encode") && (is_array($output) || is_object($output))) $output = "<plxobject>" . json_encode($output) . "</plxobject>";
				echo "<phplivex>" . $output . "</phplivex>";
				exit();
			}
		}
	}
	
	/**
	 * Create the java script code to configure and send ajax requests
	 *
	 * @return String JS code
	 */
	private function CreateJS(){
		ob_start();
		?>
		/////////////////////////////////////////
		// PHPLiveX Library                    //	
		// (C) Copyright 2006 Arda Beyazoğlu   //
		// Version: 2.4                        //
		// Home Page: www.phplivex.com 		   //
		// Contact: ardabeyazoglu@gmail.com	   //
		// License: LGPL	   				   //
		/////////////////////////////////////////

		function PHPLiveX(){
			this.Properties = {
				type: "e", mode: "rw", target: null, preload: null, method: "post",
				onRequest: null, onFinish: null, onUpdate: null,
				interval: false, clearValue: false,
				preloadStyle: "display", targetProperty: "innerContent",
				url: null
			};
		}

		PHPLiveX.prototype.ShowError = function(errorMsg){
			if(errorMsg){
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

		PHPLiveX.prototype.CreatePreloading = function(){
			if(this.Properties.preload != null){
				if(this.Properties.preloadStyle == "display") this.Properties.preload.style.display = "";
				this.Properties.preload.style.visibility = "visible";
			}
			if(this.Properties.clearValue) this.Properties.target.innerHTML = "";
		}

		PHPLiveX.prototype.CompletePreloading = function(){
			if(this.Properties.preload != null){
				if(this.Properties.preloadStyle == "display") this.Properties.preload.style.display = "none";
				this.Properties.preload.style.visibility = "hidden";
			}
		}
		
		PHPLiveX.prototype.ExternalCall = function(){
			var args = this.ExternalCall.arguments;
			newargs = new Array();
			for(i=0; i<args.length-1; i++){
				newargs.push(i + "=" + args[i]);
			}
			newargs.push(args[args.length-1]);
			this.Callback(null, newargs);
		}
		
		PHPLiveX.prototype.SubmitForm = function(form, filter, attributes){
			if(filter){
				if(!filter(form)) return false;
			}
			var args = new Array();
			var fields = form.elements;
			for(i=0; i<fields.length; i++){
				if(fields[i].type == "button" || fields[i].type == "submit" || fields[i].type == "reset") continue;
				args.push(fields[i].name + "=" + fields[i].value);
			}
			args.push(attributes);
			this.Callback(null, args);
			return false;
		}
		
		PHPLiveX.prototype.CreateOutput = function(funcName, funcArgs, funcUrl){
			var data = (funcName) ? "funcName=" + escape(funcName) : "";
			var args = new Array();
			if(!funcUrl){ funcUrl = "<?PHP echo $this->Url ?>"; }
			if(funcArgs){
				if(funcArgs.indexOf(",") != -1) args = funcArgs.split(",");
				else args.push(funcArgs);
				if(funcName){
					for (i=0;i<args.length;i++) data += "&funcArgs[]=" + escape(args[i]);
				}else{
					for (i=0;i<args.length;i++){
						key = escape(args[i].split("=")[0]);
						val = escape(args[i].split("=")[1]);
						data += "&" + key + "=" + val;
					}
				}
			}
			
			var _root = this;

			var XmlHttp = this.GetXmlHttp();
			var asynchronous = (this.Properties.type != "r") ? true : false;

			if(this.Properties.method == "get"){
				data += "&RequestId=" + new Date().getTime();
				if(funcUrl.indexOf("?") != -1){
					data = (funcUrl.indexOf("&")) ? "&" + data : data;
					XmlHttp.open("GET", funcUrl + "&" + data, asynchronous);
				}else{
					XmlHttp.open("GET", funcUrl + "?" + data, asynchronous);
				}
			}else XmlHttp.open("post", funcUrl, asynchronous);

			if(this.Properties.method == "post"){
				XmlHttp.setRequestHeader("Method", "POST " + funcUrl + " HTTP/1.1");
				XmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			}

			if(asynchronous){
				this.CreatePreloading();
				if(_root.Properties.onRequest != null) _root.Properties.onRequest();

				XmlHttp.onreadystatechange = function(){
					if(XmlHttp.readyState == 0){
						XmlHttp.abort();
					}else if(XmlHttp.readyState == 4){
						_root.CompletePreloading();

						var output = unescape(XmlHttp.responseText);
						if(output.indexOf("<phplivex>") != -1){
							var outparts = output.split("<phplivex>");
							output = outparts[outparts.length-1].split("</phplivex>")[0];
						}
						
						output = output.replace(/<plus>/g, "+");
						
						var jscode = "";
						var jsparts = output.match(/<script[^>]*>(.|\n|\t|\r)*?<\/script>/gi);
						if(jsparts){
							for(i=0;i<jsparts.length;i++){
								jscode += jsparts[i].replace(/<script[^>]*>|<\/script>/gi, "");
								output = output.replace(jsparts[i], "");
							}
						}
						
						var objparts = output.match(/<plxobject>(.|\n|\t|\r)*?<\/plxobject>/gi);
						if(objparts){
							objparts[0] = objparts[0].replace(/<plxobject>|<\/plxobject>/gi, "");
							eval("output = " + objparts[0]);
						}

						if(_root.Properties.onFinish != null){
							resp = _root.Properties.onFinish(output);
							if(resp != undefined) output = resp;
						}

						if(_root.Properties.preload != null && _root.Properties.myPreload == null){
							if(_root.Properties.preloadStyle == "visibility"){
								_root.Properties.preload.style.visibility = "hidden";
								_root.Properties.preload.style.display = "";
							}
							if(_root.Properties.preloadStyle == "display"){
								_root.Properties.preload.style.display = "none";
								_root.Properties.preload.style.visibility = "visible";
							}
						}
						
						if(_root.Properties.target == "alert"){ alert(output); }
						else if(_root.Properties.type != "e"){
							var attr = _root.Properties.targetProperty;
							var item = _root.Properties.target;
							if(attr == "innerContent"){
								if(item == "[object HTMLInputElement]" || item.type != undefined) attr = "value";
								else attr = "innerHTML";
							}
							switch(_root.Properties.mode){
								case "aw": eval("item." + attr + " += output;"); break;
								case "rw": eval("item." + attr + " = output;"); break;
								case "asw": eval("item." + attr + " = output + item." + attr + ";"); break;
							}
						}

						if(jscode != ""){
							var script = document.createElement("script");
							script.type = "text/javascript"; 
							script.lang = "javascript";
							script.text = jscode;
							document.getElementsByTagName("head")[0].appendChild(script);
						}
						
						if(_root.Properties.onUpdate != null) _root.Properties.onUpdate(output);
					}
				}

				if(this.Properties.method == "get") XmlHttp.send(null);
				else XmlHttp.send(data);
			}else{
				if(this.Properties.method == "get") XmlHttp.send(null);
				else XmlHttp.send(data);
				var output = unescape(XmlHttp.responseText);
				if(output.indexOf("<phplivex>") != -1){
					var outparts = output.split("<phplivex>");
					output = outparts[outparts.length-1].split("</phplivex>")[0];
				}
				output = output.replace(/<plus>/g, "+");
				output = output.replace(/<comma>/g, ",");

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
					script.type = "text/javascript"; 
					script.lang = "javascript";
					script.text = jscode;
					document.getElementsByTagName("head")[0].appendChild(script);
				}

				return output;
			}
		}
		
		PHPLiveX.prototype.ParseArray = function(arr){
			if(arr.length == undefined) return this.ParseObject(arr);
			var values = new Array();

			for(key in arr){
				if(typeof arr[key] == "string") val = "\"" + arr[key] + "\"";
				else if(typeof arr[key] == "object"){
					if(arr[key].length != undefined) val = this.ParseArray(arr[key]);
					else val = this.ParseObject(arr[key]);
				}
				else val = arr[key];
				values.push(val);
			}

			return "[" + values.join(", ") + "]";
		}
		
		PHPLiveX.prototype.ParseObject = function(obj){
			if(obj.length != undefined) return this.ParseArray(obj);
			var values = new Array();

			for(key in obj){
				if(typeof obj[key] == "string") val = "\"" + key + "\": " + "\"" + obj[key] + "\"";
				else if(typeof obj[key] == "object"){
					if(obj[key].length != undefined) val = "\"" + key + "\": " + this.ParseArray(obj[key]);
					else val = "\"" + key + "\": " + this.ParseObject(obj[key]);
				}
				else val = "\"" + key + "\": " + obj[key];
				values.push(val);
			}

			return "{" + values.join(", ") + "}";
		}

		PHPLiveX.prototype.Callback = function(funcName, funcArgs){
			var args = [];
			for(i=0;i<funcArgs.length-1;i++){
				args.push(funcArgs[i]);

				if(typeof(args[i]) == "object") args[i] = "<plxobj>" + escape(this.ParseObject(args[i])) + "</plxobj>";
				else if(funcName) args[i] = escape(args[i]);
				if(String(args[i]).indexOf("+")) args[i] = String(args[i]).replace(/\+/g, "<plus>");
			}
			var params = funcArgs[funcArgs.length-1];
			for(key in this.Properties){
				if(eval("params." + key)){
					eval("var value = params." + key + ";");
					eval("this.Properties." + key + " = value;");
				}
			}

			if(this.Properties.target != null){
				this.Properties.type = null;
				targetId = String(this.Properties.target);
				if(document.getElementById(targetId))
				this.Properties.target = document.getElementById(targetId);
			}
			if(this.Properties.preload != null){
				preloadId = String(this.Properties.preload);
				if(document.getElementById(preloadId))
				this.Properties.preload = document.getElementById(preloadId);
			}
			url = "<?PHP echo $this->Url; ?>";
			if(!this.Properties.url) this.Properties.url = url;
			
			var StringArgs = args.join();
	
			try{
				if(this.Properties.type == "r"){
					return this.CreateOutput(funcName, StringArgs, this.Properties.url);
				}else{
					this.CreateOutput(funcName, StringArgs, this.Properties.url);
				}
			}catch(ex){
				this.ShowError(ex);
			}

			if(this.Properties.interval && callType == null){
				var strArgs = funcArgs[funcArgs.length-1];
				var st = setTimeout("eval(" + funcName + "('" + StringArgs + "', '" + strArgs + "'))", this.Properties.interval);
			}
		}

		<?PHP
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	/**
	 * Creates the java script reflections of the exported php functions
	 *
	 * @param String $funcName
	 * @return String JS code
	 */
	private function CreateFunction($funcName){
		return "function {$funcName}(){ return new PHPLiveX().Callback('$funcName', {$funcName}.arguments); }";
	}
	
	/**
	 * Outputs the created java script code to the page or an external file to include
	 *
	 */
	public function Run(){
		$js = $this->CreateJS();
		for($i=0;$i<count($this->FList);$i++){
			$js .= str_replace("\r\n", "", $this->CreateFunction($this->FList[$i]));
		}
		if(!$this->ExternalJS){
			echo "<script language='javascript'>$js</script>";
			$fn = md5($_SERVER["SCRIPT_NAME"]);
			if(file_exists("PHPLiveX_Core_$fn.js")) unlink("PHPLiveX_Core_$fn.js");
		}else{
			$fn = md5($_SERVER["SCRIPT_NAME"]);
			$fp = fopen("PHPLiveX_Core_$fn.js", "w+");
			fwrite($fp, $js);
			fclose($fp);
			echo "<script language='javascript' src='PHPLiveX_Core_$fn.js'></script>";
		}
	}
	
	/**
	 * Outputs the java script reflections of the php functions
	 *
	 * @return String
	 */
	public function GetFunctions(){
		$html = "";
		for($i=0;$i<count($this->FList);$i++){
			$html .= $this->CreateFunction($this->FList[$i]);
		}
		echo "<script>" . $html . "</script>";
	}
    
	/**
	 * Returns a list of exported php functions
	 *
	 * @return String
	 */
    public function __toString(){
    	$functions = "<b>Registered Functions</b><ol>";
        foreach ($this->FList as $function) $functions .= "<li>$function</li>";
        return $functions . "</ol>";
    }

}


?>