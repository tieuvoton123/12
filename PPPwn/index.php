<?php

$firmwares = array("7.00", "7.01", "7.02", "7.50", "7.51", "7.55", "8.00", "8.01", "8.03", "8.50", "8.52", "9.00", "9.03", "9.04", "9.50", "9.51", "9.60", "10.00", "10.01", "10.50", "10.70", "10.71", "11.00");
$stage2jail = array("goldhen", "hen", "flow", "bestpig");
$cppm = array("1 - v1.0.0 xfangfang", "2 - stooged", "3 - latest xfangfang", "4 - nn9dev");
$detectm = array("1 - Disable", "2 - Detect PS4 Power on", "3 - Detect GoldHEN running", "4 - Detect Both Mode");
$sourceipv6 = array("1 - Old IPv6", "2 - New IPv6", "3 - Custom IPv6");

if (isset($_POST['save'])){
	$config = "#!/bin/bash\n";
	$config .= "CPPMETHOD=\\\"".str_replace(" ", "", trim($_POST["cppmethod"]))."\\\"\n";
	$config .= "INTERFACE=\\\"".str_replace(" ", "", trim($_POST["interface"]))."\\\"\n";
	$config .= "FIRMWAREVERSION=\\\"".$_POST["firmware"]."\\\"\n";
	$config .= "USBETHERNET=".(isset($_POST["usbether"]) ? "true" : "false")."\n";
	$config .= "STAGE2METHOD=\\\"".str_replace(" ", "", trim($_POST["stage2method"]))."\\\"\n";
	$config .= "SOURCEIPV6=\\\"".str_replace(" ", "", trim($_POST["sourceip"]))."\\\"\n";
	$config .= "CUSTOMIPV6=\\\"".str_replace(" ", "", trim($_POST["customip"]))."\\\"\n";
	$config .= "DETECTMODE=\\\"".str_replace(" ", "", trim($_POST["detectmode"]))."\\\"\n";
	$config .= "PPPOECONN=".(isset($_POST["pppoeconn"]) ? "true" : "false")."\n";
	$config .= "PWNAUTORUN=".(isset($_POST["pwnautorun"]) ? "true" : "false")."\n";
	$config .= "PPDBG=".(isset($_POST["ppdbg"]) ? "true" : "false")."\n";
	$config .= "TIMEOUT=\\\"".str_replace(" ", "", trim($_POST["timeout"]))."\\\"\n";
	exec('echo "'.$config.'" | sudo tee /boot/firmware/PPPwn/config.sh');
	sleep(2);

	if (isset($_POST["pppoeconn"]) == false)
	{
		print("<html><head><title>PI-Pwn-Offline</title><meta name=\"viewport\" content=\"width=device-width, initial-scale=1\"></head><style>body{user-select: none;-webkit-user-select: none;background-color: #0E0E14;color: #B6B6B6;font-family: Arial;font-size:20px;}</style><body><br><br><br><center>Web server disabled!<br>Auto shutdown and auto pwn activated.<br>Debug output should set to disable PPDBG=false<br>Enable web server again by change PPPOECONN=true</center></body></html>");
		sleep(1);
		exec('sudo poweroff > /dev/null 2>&1 &');
		exit;
	}
	
	if (($_POST["stage2method"] == "goldhen") || ($_POST["stage2method"] == "bestpig")){
	if (!file_exists('/boot/firmware/PPPwn/stage2/'.$_POST["stage2method"].'/stage2_'.str_replace(".","",$_POST["firmware"]).'.bin'))
	{
		print("<html><head><title>PI-Pwn-Offline</title><meta name=\"viewport\" content=\"width=device-width, initial-scale=1\"></head><style>body{user-select: none;-webkit-user-select: none;background-color: #0E0E14;color: #B6B6B6;font-family: Arial;font-size:20px;}a {padding: 5px 5px;font-size:20px; padding:4px; color:6495ED;} a:hover,a:focus {color: #999999;text-decoration: none;cursor: pointer;}</style><body><br><br><br><center>PPPwn not support this firmware.<br>It will use TheOfficialFloW or change stage2 jailbreak type.<br><br><a href=index.php>Back to main page</a></center></body></html>");
		sleep(1);
		exit;
	}
	}
}

