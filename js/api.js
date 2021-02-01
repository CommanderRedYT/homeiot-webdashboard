var detailedInfos;
var intervalId;
var _apiusername = "";
var _apicredentials = "";
var dataArr = [];
var dataGetArr = [];
var gaugeIndex = 0;
var textIndex = 0;
var processed = 0;
var getProcess = 0;
var gaugeProcess = 0;
var textProcess = 0;
var i = 0;
var onetime = true;
var type = 0;
var looped = 0;
var triggerStats = 5;

$(document).ready(function () {
  if (getCookie("detailedInfos") == "") setCookie("detailedInfos", true, 99999);
  checkMultiples();
  getAPIinfos();
  setupInterval(100);
  detailedInfos = getCookie("detailedInfos") == "true";
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});

function sendThingerValue(device, thing, value) {
  var _sendurl =
    "https://backend.thinger.io/v3/users/" +
    _apiusername +
    "/devices/" +
    device +
    "/resources/" +
    thing;
  sendxhttp = new XMLHttpRequest();
  sendxhttp.open("POST", _sendurl);
  sendxhttp.setRequestHeader("Content-Type", "application/json");
  sendxhttp.setRequestHeader("Authorization", "Bearer " + _apicredentials);
  sendxhttp.send(value);
}

function getThingerValue(device, thing) {
  getProcess = 1;
  var toThing = thing;
  var _url =
    "https://backend.thinger.io/v3/users/" +
    _apiusername +
    "/devices/" +
    device +
    "/resources/" +
    thing;
  xhttp = new XMLHttpRequest();
  xhttp.open("GET", _url);
  xhttp.setRequestHeader("Authorization", "Bearer " + _apicredentials);
  xhttp.send();
  xhttp.onreadystatechange = checkStatusCode;

  function checkStatusCode() {
    if (xhttp.readyState == 4) {
      dataGetArr = xhttp.responseText;
      if (xhttp.status == 200) handleGet(dataGetArr, toThing);
    }
  }
}

function handleGet(data, thing) {
  typeCode = document.getElementById(thing);
  classList = typeCode.classList;
  if (classList.contains("gauge")) setGauge(thing, data);
  if (classList.contains("text")) setText(thing, data);
  getProcess = 0;
}

function setGauge(target, data) {
  code = target + ".set(" + data + ");";
  eval(code);
  document.getElementById(target).setAttribute("data-value", data);
  getProcess = 0;
  gaugeProcess = 0;
  gaugeIndex++;
}

function setText(target, data) {
  target = document.getElementById(target);
  target.innerHTML = data;
  target.setAttribute("data-value", data);
  if (target.classList.contains("led")) {
    if (!(data == true || data == 1 || data == "true")) {
      console.log("true");
      target.parentElement.children[1].setAttribute("fill", "white");
      target.parentElement.children[1].children[0].setAttribute("d", "M2.23 4.35A6.004 6.004 0 0 0 2 6c0 1.691.7 3.22 1.826 4.31.203.196.359.4.453.619l.762 1.769A.5.5 0 0 0 5.5 13a.5.5 0 0 0 0 1 .5.5 0 0 0 0 1l.224.447a1 1 0 0 0 .894.553h2.764a1 1 0 0 0 .894-.553L10.5 15a.5.5 0 0 0 0-1 .5.5 0 0 0 0-1 .5.5 0 0 0 .288-.091L9.878 12H5.83l-.632-1.467a2.954 2.954 0 0 0-.676-.941 4.984 4.984 0 0 1-1.455-4.405l-.837-.836zm1.588-2.653l.708.707a5 5 0 0 1 7.07 7.07l.707.707a6 6 0 0 0-8.484-8.484zm-2.172-.051a.5.5 0 0 1 .708 0l12 12a.5.5 0 0 1-.708.708l-12-12a.5.5 0 0 1 0-.708z");
    } else {
      console.log("false");
      target.parentElement.children[1].setAttribute("fill", "yellow");
      target.parentElement.children[1].children[0].setAttribute("d", "M2 6a6 6 0 1 1 10.174 4.31c-.203.196-.359.4-.453.619l-.762 1.769A.5.5 0 0 1 10.5 13h-5a.5.5 0 0 1-.46-.302l-.761-1.77a1.964 1.964 0 0 0-.453-.618A5.984 5.984 0 0 1 2 6zm3 8.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1l-.224.447a1 1 0 0 1-.894.553H6.618a1 1 0 0 1-.894-.553L5.5 15a.5.5 0 0 1-.5-.5z");
    }
  }
  getProcess = 0;
  textProcess = 0;
  textIndex++;
}

function updateGauges() {
  if (getProcess == 0 && gaugeProcess == 0) {
    var gauges = document.getElementsByClassName("gauge");
    len = gauges !== null ? gauges.length : 0;
    if (len > 0) {
      gaugeProcess = 1;
      if (gaugeIndex >= len) {
        gaugeIndex = 0;
        type++;
      }
      tmpTarget = gauges[gaugeIndex];
      var device = gauges[gaugeIndex].getAttribute("data-device");
      getThingerValue(device, tmpTarget.id);
    }
  }
}

