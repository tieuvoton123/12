<?php 

if (isset($_POST['payload'])){
$fso = fsockopen("tcp://192.168.2.2", 9090, $errn, $errs, 30);
if ($fso){
$file = fopen(urldecode($_POST['payload']), "rb");
while (!feof($file)) 
{
   fwrite($fso, fgets($file));
}
fclose($fso);
fclose($file);
}
}
 
if (isset($_POST['reload'])){
	header("Location: payloads.php");
	exit;
}

if (isset($_POST['back'])){
	header("Location: index.php");
	exit;
}

print("<html> 
<head>
<title>PI-Pwn-Offline (Payloads for GoldHEN)</title>
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
	color: #B6B6B6;
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
	font-size: 16px;
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
if (window.history.replaceState) {
   window.history.replaceState(null, null, window.location.href);
}
</script>
</head>
<body>");

print("<br><table align=center><td><form method=\"post\">");


$haspl=0;

$cmd = 'sudo ls /boot/firmware/PPPwn/payloads';
exec($cmd ." 2>&1", $sdir, $ret);
$cnt=0;
if ($ret == 0 && count($sdir) > 0)
{
	foreach ($sdir as $a) {
		if ($cnt == 0) { print("<center>"); }
		if (str_ends_with($a, ".bin") || str_ends_with($a, ".elf"))
		{
			$haspl=1;
			print("<button name=\"payload\" value=".urlencode('/boot/firmware/PPPwn/payloads/'.$a).">".$a."</button>&nbsp; ");
			$cnt++;
			if ($cnt >= 4)
			{
				print("</center><br>");
				$cnt=0;
			}
		}
	}
if ($haspl > 0){goto done;}
}

print("<button name=\"reload\" value=\"reload\">Reload page</button>");
done:
print("</form></td></table><center>Place your payloads in a folder called \"<b>payloads</b>\" on SDCARD/firmware/PPPwn/payloads/.<br>You must also enable the binloader server in goldhen.<br>If your firmware not support please use Playload Guest instead.<br><form method=\"post\"><input type=\"hidden\" value=\"back\"><input type=\"submit\" name=\"back\" value=\"Back to main page\"/></form></center>");
print("</body></html>");

?>