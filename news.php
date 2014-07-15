<?php
?>
<HTML>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>News</title>
    
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.0/jquery.mobile-1.4.0.min.css" />
	
    <script type="text/javascript" src="//sdk.spritzinc.com/jQuery/jquery-2.1.0.min.js"></script>
    <script type="text/javascript" src="//sdk.spritzinc.com/js/1.2/js/spritz.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/mobile/1.4.0/jquery.mobile-1.4.0.min.js"></script>
    <script type="text/javascript" src="scripts/modernizr-2.0.6.js"></script>

    <link rel="stylesheet" type="text/css" href="css/ezBigRead.css">

    <script type="text/javascript">
		$(document).on('pageinit', function() {
			//alert("hi");
		});
		$(document).ready(function() {
            $('#btnHome').on('click', function(){
              window.location = 'index.php';
            });
            $('ul li a').on('click', function(){
                window.location = 'bigread.php?bID=' + $(this).data('bookid');
            });
		});
		
  function showRSS(str) {
        if (str.length==0) {
	        $('#newsStories').html('');
	        return;
        }
        
        if (window.XMLHttpRequest) {
	        // code for IE7+, Firefox, Chrome, Opera, Safari
	        xmlhttp=new XMLHttpRequest();
        }
        else {  // code for IE6, IE5
	        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        
        xmlhttp.onreadystatechange=function() {
	        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		        $('#newsStories').html(xmlhttp.responseText);
		        $('#newsStories').listview('refresh');
	        }
        }
        
        xmlhttp.open("GET","getrss.php?q="+str,true);
        xmlhttp.send();
    }
</script>
</head>
<body>
    <div data-role="page">
        <div data-role="header" id="hdrBlue2">
        	<a id="btnHome" href="index.php" data-role="button" >Back</a>
	        <h3>News</h3>
        </div><!-- /header -->
        
        <div data-role="content" data-inset="true">
	        <select data-native-menu="false" onchange="showRSS(this.value)">
				<option value="-1" data-placeholder="true">Choose one...</option>
	        	<option value="CNN">CNN News</option>
	        	<option value="Google">Google News</option>
	        </select>
	        <div style="height:20px;"></div>
	        <ul id="newsStories" data-role="listview" data-inset="true">
			</ul>
		</div>
	</div>
</body>
</html>