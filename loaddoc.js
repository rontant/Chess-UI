function LoadDoc(myscript,para,display,async) {
  var xhttp;
  if (window.XMLHttpRequest) {
    xhttp = new XMLHttpRequest();
  } else {
    // code for IE6, IE5
    xhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }

  if (async==true) {  
      xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
         document.getElementById(display).innerHTML = xhttp.responseText;
        }
      };
      xhttp.open("POST", myscript, async);
      if (para=="") {
      } else {
       xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      }
      xhttp.send(para);
      
  } else {
  
      xhttp.open("POST", myscript, async);
      if (para=="") {
      } else {
       xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      }
      xhttp.send(para);
      document.getElementById(display).innerHTML = xhttp.responseText;
  
  }
  
}