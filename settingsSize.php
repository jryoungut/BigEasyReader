<?php
include ("dbConfig.php");
include ("dbOpen.php");
include ("settingsUtilities.php");

//################################################################
//variables
if(isset($_SESSION['brUserID'])){
	$user_check = $_SESSION['brUserID'];
}

//################################################################
//Get Setting for user
$settingsArray = GetSettingsValues($conn);
extract($settingsArray);


include("dbClose.php");
?>
<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Size Settings - Big EZ Reader</title>
    
	<?php include("_jqueryPartial.php"); ?>
	
	<script type="text/javascript">
		var IsLoggedIn =<?php echo($user_check) ?>;
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

	
		$(document).on('pageinit', '#pgSize', function(){   
			SetThemeColors();
			
		    $('#sizerCtrl').on('slidestop', function(event, ui){
		    	Size = $(this).val();
		    	SaveSize();
		    	
		    	var bw = $(window).width() * parseInt(Size)/100;

				CustomOptions['redicleWidth'] = bw;
				CustomOptions['redicleHeight'] = bw * SpritzHelper.verticalScaleFactor;

				var redicleLineWidth = SpritzHelper.GetLineWidth(Size);
				CustomOptions.redicle['redicleLineWidth'] = redicleLineWidth;

				
				$('#spritzer').empty();
	            initSpritz($('#spritzer'), CustomOptions);
	            
	            centerSpritzerControl($('#spritzer'), bw*SpritzHelper.verticalScaleFactor, bw);
		    });

			$('#sizerCtrl').val(parseInt(Size)).slider('refresh');

			// Set redicle line width
			var redicleLineWidth = SpritzHelper.GetLineWidth(Size);
			CustomOptions.redicle['redicleLineWidth'] = redicleLineWidth;
			
			// Set redicle colors
			var backgroundColor = '#000011';
			var textNormalPaintColor = '#eeeeff';
			var textHighlightPaintColor = '#FAFA00';
			var redicleLineColor = '#aaa';
			switch(ColorThemeID){
				case 1:
					backgroundColor = '#000011';
					textNormalPaintColor = '#eeeeff';
					textHighlightPaintColor = '#FAFA00';
					redicleLineColor = '#aaa';
					break;
				case 2:
					backgroundColor = '#EEEEFF';
					textNormalPaintColor = '#000011';
					textHighlightPaintColor = '#FA0000';
					redicleLineColor = '#888';
					break;
			}
			CustomOptions.redicle['backgroundColor'] = backgroundColor;
			CustomOptions.redicle['textNormalPaintColor'] = textNormalPaintColor;
			CustomOptions.redicle['textHighlightPaintColor'] = textHighlightPaintColor;
			CustomOptions.redicle['redicleLineColor'] = redicleLineColor;

            initSpritz($('#spritzer'), CustomOptions);
 
            setTimeout(function(){
            	centerSpritzerControl($('#spritzer'), bw*SpritzHelper.verticalScaleFactor, bw);
            }, 10);
			//centerSpritzerControl($('#spritzer'), bw*SpritzHelper.verticalScaleFactor, bw)
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
          var contentUrl = 'http://www.archives.gov/exhibits/charters/print_friendly.html?page=declaration_transcript_content.html&title=NARA%20%7C%20The%20Declaration%20of%20Independence%3A%20A%20Transcription';
          var spritzer = ctrl;
          var spritzerId = $(spritzer).prop('id');
          
          spritzer.data('url', contentUrl);
          spritzerController = new SPRITZ.spritzinc.SpritzerController(customOps);
          spritzerController.spritzClient = SpritzClient;
          spritzerController.attach(spritzer);
            
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
          $('#spritzer').data('controller').startSpritzing(spritzText);
        }
        
        function onFetchError(error) {
          alert('Spritzing failed: ' + error.message);
        }

        
        function centerSpritzerControl(ctrl, h, w){
        	var cW = w;
        	var cH = h;
        	var win = $(window);
        	var winWidth = win.width();
        	var winHeight = $('#pgSize').height() - $('#sizerCtrl').position().top - $('#sizerCtrl').height() - 30;
        	
        	ctrl.css('margin-top', ((winHeight/2) - cH/2) + 'px');
        	ctrl.css('margin-left', ((winWidth/2) - cW/2) + 'px');
        	
        }
        
        function setupSpritzerDefaultUI(){
        	$('.spritzer-container').css('background-color', 'transparent');
        	$('.spritzer-container').css('border', 'none');
        }

		function SaveSize(){
	    	var dataString = {
	    		"settingID" : "2",
	    		"settingValue" : String(Size)
	    	};
	    	
	        $.ajax({  
	            type: "POST",  
	            url: "settingsFunctions.php/SaveSettings",  
	            data:  "settingID=" + dataString.settingID + "&" + "settingValue=" + dataString.settingValue,
	            success: function(data){  
	                //alert(data);  
	            }  
	        });  
		}
	</script>
</head>
<body>
<!-- ###################################################################################### -->
<!-- page -->
    <div data-role="page" id="pgSize">
        <div data-role="header">
        	<?php
			if($user_check > 0)
			{
				echo("<a id='btnBack' href='settingsMain.php' data-role='button' data-mini='true' data-ajax='false'>Settings</a>");
			}
        	?>
        	<h3>Size</h3>
       </div><!-- /header -->

        <div data-role="content">
        	<div id="sizerHolder">
        		<input type="range" name="sizerCtrl" id="sizerCtrl" data-highlight="true" min="20" max="100" step="10" value="40">
        	</div>
        	<div class="clear"></div>
            <div id="spritzer" style="position: absolute;"></div>

        </div><!-- /content -->
    </div><!-- /page -->
</body>
</html>
