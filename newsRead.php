<?php
include ("dbConfig.php");
include ("dbOpen.php");
include ("settingsUtilities.php");

// Get POST values
$newsLink = $_SERVER['REQUEST_URI'];
$newsLinkArray = explode("?nl=", $newsLink);
$newsLink = $newsLinkArray[1];

// Variables
$title = "";
$location = "";


//################################################################
//Get Setting for user
$settingsArray = GetSettingsValues($conn);
extract($settingsArray);


include("dbClose.php");
?>
<HTML>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>News Read</title>
	
	<?php include("_jqueryPartial.php"); ?>

    <script type="text/javascript">

		var ColorTheme = '<?php echo($settingColorValue) ?>';
		var ColorThemeID = <?php echo($settingColor) ?>;
		var Size = '<?php echo($settingSize) ?>';
		var Speed = '<?php echo($settingSpeed) ?>';
		
	    var bw = $(window).width() * parseInt(Size)/100;
	    var CustomOptions = {
	        'debugLevel': 	0,
			'redicleWidth': 	bw,
			'redicleHeight': 	bw * SpritzHelper.verticalScaleFactor,
			'defaultSpeed': 	Speed,
			'speedItems': 	[250, 300, 350, 400, 450, 500, 550, 600],
			'controlButtons': [],
			'header': { //show above the redicle
				'close': false, //close "x" button
				'closeHandler': '', //optional callback the dev can set, otherwise use our default
			},
			'controlTitles' : {},
			'placeholderText': { //full sentence text that displays before/after a spritz
				'startText': "Click to spritz.",
				'startTextColor': "#bababa",
				'endText': "",
				'endTextColor': ""
			},
			//"advanced" redicle options
			redicle: {
				'lineStrokeWidth': .125,
				'backgroundColor': "#000011",
				'textNormalPaintColor': "#eeeeff",
				'textHighlightPaintColor': "#FAFA00", //red ORP
				'redicleLineColor': "#aaa",
				'redicleLineWidth': 20,
				'countdownTime': 750,
				'countdownColor': "#e8e8e8",
				'countdownSlice': 5	// 5 milliseconds
			}
	    };

	
		$(document).on('pageinit', '#pgBigRead', function(){ 
			SetThemeColors();
			
		    $('#speedCtrl').on('slidestop', function(event, ui){
		    	Speed = $(this).val();
		    	$('#spritzer').data('controller').setSpeed(parseInt(Speed));
		    });
  
			$('#speedCtrl').val(parseInt(Speed)).slider('refresh');
            $("#startSpritz").on("click", SpritzHelper.onStartSpritzClick);

            SpritzHelper.bookLocation = '<?php echo($location);?>';

			// Set redicle line width
			var redicleLineWidth = SpritzHelper.GetLineWidth(Size);
			CustomOptions.redicle['redicleLineWidth'] = redicleLineWidth;
			
			// Set redicle colors
			var backgroundColor = '#000011';
			var countdownColor = '#888';
			var textNormalPaintColor = '#eeeeff';
			var textHighlightPaintColor = '#FAFA00';
			var redicleLineColor = '#aaa';
			switch(ColorThemeID){
				case 1:
					backgroundColor = '#000011';
					countdownColor = '#888';
					textNormalPaintColor = '#eeeeff';
					textHighlightPaintColor = '#FAFA00';
					redicleLineColor = '#aaa';
					break;
				case 2:
					backgroundColor = '#EEEEFF';
					countdownColor = '#CCC';
					textNormalPaintColor = '#000011';
					textHighlightPaintColor = '#FA0000';
					redicleLineColor = '#888';
					break;
			}
			CustomOptions.redicle['backgroundColor'] = backgroundColor;
			CustomOptions.redicle['countdownColor'] = countdownColor;
			CustomOptions.redicle['textNormalPaintColor'] = textNormalPaintColor;
			CustomOptions.redicle['textHighlightPaintColor'] = textHighlightPaintColor;
			CustomOptions.redicle['redicleLineColor'] = redicleLineColor;

            initSpritz($('#spritzer'), CustomOptions);
 
 			$('#spritzer').on("onSpritzBack", function(event, position, pausePos) {console.log("onSpritzBack: " + position + "/" + pausePos);});
 
            setTimeout(function(){
            	centerSpritzerControl($('#spritzer'), bw*SpritzHelper.verticalScaleFactor, bw);
            }, 10);
		});
		
		function SetThemeColors(){
        	if(ColorTheme === 'Dark Theme'){
        		$('#pgSize').removeClass('themeLight');
				$('#pgSize').addClass('themeDark');
				$('h3').removeClass('themeLight');
				$('h3').addClass('themeDark');
				$('a').removeClass('themeLight');
				$('a').addClass('themeDark');
				$('#spnDark').removeClass('themeLight');
				$('#spnDark').addClass('themeDark');
				$('#spnLight').removeClass('themeLight');
				$('#spnLight').addClass('themeDark');
				$('#spnDark').html('&#10152;');
				$('#spnLight').html('');
				$('.spritzer-container').removeClass('themeLight');
				$('.spritzer-container').addClass('themeDark');
 				$('.ui-header').removeClass('themeLight');
				$('.ui-header').addClass('themeDark');
	       	}
        	else{
				$('#pgSize').removeClass('themeDark');
				$('#pgSize').addClass('themeLight');
				$('h3').removeClass('themeDark');
				$('h3').addClass('themeLight');
				$('a').removeClass('themeDark');
				$('a').addClass('themeLight');
				$('#spnDark').removeClass('themeDark');
				$('#spnDark').addClass('themeLight');
				$('#spnLight').removeClass('themeDark');
				$('#spnLight').addClass('themeLight');
				$('#spnDark').html('');
				$('#spnLight').html('&#10152;');
				$('.spritzer-container').removeClass('themeDark');
				$('.spritzer-container').addClass('themeLight');
 				$('.ui-header').removeClass('themeDark');
				$('.ui-header').addClass('themeLight');
	       	}
		}
		
        function initSpritz(ctrl, customOps) {
          //var contentUrl = 'http://www.zaycounlimited.com/bigread/Books/PoetryOfASoldier.txt';
          var contentUrl = '<?php echo $newsLink; ?>';
          var spritzer = ctrl;
          var spritzerId = $(spritzer).prop('id');
          
          spritzer.data('url', contentUrl);
          SpritzHelper.spritzController = new SPRITZ.spritzinc.SpritzerController(customOps);
          SpritzHelper.spritzController.spritzClient = SpritzClient;
          SpritzHelper.spritzController.attach(spritzer);
          
  		  // Supply custom progress reporter
		  SpritzHelper.spritzController.setProgressReporter(showProgress);

          // Is there a user logged in?
            if (SpritzClient.isUserLoggedIn()) {
              // Yes, so we'll kick off the content retrieval process, and start the Spritz
              // if and when retrieval completes successfully.
                SpritzClient.fetchContents(contentUrl, onFetchSuccess, onFetchError);
            } else {
              // No, so we'll let the user initiate the Spritz. 
            }
        }

        function onFetchSuccess(spritzText) {
          $('#spritzer').data('controller').setSpritzText(spritzText);
        }
        
        function onFetchError(error) {
          alert('Spritzing failed: ' + error.message);
        }

        function centerSpritzerControl(ctrl, h, w){
        	var cW = w;
        	var cH = h;
        	var win = $(window);
        	var winWidth = win.width();
        	var winHeight = $('#pgBigRead').height() - 30;
        	
        	ctrl.css('margin-top', ((winHeight/4) - cH/2) + 'px');
        	ctrl.css('margin-left', ((winWidth/2) - cW/2) + 'px');
        	
        }

	function showProgress(completed, total) {
		$("#wordNumber").text(completed);
		$("#wordTotal").text(total);
	}

    </script>

