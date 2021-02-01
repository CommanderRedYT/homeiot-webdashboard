$(document).ready(function () {
  if(getCookie("detailedInfos") == "") setCookie("detailedInfos", true, 99999);
  document.getElementById("detailedInfo1").checked = (getCookie("detailedInfos") == "true");
  document.getElementById("detailedInfo2").checked = (getCookie("detailedInfos") == "false");
});

function saveAccountCredentials() {
  var APIusername = document.getElementById("apiusername").value;
  var APIcredentials = document.getElementById("apicredentials").value;
  document.getElementById("apiusername").value = "";
  document.getElementById("apicredentials").value = "";

  var xhttp = new XMLHttpRequest();
  xhttp.open("POST", "api.php");
  xhttp.setRequestHeader("Type", "credentials");
  xhttp.setRequestHeader("Username", APIusername);
  xhttp.setRequestHeader("Credentials", APIcredentials);
  xhttp.send();
  xhttp.onreadystatechange = checkStatusCode;

  function checkStatusCode() {
    if (xhttp.readyState == 4) {
      var statuscode = xhttp.status;
      if (statuscode == 200) {
        alert("Successfuly saved credentials!");
      } else {
        alert("Please fill out both text fields!");
      }
    }
  }
}

function updateDetailed() {
  checked = document.getElementById("detailedInfo1").checked;
  setCookie("detailedInfos", checked, 99999);
}