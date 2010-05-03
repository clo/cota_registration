function check(id,val){
  var v = document.getElementById(id);
  if (v.value == ""){
    alert(val + " is leer");
    return false;
  }else{
    return true;
  }
}

function submit(val,validate){
  if (validate == 'VALID'){
    alert("TODO submit: " + val);
  }  
}

function enable(id){
  document.getElementById(id).disabled = false;
}

function disable(id){
  document.getElementById(id).disabled = true;
}

function fillIt(val){
  //alert(val + " " + id)
  alert(val);	
  //document.getElementById(id).value = val;
}

function showElement(id){
  if (id != null && id != ''){
    document.getElementById(id).style.display = "";
  }
}

function hideElement(id){
  if (id != null && id != ''){
    document.getElementById(id).style.display = "none";
  }
}

function validate(){ 
  hideElement('preuserinfo');
  hideElement('infonegative');
  var userid = document.getElementById('userid').value;
  var key = document.getElementById('registrationkey').value;
  if (userid == "" && key == ""){
    showElement('preuserinfo');
  }
  ajax_checkValidation(document.getElementById('userid').value,document.getElementById('registrationkey').value,{type: 'r',target:'registrated',onUpdate: doValidate});
}

function doValidate(){
  var valid = document.getElementById('registrated').value;
  if (valid == "VALID") {
    enable('next');
    disable('userid');
    disable('registrationkey');
    copy2Element('userid','_userid_');
    copy2Element('registrationkey','_registrationkey_');
    showElement('userinfo');
  }else{
    disable('next');
    enable('userid');
    enable('registrationkey');
    emptyElement('_userid_');
    emptyElement('_registrationkey_');
    hideElement('userinfo');
    if (document.getElementById('userid').value != ""  || 
        document.getElementById('registrationkey').value != ""  ) {
      showElement('infonegative');
    }else{
      
    }
  }
}


function copy2Element(idfrom,idto){
  document.getElementById(idto).value = document.getElementById(idfrom).value; 
}

function emptyElement(id){
  document.getElementById(id).value = "";
}

function validateKontingent(){
  var kon = document.getElementById('kontingent').value * 1;
  var anz = document.getElementById('anzahl').value * 1;
  
  if (anz == 0){ 
    showElement('preuserinfo');
  }else{
    hideElement('preuserinfo');
  }
  hideElement('zuvieleinfo');
  hideElement('userinfo');
  disable('anmelden');
  //alert("_" + anz + "_>_" + kon + "_");
  //alert(typeof anz);
  //alert(typeof kon);
  if (anz > kon) {
    showElement('zuvieleinfo');
  }else{
    if (anz != 0 && anz != "") {
      hideElement('preuserinfo');
      showElement('userinfo');
      enable('anmelden');
      
    }
  }
  
}
