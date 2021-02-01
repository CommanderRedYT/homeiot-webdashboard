function logout() {
  setCookie("user", "", 0);
  setCookie("PHPSESSID", "", 0);
  location.reload();
}
$(document).ready(function () {
  if (getCookie("detailedInfos") == "") setCookie("detailedInfos", true, 99999);
});

function login() {
  document.getElementById("msg").style = "color: black";
  var xhttp = new XMLHttpRequest();
  var _usrname = document.getElementById("usrname").value;
  var _passwd = document.getElementById("passwd").value;
  document.getElementById("usrname").value = "";
  document.getElementById("passwd").value = "";
  var encryptedHash = CryptoJS.SHA3(_usrname + _passwd);
  xhttp.open("POST", "_comp.php");
  xhttp.setRequestHeader("Hash", encryptedHash);
  xhttp.send();
  xhttp.onreadystatechange = checkStatusCode;

  function checkStatusCode() {
    if (xhttp.readyState == 4) {
      var statuscode = xhttp.status;
      if (statuscode == 200) {
        location.reload();
      } else if (statuscode == 403) {
        document.getElementById("msg").style = "color: red";
        document.getElementById("msg").innerHTML =
          "Wrong username or password!";
      } else if (statuscode == 500) {
        document.getElementById("msg").style = "color: red";
        document.getElementById("msg").innerHTML =
          "The .credentials-file is empty! Please check you setup!";
      }
    }
  }
}

function createHash(username, password) {
  var encryptedHash = CryptoJS.SHA3(username + password);
  return encryptedHash.toString();
}