if (isset($_POST['force'])){
	if (file_exists('/tmp/ps4alreadypwned.txt'))
	{
		sleep(1);
		print("<html><head><title>PI-Pwn-Offline</title><meta name=\"viewport\" content=\"width=device-width, initial-scale=1\"></head><style>body{user-select: none;-webkit-user-select: none;background-color: #0E0E14;color: #B6B6B6;font-family: Arial;font-size:20px;}a {padding: 5px 5px;font-size:20px; padding:4px; color:6495ED;} a:hover,a:focus {color: #999999;text-decoration: none;cursor: pointer;}</style><body><br><br><br><center>Had the PS4 already been pwned?<br>Reset jailbreak status in main page to pwn again.<br><br><a href=index.php>Back to main page</a></center></body></html>"); 
		exit;
	}
	else
	{
		sleep(1);
		print("<html><head><title>PI-Pwn-Offline</title><meta name=\"viewport\" content=\"width=device-width, initial-scale=1\"></head><style>body{user-select: none;-webkit-user-select: none;background-color: #0E0E14;color: #B6B6B6;font-family: Arial;font-size:20px;}a {padding: 5px 5px;font-size:20px; padding:4px; color:6495ED;} a:hover,a:focus {color: #999999;text-decoration: none;cursor: pointer;}</style><body><br><br><br><center>The device is trying to pwn PS4...<br>After pppwn is successed, wait 5 seconds to reload the main page.<br><br>If enable GoldHEN detection<br>First pppwn should wait 10 seconnds for GoldHEN communicate with the device.<br><br><a href=index.php>Reload Page</a></center></body></html>");
		exec('sudo bash /boot/firmware/PPPwn/run_web.sh force > /dev/null 2>&1 &');
		exit;
	}
}

if (isset($_POST['reset'])){
	exec('sudo rm /tmp/ps4alreadypwned.txt > /dev/null 2>&1 &');
	sleep(1);
	header("Location: index.php");
	exit;
}

if (isset($_POST['reboot'])){
	sleep(1);
	print("<html><head><title>PI-Pwn-Offline</title><meta name=\"viewport\" content=\"width=device-width, initial-scale=1\"></head><style>body{user-select: none;-webkit-user-select: none;background-color: #0E0E14;color: #B6B6B6;font-family: Arial;font-size:20px;}a {padding: 5px 5px;font-size:20px; padding:4px; color:6495ED;} a:hover,a:focus {color: #999999;text-decoration: none;cursor: pointer;}</style><body><br><br><br><center>The device is rebooting...<br><br><a href=index.php>Reload Page</a></center></body></html>");
	exec('sudo reboot > /dev/null 2>&1 &');
	exit;
}

if (isset($_POST['shutdown'])){
	sleep(1);
	print("<html><head><title>PI-Pwn-Offline</title><meta name=\"viewport\" content=\"width=device-width, initial-scale=1\"></head><style>body{user-select: none;-webkit-user-select: none;background-color: #0E0E14;color: #B6B6B6;font-family: Arial;font-size:20px;}</style><body><br><br><br><center>The device is shutting down!</center></body></html>");
	exec('sudo poweroff > /dev/null 2>&1 &');
	exit;
}

if (isset($_POST['payloads'])){
	sleep(1);
	header("Location: payloads.php");
	exit;
}

if (isset($_POST['pconfig'])){
	sleep(1);
	header("Location: pconfig.php");
	exit;
}