function updateTexts() {
  if (getProcess == 0 && textProcess == 0) {
    var texts = document.getElementsByClassName("text");
    len = texts !== null ? texts.length : 0;
    if (len > 0) {
      textProcess = 1;
      if (textIndex >= len) {
        textIndex = 0;
        type++;
      }
      tmpTarget = texts[textIndex];
      var device = texts[textIndex].getAttribute("data-device");
      getThingerValue(device, tmpTarget.id);
    }
  }
}

function setupInterval(ms) {
  if (typeof intervalId !== "undefinded") clearInterval(intervalId);
  intervalId = window.setInterval(function () {
    if (_apiusername != "" && _apicredentials != "") loop();
  }, ms);
}

function checkMultiples() {
  var items = document.getElementsByClassName("api");
  len = items !== null ? items.length : 0;
  for (let index = 0; index < len; index++) {
    const element = items[index];
    thing = items[index].id;
    if (element.classList.contains("text"))
      element.innerHTML =
        'WARNING! There are multiple instances of "' + thing + '"';
  }
}

function loop() {
  looped++;
  if (looped > triggerStats) looped = 0;
  if (type > 1) type = 0;
  if (type == 0 && document.getElementsByClassName("gauge").length != 0) {
    updateGauges();
  }
  if (type == 0 && document.getElementsByClassName("gauge").length == 0) type++;
  if (type == 1 && document.getElementsByClassName("text").length != 0) {
    updateTexts();
  }
  if (type == 1 && document.getElementsByClassName("text").length == 0) type++;
  if (processed == 3) processed = 0;
  var devices = document.getElementsByClassName("status");
  len = devices !== null ? devices.length : 0;
  if (i < len && processed == 0) {
    var device = devices[i].id.toString();
    if (looped == triggerStats) getDeviceStats(device);
  } else if (i >= len) {
    i = 0;
    if (onetime) {
      setupInterval(200);
      triggerStats = 17;
      onetime = false;
    }
  }

  if (processed == 1) {
    if (dataArr[0].split(":")[1] == "true") {
      devices[i].classList.remove("alert-danger");
      if (!devices[i].classList.contains("alert-success"));
      devices[i].classList.add("alert-success");
      if (detailedInfos) {
        devices[i].innerHTML =
          'Device "' +
          devices[i].id.toString() +
          '" is online <hr> IP-Address: <b>' +
          dataArr[2].split(":")[1].replace(/(\"|\\)+/g, "") +
          "</b> - Uptime: <b>" +
          timeSince(dataArr[1].split(":")[1]) +
          "</b> - RX/TX: <b>" +
          dataArr[3].split(":")[1] +
          " Bytes /" +
          dataArr[4].split(":")[1] +
          " Bytes </b>";
      } else {
        devices[i].innerHTML =
          'Device "' + devices[i].id.toString() + '" is online';
      }
      processed = 0;
      i++;
    } else if (dataArr[0].split(":")[1] == "false") {
      if (!devices[i].classList.contains("alert-danger"))
        devices[i].classList.add("alert-danger");
      devices[i].classList.remove("alert-success");
      devices[i].innerHTML =
        'Device "' + devices[i].id.toString() + '" is offline';
      processed = 0;
      i++;
    } else {
      processed = 0;
    }
  }
}

function getAPIinfos() {
  var xhttp = new XMLHttpRequest();
  xhttp.open("GET", "api.php");
  xhttp.send();
  xhttp.onreadystatechange = checkStatusCode;

  function checkStatusCode() {
    if (xhttp.readyState == 4) {
      _apiusername = xhttp.getResponseHeader("username");
      _apicredentials = xhttp.getResponseHeader("credentials");
    }
  }
}

function getDeviceStats(device) {
  processed = 2;
  var xhttp = new XMLHttpRequest();
  var _url =
    "https://eu-central.aws.thinger.io/v1/users/" +
    _apiusername +
    "/devices/" +
    device +
    "/stats";
  xhttp.open("GET", _url);
  xhttp.setRequestHeader("Authorization", "Bearer " + _apicredentials);
  xhttp.send();
  xhttp.onreadystatechange = checkStatusCode;
  function checkStatusCode() {
    if (xhttp.readyState == 4) {
      var _apiresponse = xhttp.responseText;
      _apiresponse = _apiresponse.replace("{", "").replace("}", "");
      _apiresponse = _apiresponse.split(",");
      dataArr = _apiresponse;
      processed = 1;
    }
  }
}

function unixTodate(timestamp) {
  var d = timestamp;
  var date = new Date(+d);
  var today = new Date();
  var time = date.toUTCString();
  return time;
}

function timeSince(timestamp) {
  var lang = navigator.language;
  if (lang.includes("-")) lang = lang.split("-")[0];
  moment.locale(lang);
  const d = new Date(+timestamp);
  var now = moment(d).fromNow(true);
  return now;
}
