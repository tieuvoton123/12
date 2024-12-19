<?php 

$spraynumber = array("400", "450", "500", "550", "600", "650", "700", "750", "800", "850", "900", "950", "1000", "1050", "1100", "1150", "1200", "1250", "1300", "1350", "1400", "1450", "1500");
$corruptnumber = array("1", "2", "4", "6", "8", "10", "14", "20", "30", "40");

if (isset($_POST['save'])){
	$xfwap = str_replace(" ", "", trim($_POST["xfwap"]));
	$xfgd = str_replace(" ", "", trim($_POST["xfgd"]));
	$xfbs = str_replace(" ", "", trim($_POST["xfbs"]));
	$xfnwb = (isset($_POST["xfnwb"]) ? "true" : "false");
	$sprayno = str_replace(" ", "", trim($_POST["sprayno"]));
	$corruptno = str_replace(" ", "", trim($_POST["corruptno"]));
	$pinno = str_replace(" ", "", trim($_POST["pinno"]));
	if (empty($xfwap)){ $xfwap = "1";}
	if (empty($xfgd)){ $xfgd = "4";}
	if (empty($xfbs)){ $xfbs = "0";}
	if (empty($xfnwb)){ $xfnwb = "false";}
	if (empty($sprayno)){ $sprayno = "1000";}
	if (empty($corruptno)){ $corruptno = "1";}
	if (empty($pinno)){ $pinno = "1000";}
	$config = "#!/bin/bash\n";
	$config .= "XFWAP=\\\"".$xfwap."\\\"\n";
	$config .= "XFGD=\\\"".$xfgd."\\\"\n";
	$config .= "XFBS=\\\"".$xfbs."\\\"\n";
	$config .= "XFNWB=".$xfnwb."\n";
	$config .= "SPRAY_NUM=\\\"".$sprayno."\\\"\n";
	$config .= "CORRUPT_NUM=\\\"".$corruptno."\\\"\n";
	$config .= "PIN_NUM=\\\"".$pinno."\\\"\n";
	exec('echo "'.$config.'" | sudo tee /boot/firmware/PPPwn/pconfig.sh');
	sleep(2);
}


if (isset($_POST['back'])){
	header("Location: index.php");
	exit;
}


if (isset($_POST['back_btn'])){
	header("Location: index.php");
	exit;
}


$cmd = 'sudo cat /boot/firmware/PPPwn/pconfig.sh';
exec($cmd ." 2>&1", $data, $ret);
if ($ret == 0){
foreach ($data as $x) {
   if (str_starts_with($x, 'XFWAP')) {
      $xfwap = (explode("=", str_replace("\"", "", $x))[1]);
   }
   elseif (str_starts_with($x, 'XFGD')) {
      $xfgd = (explode("=", str_replace("\"", "", $x))[1]);
   }
   elseif (str_starts_with($x, 'XFBS')) {
      $xfbs = (explode("=", str_replace("\"", "", $x))[1]);
   }
   elseif (str_starts_with($x, 'XFNWB')) {
      $xfnwb = (explode("=", $x)[1]);
   }
   elseif (str_starts_with($x, 'SPRAY_NUM')) {
      $sprayno = (explode("=", str_replace("\"", "", $x))[1]);
   }
   elseif (str_starts_with($x, 'CORRUPT_NUM')) {
      $corruptno = (explode("=", str_replace("\"", "", $x))[1]);
   }
   elseif (str_starts_with($x, 'PIN_NUM')) {
      $pinno = (explode("=", str_replace("\"", "", $x))[1]);
   }
}
}else{
   $xfwap = "1";
   $xfgd = "4";
   $xfbs = "0";
   $xfnwb = "false";
   $sprayno = "1000";
   $corruptno = "1";
   $pinno = "1000";
}

