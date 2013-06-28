<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Traceroute</title>
<link href="main.css" rel="stylesheet" type="text/css">
</head>

<body onLoad="document.inputForm.inputSite.focus();">

<div class="container">
  <div class="header">
    <div class="header_1" ><a style="color:#C6C6C6" href="index.php">traceroute.kersj.es</a></div>
    <div class="header_2"></div>
    <div class="header_3"><h1>Online Traceroute</h1></div>
    <!-- end .header --></div>
  <div class="input">
  	<h3>- Input -</h3>
    <form action="index.php" method="post" name="inputForm" target="_self">
    	<div style="float:left">
        	&nbsp;&nbsp;Serveradresse:<br>
            &nbsp;<input name="inputSite" type="text" size="30" maxlength="300">
        </div>
        <div style="float:left">
        	&nbsp;&nbsp;Typ:<br>
            &nbsp;<select name="inputType">
            <option value="ping">Ping</option>
            <option value="traceroute">Traceroute</option>
            <option value="nmap">nmap</option>
            </select>
        </div>
        <div style="float:left">
        	&nbsp;&nbsp;Hops:<br>
            &nbsp;<select name="inputHops">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
            <option value="25">25</option>
            <option selected value="30">30</option>
            <option value="40">40</option>
            <option value="50">50</option>
            <option value="60">60</option>
            </select>
        </div>
        <div style="float:left">
            <br>&nbsp;&nbsp;<input name="submit" type="submit" value="Senden">
        </div>
        <div style="clear:both">
        	<br>(Bitte haben Sie Geduld. Die Abfragen können je nach Erreichbarkeit der Server relativ lange dauern!)
        </div>
    </form>
  </div>
  <div class="content">
<?php
if(isset($_POST['inputSite'])){
	if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");
	
	// log in at server1.example.com on port 22
	if (!($con = ssh2_connect("xx.xx.xx.xx", 22))) {
		echo "fail: unable to establish connection\n";
	} else {

		// authenticate with username and pass
		if (!ssh2_auth_password($con, "xxxxx", "xxxxx")) {
			echo "fail: unable to authenticate\n";
		} else {
	
		   // create a shell
			if (!($shell = ssh2_shell($con, 'vt102', null, 80, 40, SSH2_TERM_UNIT_CHARS))) {
				echo "fail: unable to establish shell\n";
			} else {
	
			$inputType = $_POST['inputType'];
			$inputSite = $_POST['inputSite'];
			$inputHops = $_POST['inputHops'];
			
			if($inputType == "traceroute"){
				$stream = ssh2_exec($con,''.$inputType.' -m '.$inputHops.' '.$inputSite.';');	
			}
			if($inputType == "ping"){
				$stream = ssh2_exec($con,''.$inputType.' -c '.$inputHops.' '.$inputSite.';');	
			}
			if($inputType == "nmap"){
				$stream = ssh2_exec($con,''.$inputType.' '.$inputSite.';');	
			}
			
			// collect returning data from command
				stream_set_blocking($stream, true);

				$data = "";
				while ($buf = fread($stream,4096)) {

					$data .= nl2br($buf);

					sleep(1); 
				}

				print($data);

				fclose($stream);
				

				}
			}
		}
}
?>
    <!-- end .content --></div>
  <div class="footer">
    <p><a style="text-decoration:none;color:#C6C6C6" href="http://kersjesblog.de" >&copy;Christian Kersjes</a></p>
    <!-- end .footer --></div>
  <!-- end .container --></div>
</body>
</html>
