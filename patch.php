<?php
	// TODO - REMOVE THIS!
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);

	if ($_SERVER['REQUEST_METHOD'] == "GET") {
		session_start();
		if (isset($_GET['showResults'])) {
			// Should avoid XSS
			header("Content-Type: text/plain");
			if (isset($_SESSION["LAST_REQUEST"])) {
				echo $_SESSION["LAST_REQUEST"];
			}
			else {
				echo "No session or no content submitted previously";
			}
		}
		else {
echo <<<EOF
<html>
	<head>
		<title>My little PATCH experiment</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<style>
			label {
				display: block;
				margin-top: 1em;
			}
			iframe {
				display: block;
				width: 600px;
				height: 20em;
			}
			textarea {
				width: 600px;
				height: 5em;
			}
		</style>
	</head>
	<body>
		<h1>Welcome to my litte PATCH experiment</h1>
		
		<label for="method">Enter the HTTP verb you want to test (exception GET):</label>
		<input type="text" id="method" value="PATCH"/>
		
		<label for="payload">Enter the HTTP request data you want to send:</label>
		<textarea id="payload">some=data&in=whatever&format</textarea>
		
		<label for="submit">Send the test HTTP request</label>
		<input id="submit" type="submit" value="Submit the request" onClick="javascript:sendAndDisplay();">
		
		<script>
			function sendAndDisplay() {
				$.ajax({
						url: "patch.php",
						method: $("#method").val(),
						data: $("#payload").val(),
					}
				).done( function() {
					//alert("submitted!");
					$("#result")[0].src = "patch.php?showResults=true";
				});
			}
		</script>
		
		<label for="result">Raw HTTP request received on the server (HTTP headers might not be in order)</label>
		<iframe id="result"/>
	</body>
</html>
EOF;
		}
	}
	else {
		// Store the raw test request in the session variable
		session_start();
		$_SESSION["LAST_REQUEST"] = "Request received on " . date("r") . "\r\n";
		
		$_SESSION["LAST_REQUEST"] .= "\r\n" . $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI'] . " " . $_SERVER['SERVER_PROTOCOL'] . "\r\n";
		// &%/%(ç PHP - see https://stackoverflow.com/questions/13224615/get-the-http-headers-from-current-request-in-php
		//foreach (getallheaders() as $key => $value) {
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_' || substr($name, 0, 8) == 'CONTENT_') {
				$_SESSION["LAST_REQUEST"] .= "$name: $value\r\n";
			}
			//}
		}
		
		$_SESSION["LAST_REQUEST"] .= "\r\n\r\nData sent within request:\r\n";
		$_SESSION["LAST_REQUEST"] .= file_get_contents("php://input");
	}

?>