$cmd = 'sudo cat /boot/firmware/PPPwn/config.sh';
exec($cmd ." 2>&1", $data, $ret);
if ($ret == 0){
foreach ($data as $x) {
   if (str_starts_with($x, 'CPPMETHOD')) {
      $cppmethod = (explode("=", str_replace("\"", "", $x))[1]);
   }
   elseif (str_starts_with($x, 'INTERFACE')) {
      $interface = (explode("=", str_replace("\"", "", $x))[1]);
   }
   elseif (str_starts_with($x, 'FIRMWAREVERSION')) {
      $firmware = (explode("=", str_replace("\"", "", $x))[1]);
   }
   elseif (str_starts_with($x, 'USBETHERNET')) {
      $usbether = (explode("=", $x)[1]);
   }
   elseif (str_starts_with($x, 'STAGE2METHOD')) {
      $stage2method = (explode("=", str_replace("\"", "", $x))[1]);
   }   
   elseif (str_starts_with($x, 'SOURCEIPV6')) {
      $sourceip = (explode("=", str_replace("\"", "", $x))[1]);
   }
   elseif (str_starts_with($x, 'CUSTOMIPV6')) {
      $customip = (explode("=", str_replace("\"", "", $x))[1]);
   }   
   elseif (str_starts_with($x, 'DETECTMODE')) {
      $detectmode = (explode("=", str_replace("\"", "", $x))[1]);
   }   
   elseif (str_starts_with($x, 'PPPOECONN')) {
      $pppoeconn = (explode("=", $x)[1]);
   }
   elseif (str_starts_with($x, 'PPDBG')) {
      $ppdbg = (explode("=", $x)[1]);
   }
   elseif (str_starts_with($x, 'PWNAUTORUN')) {
      $pwnautorun = (explode("=", $x)[1]);
   }
   elseif (str_starts_with($x, 'TIMEOUT')) {
      $timeout = (explode("=", str_replace("\"", "", $x))[1]);
   }
}
}else{
   $cppmethod = "3";	
   $interface = "eth0";
   $firmware = "11.00";
   $usbether = "false";
   $stage2method = "flow";
   $sourceip = "2";
   $customip = "9f9f:41ff:9f9f:41ff";
   $detectmode = "2";
   $pppoeconn = "true";
   $pwnautorun = "false";
   $timeout = "5m";
   $ppdbg = "false";
}


if (empty($cppmethod)){ $cppmethod = "3";}
if (empty($interface)){ $interface = "eth0";}
if (empty($firmware)){ $firmware = "11.00";}
if (empty($usbether)){ $usbether = "false";}
if (empty($stage2method)){ $stage2method = "flow";}
if (empty($sourceip)){ $sourceip = "2";}
if (empty($customip)){ $customip = "9f9f:41ff:9f9f:41ff";}
if (empty($detectmode)){ $detectmode = "2";}
if (empty($pppoeconn)){ $pppoeconn = "true";}
if (empty($pwnautorun)){ $pwnautorun = "false";}
if (empty($timeout)){ $timeout = "5m";}
if (empty($ppdbg)){ $ppdbg = "false";}