if (empty($xfwap)){ $xfwap = "1";}
if (empty($xfgd)){ $xfgd = "4";}
if (empty($xfbs)){ $xfbs = "0";}
if (empty($xfnwb)){ $xfnwb = "false";}
if (empty($sprayno)){ $sprayno = "1000";}
if (empty($corruptno)){ $corruptno = "1";}
if (empty($pinno)){ $pinno = "1000";}


$cmd = 'sudo cat /boot/firmware/PPPwn/config.sh';
exec($cmd ." 2>&1", $data, $ret);
if ($ret == 0){
foreach ($data as $x) {
   if (str_starts_with($x, 'CPPMETHOD')) {
      $cppmethod = (explode("=", str_replace("\"", "", $x))[1]);
   }
}
}else{
   $cppmethod = "3";
}
if (empty($cppmethod)){ $cppmethod = "3";}


print("<html> 
<head>
<title>PI-Pwn-Offline (C++ Options)</title>
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
	padding: 1px 3px;
    border-radius: 2px;
	border: 1px solid #6495ED;
}

input[type=text] {
    background: #454545;
	color: #FFFFCC;
	padding: 5px 5px;
    border-radius: 3px;
	border: 1px solid #6495ED;
}

input[type=number] {
    background: #454545;
	color: #FFFFFF;
	padding: 5px 5px;
    border-radius: 3px;
	border: 1px solid #6495ED;
}

