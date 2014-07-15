<?php
?>
<HTML>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Web Link Read</title>
	<?php include("_jqueryPartial.php"); ?>
    <script type="text/javascript" src="//sdk.spritzinc.com/jQuery/jquery-2.1.0.min.js"></script>
    <script type="text/javascript" src="//sdk.spritzinc.com/js/1.0/js/spritz.min.js"></script>
    <script type="text/javascript" src="scripts/spritzHelper.js"></script>

    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/my.css">

    <script type="text/javascript">
        var init = function () {
            $('#btnHome').on('click', function(){
              window.location = 'index.php';
            });

            $("#startSpritz").on("click", SpritzHelper.onWebLinkStartSpritzClick);
        };

        $(document).ready(function () {
            init();
        });
    </script>

</head>
<body>
    <div data-role="page">
        <div data-role="header" id="hdrOrange">
        	<a id="btnHome" href="" data-role="button">Back</a>
	        <h3>Web Link Read</h3>
        	<a id="btnSpritzInc" href="http://www.spritzinc.com" data-role="button" class="spritzBtnHolder"><div class="spritzBtn"></div></a>
        </div><!-- /header -->
        <div data-role="content" data-inset="true" class="main-body">
        
            <div id="spritzer" data-url=""></div>
        
            <div class="spacer-b">
                <button class="btn btn-default rprBtn" id="startSpritz" type="submit" data-inline="true">Start</button>
                <button class="btn btn-default rprBtn hide" id="togglepauseSpritz" type="submit" data-inline="true" data-spritzstate="Reading">Pause</button>
        	    <button class="btn btn-default hide" id="btnReset" type="submit" data-inline="true"><span class="bold">|&laquo;</span></button> 	
        	    <button class="btn btn-default hide" id="btnBack" type="submit" data-inline="true"><span class="bold">&larr;</span></button> 	
        	    <button class="btn btn-default hide" id="btnForward" type="submit" data-inline="true"><span class="bold">&rarr;</span></button> 	
        	    <button class="btn btn-default" id="slower" type="submit" data-inline="true"><span class="bold">&ndash;</span></button><label for="slower" accesskey="s" style="display: inline-block;"></label> 	
                <div id="spnSpeed" class="btn" data-inline="true"></div>	
        	    <button class="btn btn-default" id="faster" type="submit" data-inline="true"><span class="bold">+</span></button><label for="faster" accesskey="w" style="display: inline-block;"></label> 	
            </div>
            <div>
                <p class="fieldInstructions">Enter a web URL below and press Start.</p>
                <textarea id="inputText" rows="1" class="webLinkInput"></textarea>
        	    <button class="btn btn-default" id="clear" type="submit" data-inline="true">Clear</button> 	
            </div>
        </div>
    </div>
</body>
</html>