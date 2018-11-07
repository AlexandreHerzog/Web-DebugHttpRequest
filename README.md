# Web-DebugHttpRequest
Small PHP page echo-ing back the request it received. Useful to check what kind of information leaks within HTTP headers or troubleshoot issues (typically reverse proxy request rewrites).

## Prerequisites
Web server with PHP.

## Getting started
1. Upload patch.php on your web server

2. Apply some basic hardening to your web server, especially if you plan to expose it to Internet:

   Example for an Apache server to suppress version details (e.g. in file apache2.conf or security.conf):
   
   ``
   ServerTokens Prod
   ServerSignature Off
   ``
   
3. Visit the page from a browser & submit requests, altering the HTTP verb and the submitted payload as desired

4. If you get nothing / the submission isn't accepted by the web server (message ```No session or no content submitted previously```), turn on developer tools ([F12]) and watch for 400 or 500 errors. To help debug such errors, where the web server refuses the request, consider

    a. Using a network sniffer on your web server (e.g. tcpdump or Wireshark)
  
    b. Increase the log verbosity on the web server
    
      Example for an Apache server to log all request details (typically in file apache2.conf)
    
		   ``LogLevel trace8`` (instead of LogLevel warn)
    
    
