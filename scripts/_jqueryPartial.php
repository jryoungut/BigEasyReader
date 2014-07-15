<?php
?>
	<script type="text/javascript">
        var host = window.location.hostname;
        var http = location.protocol;
        var slashes = http.concat("//");
        var locandfile = '/bigeasyreader/login_success.html';
        if(host === 'localhost'){
        	host = host + ':81';
        }
        var SpritzSettings = {
            clientId : "cc7314548848c6af2",
            //redirectUri : slashes + host + locandfile
            redirectUri :  host + locandfile
        };
	</script>
	
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.0/jquery.mobile-1.4.0.min.css" />
	
    <script type="text/javascript" src="//sdk.spritzinc.com/jQuery/jquery-2.1.0.min.js"></script>
    <script type="text/javascript" src="//sdk.spritzinc.com/js/1.2/js/spritz.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/mobile/1.4.0/jquery.mobile-1.4.0.min.js"></script>
    <script type="text/javascript" src="scripts/modernizr-2.0.6.js"></script>

    <script type="text/javascript" src="scripts/spritzHelper.js"></script>
        
    <link rel="stylesheet" type="text/css" href="css/ezBigRead.css">
