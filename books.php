<?php
?>
<HTML>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Books</title>
    
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
</script>
</head>
<body>
    <div data-role="page">
        <div data-role="header" id="hdrBlue2">
        	<a id="btnHome" href="index.php" data-role="button" >Back</a>
	        <h3>Books</h3>
        </div><!-- /header -->
        <div data-role="content" data-inset="true">
        <ul data-role="listview" data-filter="true" data-inset="true">
			<li><a href="" data-bookid="1">Poetry of a Soldier</a></li>
			<li><a href="" data-bookid="2">The Art of Inventing</a></li>
		</ul>
	</div>
</body>
</html>