</head>
<body>
    <div data-role="page" id="pgBigRead">
        <div data-role="header">
        	<a id="btnHome" href="news.php" data-role="button"  data-mini="true" >Back</a>
	        <h3>News</h3>
        	<a id="btnSpritzInc" href="http://www.spritzinc.com" data-role="button" class="spritzBtnHolder hide"><div class="spritzBtn"></div></a>
        </div><!-- /header -->
        <div data-role="content">
        
            <div id="spritzer"></div>
        
            <div class="spacer-b">
                <img src='images/Play.png' id="startSpritz" class='ctrlBtn' />
                <img src='images/Pause.png' id="togglepauseSpritz" class='ctrlBtn'  />
                <img src='images/Beginning.png' id="btnReset" class='ctrlBtn ctrlBtnSpacerLeft'  />
                <img src='images/Back-Sentence.png' id="btnBackSentence" class='ctrlBtn ctrlBtnSpacerLeft'  />
                <img src='images/Back-Word.png' id="btnBackWord" class='ctrlBtn'  />
                <img src='images/Forward-Word.png' id="btnNextWord" class='ctrlBtn'  />
                <img src='images/Forward-Sentence.png' id="btnNextSentence" class='ctrlBtn'  />
                <!--<button class="btn btn-default rprBtn" id="startSpritz" type="submit" data-inline="true">Start</button>-->
                <!--<button class="btn btn-default rprBtn hide" id="togglepauseSpritz" type="submit" data-inline="true" data-spritzstate="Reading">Pause</button>-->
        	    <!--<button class="btn btn-default " id="btnReset" type="submit" data-inline="true"><span class="bold">|&laquo;</span></button> 	
        	    <button class="btn btn-default " id="btnBack" type="submit" data-inline="true"><span class="bold">&larr;</span></button> 	
        	    <button class="btn btn-default " id="btnForward" type="submit" data-inline="true"><span class="bold">&rarr;</span></button> 	
        	    <button class="btn btn-default hide" id="slower" type="submit" data-inline="true"><span class="bold">&ndash;</span></button><label for="slower" accesskey="s" style="display: inline-block;"></label> 	
                <div id="spnSpeed" class="btn hide" style='display: inline-block;'></div>	
        	    <button class="btn btn-default hide" id="faster" type="submit" data-inline="true"><span class="bold">+</span></button><label for="faster" accesskey="w" style="display: inline-block;"></label> 	-->
            </div>
            <div class="hide" style="color:#fff;"><?php echo $newsLink; ?></div>
            <div id="speedHolder" class=''>
                <input type="range" name="speedCtrl" id="speedCtrl" data-highlight="true" min="1" max="1000" value="150">
            </div>
        </div>
    </div>
</body>
</html>