a:active,
a:focus {
    outline: 0;
    border: none;
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

label[id=urllbl] {
    padding: 5px 5px;
	font-size:14px; 
	padding:4px; 
	color:6495ED;
}

label[id=urllbl]:hover,
label[id=urllbl]:focus {
    color: #999999;
    text-decoration: none;
    cursor: pointer;
}

input[type=submit] {
    padding:4px;
    color: #6495ED;
    margin-top: 10px;
    background-color: #0E0E14;
    border: none;
	font-size: 16px;
}

input[type=submit]:hover {
    text-decoration: underline;
}
</style>
<script>
var fid;
if (window.history.replaceState) {
   window.history.replaceState(null, null, window.location.href);
}
</script>
</head>
<body>");


print("<br><table align=center><td><form method=\"post\" autocomplete=\"off\">");

if ($cppmethod == "1")
{
print("C++ v 1.0.0 from xfangfang, No need to adjust parameter");
}

if ($cppmethod == "2" || $cppmethod == "3" || $cppmethod == "4")
{
$cval = "";
if ($xfnwb == "true")
{
$cval = "checked";
}
print("<input type=\"checkbox\" name=\"xfnwb\" value=\"".$xfnwb."\" ".$cval.">
<label for=\"xfnwb\">&nbsp;Only wait for one PADI request</label>
<br>
<div style=\"text-align:left; font-size:16px; padding:10px;\">
By default pppwn will wait for two PADI requests. According to<a href=\"https://github.com/TheOfficialFloW/PPPwn/pull/48\" style=\"text-decoration:none;\" target=\"_blank\"><label id=\"urllbl\">TheOfficialFloW/PPPwn/pull/48</label></a>this helps to improve stability.
</div>
");

print("<label for=\"xfwap\">Wait after pin </label><input size=\"2\" type=\"number\" name=\"xfwap\" value=\"".$xfwap."\" style=\"text-align:center;\"><label style=\"text-align:left; font-size:16px; padding:10px;\">(Default: 1)</label><br>
<div style=\"text-align:left; font-size:16px; padding:10px;\">
According to<a href=\"https://github.com/SiSTR0/PPPwn/pull/1\" style=\"text-decoration:none;\" target=\"_blank\"><label id=\"urllbl\">SiSTR0/PPPwn/pull/1</label></a>setting this parameter to 20 helps to improve stability.
</div>
");

print("<label for=\"xfgd\">Groom delay&nbsp;</label><input size=\"4\" type=\"number\" name=\"xfgd\" value=\"".$xfgd."\" style=\"text-align:center;\"><label style=\"text-align:left; font-size:16px; padding:10px;\">(Default: 4)</label><br>
<div style=\"text-align:left; font-size:16px; padding:10px;\">
The Python version of pppwn does not set any wait at Heap grooming. If the C++ version does not add some wait<br>there is a probability of kernel panic. You can set any value within 1-4097 (4097 is equivalent to not doing any wait).
</div>
");

print("<label for=\"xfbs\">Buffer size&nbsp;&nbsp;&nbsp;&nbsp; </label><input size=\"5\" type=\"number\" name=\"xfbs\" value=\"".$xfbs."\" style=\"text-align:center;\"><label style=\"text-align:left; font-size:16px; padding:10px;\">(Default: 0)</label><br>
<div style=\"text-align:left; font-size:16px; padding:10px;\">
When running on low-end devices this value can be set to reduce memory usage. Setting it to 10240 can run normally<br>
and the memory usage is about 3MB. (Note: A value that is too small may cause some packets to not be captured properly)
</div>
");
}


if ($cppmethod == "4")
{
print("<select name=\"sprayno\">");
foreach ($spraynumber as $sn) {
if ($sprayno == $sn)
{
	print("<option value=\"".$sn."\" selected>0x".$sn."</option>");
}else{
	print("<option value=\"".$sn."\"> 0x".$sn." </option>");
}
}
print("</select><label for=\"sprayno\">&nbsp; Spray number</label><label style=\"text-align:right; font-size:16px; padding:10px;\">(Default: 0x1000) in the original exploit.</label><br>
<div style=\"text-align:left; font-size:16px; padding:10px;\">
Brief testing shows that increasing this by steps of 0x50 up to around 0x1500 results in better reliability.
</div>
");

print("<select name=\"corruptno\">");
foreach ($corruptnumber as $cn) {
if ($corruptno == $cn)
{
	print("<option value=\"".$cn."\" selected>0x".$cn."</option>");
}else{
	print("<option value=\"".$cn."\"> 0x".$cn." </option>");
}
}
print("</select><label for=\"corruptno\">&nbsp; Corrupt number</label><label style=\"text-align:right; font-size:16px; padding:10px;\">(Default: 0x1) in the original exploit.</label><br>
<div style=\"text-align:left; font-size:16px; padding:10px;\">
It is the amout of malicious packets sent to the PS4. Breif testing shows increasing this results in much better reliability.<br>
Reccomended values are 0x1 0x2, 0x4, 0x6, 0x8, 0x10, 0x14, 0x20, 0x30, 0x40. Values too high may result in a crash.
</div>
");

print("<label for=\"pinno\"> Pin number : 0x&nbsp;</label><input size=\"4\" type=\"number\" name=\"pinno\" value=\"".$pinno."\" style=\"text-align:center;\"><label style=\"text-align:right; font-size:16px; padding:10px;\">(Default: 0x1000) in the original exploit.</label><br>
<div style=\"text-align:left; font-size:16px; padding:10px;\">
Its purpose is the time to wait on a CPU before proceeding with the exploit.<br>Brief testing has shown this doesn't affect too much, so it's fine to leave this at default.
</div>
");
}
else {
print("<input type=\"hidden\" name=\"sprayno\" value=\"".$sprayno."\">");
print("<input type=\"hidden\" name=\"corruptno\" value=\"".$corruptno."\">");
print("<input type=\"hidden\" name=\"pinno\" value=\"".$pinno."\">");
}


if ($cppmethod == "1")
{
	print("<br><br></td></tr><td align=center><button name=\"back_btn\" value=\"back_btn\">Back to main page</button></td></tr>");
}else{
print("</td></tr><td align=center><button name=\"save\">Save</button></td></tr>
</form>
</td>
</table>
<center><form method=\"post\"><input type=\"hidden\" value=\"back\"><input type=\"submit\" name=\"back\" value=\"Back to main page\"/></form></center>


</body>
</html>");
}
?>