print("<html> 
<head>
<title>PI-Pwn-Offline</title>
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
<style>

body {
	user-select: none;
    -webkit-user-select: none;
    background-color: #0E0E14;
    color: #B6B6B6;
    font-family: Arial;
}

select {
    background: #454545;
	color: #FFFFFF;
	padding: 3px 5px;
    border-radius: 3px;
	border: 1px solid #6495ED;
}


input[type=text] {
    background: #454545;
	color: #FFFFFF;
	padding: 5px 5px;
    border-radius: 3px;
	border: 1px solid #6495ED;
}

a:active,a:hover,
a:focus {
    outline: 0;
    border: none;
	color: #999999;
    text-decoration: none;
    cursor: pointer;
}

a {
	font-size:16px;
	text-decoration: none;
	color:6495ED;
}

button {
    border: 1px solid #6495ED;
    color: #FFFFFF;
    background: #454545;
    padding: 10px 20px;
    margin-bottom:12px;
    border-radius: 3px;
}

button:hover {
    background: #999999;
}

label[id=pwngreen] {
    border: 2px solid #6495ED;
    color: #FFFFFF;
    background: #4CC417;
    padding: 5px 10px;
    margin-bottom:6px;
    border-radius: 4px;
	font-size: 16px;
}

label[id=pwnred] {
    border: 2px solid #6495ED;
    color: #FFFFFF;
    background: #E30B5D;
    padding: 5px 10px;
    margin-bottom:6px;
    border-radius: 4px;
	font-size: 16px;
}

input:focus {
    outline:none;
}

label {
    padding: 5px 5px;
}

input[type=checkbox] {
    position: relative;
    cursor: pointer;
}

input[type=checkbox]:before {
    content: \"\";
    display: block;
    position: absolute;
    width: 17px;
    height: 17px;
    top: 0;
    left: 0;
    background-color:#e9e9e9;
}

input[type=checkbox]:checked:before {
    content: \"\";
    display: block;
    position: absolute;
    width: 17px;
    height: 17px;
    top: 0;
    left: 0;
    background-color:#1E80EF;
}

input[type=checkbox]:checked:after {
    content: \"\";
    display: block;
    width: 3px;
    height: 8px;
    border: solid white;
    border-width: 0 2px 2px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
    position: absolute;
    top: 2px;
    left: 6px;
}	
	
.logger {
    display: none; 
    position: fixed; 
    z-index: 1; 
    padding-top: 100px; 
    padding-bottom: 100px;
    left: 0;
    top: 0;
    width: 100%; 
    height: 66%; 
    overflow-x:hidden;
    overflow-y:hidden;
    background-color: #00000000;
}


.logger-content {
    position: relative;
    background-color: #0E0E14;
    margin: auto;
    padding: 0;
    border: 1px solid #6495ED;
    width: 80%;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
    -webkit-animation-name: animatetop;
    -webkit-animation-duration: 0.4s;
    animation-name: animatetop;
    animation-duration: 0.4s
}


@-webkit-keyframes animatetop {
    from {top:-300px; opacity:0} 
    to {top:0; opacity:1}
}

@keyframes animatetop {
    from {top:-300px; opacity:0}
    to {top:0; opacity:1}
}

.close {
    color: #6495ED;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #999999;
    text-decoration: none;
    cursor: pointer;
}

.logger-header {
    padding: 2px 8px;
    background-color: #0E0E14;
    color: 0E0E14;
}

.logger-body 
{
    padding: 2px 8px;
}

textarea {
    resize: none;
    border: none;
    background-color: #0E0E14;
    color: #FFFFFF;
    box-sizing:border-box;
    height: 100%;
    width: 100%;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;    
    box-sizing: border-box;         
}

label[id=pwnlog] {
    padding: 5px 5px;
	font-size:14px; 
	padding:4px; 
	color:6495ED;
}

label[id=pwnlog]:hover,
label[id=pwnlog]:focus {
    color: #999999;
    text-decoration: none;
    cursor: pointer;
}

label[id=argslog] {
    padding: 5px 5px;
	font-size:14px; 
	padding:4px; 
	color:6495ED;
}

label[id=argslog]:hover,
label[id=argslog]:focus {
    color: #999999;
    text-decoration: none;
    cursor: pointer;
}

label[id=help] {
    padding: 5px 5px;
	font-size:16px; 
	padding:4px; 
	color:6495ED;
}

label[id=help]:hover,
label[id=help]:focus {
    color: #999999;
    text-decoration: none;
    cursor: pointer;
}

div[id=help]{
    height:100%;
    overflow:auto;
    overflow-x:hidden;
	scrollbar-color: #6495ED #0E0E14;
    scrollbar-width: thin;
}

</style>
<script>
var fid;
if (window.history.replaceState) {
   window.history.replaceState(null, null, window.location.href);
}

function startLog(lf) {
   fid = setInterval(updateLog, 2000, lf);
}

function stopLog() {
  clearInterval(fid);
}

function updateLog(f) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/' + f);
	xhr.setRequestHeader('Cache-Control', 'no-cache');
	xhr.responseType = \"text\";
	xhr.onload = () => {
	if (!xhr.responseURL.includes(f)) {
	xhr.abort();
	return;
	}
	if (xhr.readyState === xhr.DONE) {
    if (xhr.status === 200) {
	document.getElementById(\"text_box\").value = xhr.responseText;
	var textarea = document.getElementById('text_box');
	textArea.createTextRange().scrollIntoView(false);
	}
  }
};
xhr.send();
}

function setEnd() {
	if (navigator.userAgent.includes('PlayStation 4')) {
		let name = document.getElementById(\"plist\");
		name.focus();
		name.selectionStart = name.value.length;
		name.selectionEnd = name.value.length;	
	}
}
</script>
</head>
<body>
<center>

<div id=\"pwnlogger\" class=\"logger\">
<div class=\"logger-content\">
<div class=\"logger-header\">
<a href=\"javascript:void(0);\" style=\"text-decoration:none;\"><span class=\"close\">&times;</span></a></div>
<div class=\"logger-body\">
</div></div></div>
<br>
<form method=\"post\"><button name=\"payloads\">Payloads</button> &nbsp; ");


print("<button name=\"pconfig\">C++ Options</button> &nbsp; <button name=\"reset\">Reset Status</button> &nbsp; <button name=\"force\">Force Pwn</button> &nbsp; <button name=\"reboot\">Reboot the device</button> &nbsp; <button name=\"shutdown\"><font color=\"#E30B5D\">SHUTDOWN</button>
</form>
</center><table align=center><td><form method=\"post\">");



print("<select name=\"cppmethod\">");
for($x =1; $x<=4;$x++)
{
   if ($cppmethod == $x)
   {
	   print("<option value=\"".$x."\" selected>".$cppm[$x-1]."</option>");
   }else{
	   print("<option value=\"".$x."\">".$cppm[$x-1]."</option>");
   }
} 
print("</select><label for=\"cppmethod\">&nbsp; C++ Binary Method (1 = v1.0.0, fastest speed, Old IPv6)</label><br><br>");



print("<select name=\"firmware\">");
foreach ($firmwares as $fw) {
if ($firmware == $fw)
{
	print("<option value=\"".$fw."\" selected>".$fw."</option>");
}else{
	print("<option value=\"".$fw."\">".$fw."</option>");
}
}
print("</select><label for=\"firmware\">&nbsp; Firmware version (GoldHEN = 9.00, 9.60, 10.00, 10.01, 10.50, 10.70, 10.71, 11.00)</label><br><br>");



print("<select name=\"stage2method\">");
foreach ($stage2jail as $s2m) {
if ($stage2method == $s2m)
{
	print("<option value=\"".$s2m."\" selected>".$s2m."</option>");
}else{
	print("<option value=\"".$s2m."\">".$s2m."</option>");
}
}
print("</select><label for=\"stage2method\">&nbsp; Stage2 Jailbreak type (BestPig = 10.50 Only)</label><br><br>");



print("<select name=\"detectmode\">");
for($x =1; $x<=4;$x++)
{
   if ($detectmode == $x)
   {
	   print("<option value=\"".$x."\" selected>".$detectm[$x-1]."</option>");
   }else{
	   print("<option value=\"".$x."\">".$detectm[$x-1]."</option>");
   }
} 
print("</select><label for=\"detectmode\">&nbsp; Detect mode (HEN detection not support)</label><br><br>");



if ($cppmethod !== "1")
{
if (($cppmethod == "3") && ($sourceip == "3")){ $sourceip = "2";}
if ($sourceip == "1") {
$customipd = "4141:4141:4141:4141";
}
else {
$customipd = "9f9f:41ff:9f9f:41ff";
}

print("<select name=\"sourceip\">");
for($x =1; $x<=3;$x++)
{
   if ($sourceip == $x)
   {
	   print("<option value=\"".$x."\" selected>".$sourceipv6[$x-1]."</option>");
   }else{
	   print("<option value=\"".$x."\">".$sourceipv6[$x-1]."</option>");
   }
} 
print("</select><label for=\"sourceip\">&nbsp; Source IPv6</label>");

if ($sourceip == "3")
{
print("<label for=\"customip\">&nbsp; = &nbsp; fe80::&nbsp;</label><input size=\"19\" type=\"text\" name=\"customip\" value=\"".$customip."\" style=\"text-align:center; font-size:16px;\"></label>");
}
else
{
print("<input type=\"hidden\" name=\"customip\" value=\"".$customip."\">");
print("<label for=\"customipd\">&nbsp; = &nbsp; fe80::&nbsp;</label><input size=\"19\" type=\"text\" name=\"customipd\" value=\"".$customipd."\" style=\"text-align:center; font-size:16px;\" disabled></label>");
}
print("<br><br>");
}
else {
print("<input type=\"hidden\" name=\"sourceip\" value=\"".$sourceip."\">");
print("<input type=\"hidden\" name=\"customip\" value=\"".$customip."\">");
}



print("<select name=\"timeout\">");
for($x =1; $x<=5;$x++)
{
   if ($timeout == $x."m")
   {
	   print("<option value=\"".$x."m\" selected>".$x." minute(s)</option>");
   }else{
	   print("<option value=\"".$x."m\">".$x." minutes(s)</option>");
   }
} 
print("</select><label for=\"timeout\">&nbsp; Time to restart PPPwn if it hangs</label><br>");



if ($cppmethod == "1") {
print("<br>");
}



print("<select name=\"interface\">");
$cmd = 'sudo ip link | cut -d " " -f-2 | cut -d ":" -f2-2 ';
exec($cmd ." 2>&1", $idata, $iret);
foreach ($idata as $x) {
$x = trim($x);
if ($x !== "" && $x !== "lo" && $x !== "ppp0" && !str_starts_with($x, "wlan"))
{
if ( $interface ==  $x)
{
print("<option value=\"".$x."\" selected>".$x."</option>");
}else{
print("<option value=\"".$x."\">".$x."</option>");
}
}
}
print("</select><label for=\"interface\">&nbsp; Interface</label>");



if (file_exists('/tmp/ps4alreadypwned.txt')){
	$stage2m = file_get_contents('/tmp/ps4alreadypwned.txt');
	if (empty($stage2m)){ $stage2m = $stage2method;}
	print("&emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; <label id=\"pwngreen\">".ucfirst($stage2m)." is running?</label><br>");
}else{
	print("&emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; <label id=\"pwnred\">".ucfirst($stage2method)." not running?</label><br>");
}



$cval = "";
if ($pwnautorun == "true")
{
$cval = "checked";
}
print("<br><input type=\"checkbox\" name=\"pwnautorun\" value=\"".$pwnautorun."\" ".$cval.">
<label for=\"pwnautorun\">&nbsp;Pwn auto run <font color=\"#FAF884\">(set to disable for force pwn in browser)</font></label><br>");



$cval = "";
if ($pppoeconn == "true")
{
$cval = "checked";
}
print("<br><input type=\"checkbox\" name=\"pppoeconn\" value=\"".$pppoeconn."\" ".$cval.">
<label for=\"pppoeconn\">&nbsp;Enables web server <font color=\"#E30B5D\">(disable web server will enable auto shutdown and pwn auto run)</font></label><br>");



if ($ppdbg == "true")
{
$argslog = "true";
print("<br><input type=\"checkbox\" name=\"ppdbg\" value=\"".$ppdbg."\" checked>
<label for=\"ppdbg\">&nbsp;Enables PPPwn debug and view log</label> &nbsp; <a href=\"javascript:void(0);\" style=\"text-decoration:none;\"><label id=\"pwnlog\">Open Log Viewer</label></a> &nbsp; <a href=\"javascript:void(0);\" style=\"text-decoration:none;\"><label id=\"argslog\">Open Argument Viewer</label></a><br>");
}
else
{
print("<br><input type=\"checkbox\" name=\"ppdbg\" value=\"".$ppdbg."\">
<label for=\"ppdbg\">&nbsp;Enables PPPwn debug and view log</label><br>");
}



$cval = "";
if ($usbether == "true")
{
$cval = "checked";
}
print("<br><input type=\"checkbox\" name=\"usbether\" value=\"".$usbether."\" ".$cval.">
<label for=\"usbether\">&nbsp;Use usb ethernet adapter for console connection</label><br>");



if ($cppmethod == "1") {
print("<br>");
}



print("<div style=\"text-align:center; font-size:16px; padding:4px;\">
<a href=\"javascript:void(0);\" style=\"text-decoration:none;\"><label id=\"help\">Show Help</label></a>
</div>");


print("</td></tr><td align=center><button name=\"save\">Save</button></td></tr>
</form>
</td>
</table>
<script>
var logger = document.getElementById(\"pwnlogger\");
var span = document.getElementsByClassName(\"close\")[0];
");


if ($ppdbg == "true")
{
print("var btn = document.getElementById(\"pwnlog\");
btn.onclick = function() {
  logger.style.display = \"block\";
  var lbody = document.getElementsByClassName(\"logger-body\")[0];
  lbody.innerHTML  = '<textarea disabled id=\"text_box\" rows=\"40\"></textarea>';
  startLog('pwn.log');
}
");
print("var btn_args = document.getElementById(\"argslog\");
btn_args.onclick = function() {
  logger.style.display = \"block\";
  var lbody = document.getElementsByClassName(\"logger-body\")[0];
  lbody.innerHTML  = '<textarea disabled id=\"text_box\" rows=\"40\"></textarea>';
  startLog('args.log');
}
");
}


print("var btn1 = document.getElementById(\"help\");
btn1.onclick = function() {
  logger.style.display = \"block\";
  var lbody = document.getElementsByClassName(\"logger-body\")[0];
  lbody.innerHTML  = \"<br><div id='help' style='text-align: left; font-size: 14px;'> <font color='#F28C28'>C++ Binary Method</font> - each method have a diffrent effect, 1 = xfangfang v1.0.0 fastest method for old model, 2 = stooged binary, 3 = latest xfangfang binary, 4 = nn9dev binary.<br><br><font color='#F28C28'>Firmware version</font> - version of firmware running on the console.<br><br><font color='#F28C28'>Stage2 Jailbreak type</font> - GoldHen, Hen-vtx, TheOfficialFloW and Hen by BestPig.<br><br><font color='#F28C28'>Detect mode</font> - detect ps4 power on and waits link become ready, detect goldhen that useful for rest mode.<br><br><font color='#F28C28'>Source ipv6</font> - if set value to 3, you need to set custom ipv6.<br><br><font color='#F28C28'>Custom ipv6</font> - be careful when set this value, ie 1111:2222:3333:4444, don't miss colon, works with stooged and nn9dev binary.<br><br><font color='#F28C28'>Time to restart PPPwn if it hangs</font> - a timeout in minutes to restart pppwn if the exploit hangs mid process.<br><br><font color='#F28C28'>Interface</font> - this is the lan interface on the device that is connected to the console.<br><br><font color='#F28C28'>Pwn auto run</font> - you must force pwn in browser or disable web server to enable auto run.<br><br><font color='#F28C28'>Enable web server</font> - if disable you can enable again by edit config.sh and change PPPOECONN=true.<br><br><font color='#F28C28'>Enable PPPwn debug and view log</font> - enables debug output from pppwn so you can see the result after exploited.<br><br><font color='#F28C28'>Use usb ethernet adapter for console connection</font> - only enable this if you are using a usb to ethernet adapter to connect to the console.<br><br><font color='#F28C28'>Pi-Pwn-Offline by</font> - <a href='https://github.com/joe97tab/Pi-Pwn-Offline' target='_blank'>joe97tab(PacBK)</a><br><br><font color='#50C878'>Credits</font> - all credit goes to <a href='https://github.com/TheOfficialFloW' target='_blank'>TheOfficialFloW</a>, <a href='https://github.com/xfangfang' target='_blank'>xfangfang</a>, <a href='https://github.com/SiSTR0' target='_blank'>SiSTR0</a>, <a href='https://github.com/EchoStretch/ps4-hen-vtx' target='_blank'>EchoStretch and BestPig</a>, <a href='https://github.com/stooged/PI-Pwn' target='_blank'>stooged</a> who have made this project possible.</center>\";
}

span.onclick = function() {
  logger.style.display = \"none\";
  stopLog();
  var text1 = document.getElementById(\"text_box\");
  text1.value = '';
}

window.onclick = function(event) {
  if (event.target == logger) {
    logger.style.display = \"none\";
	stopLog();
	var text1 = document.getElementById(\"text_box\");
	text1.value = '';
  }
}
");


print("</script>
</body>
</html>");

?>