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
    <title>Fixation Settings - Big EZ Reader</title>
    
	<?php include("_jqueryPartial.php"); ?>
	
	<script type="text/javascript">
		var IsLoggedIn =<?php echo($user_check) ?>;
		var ColorTheme = '<?php echo($settingColorValue) ?>';
		var ColorThemeID = <?php echo($settingColor) ?>;
		var Size = '<?php echo($settingSize) ?>';
		var Speed = '<?php echo($settingSpeed) ?>';
		var Fixation = '<?php echo($settingFixation) ?>';
		
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

	
		$(document).on('pageinit', '#pgFixation', function(){ 
			// $('#btnReset').on('click', function(){
				// Reset();
			// });
			SetThemeColors();
			
			initCanvas();  
			
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
        	var winHeight = $('#pgFixation').height() - 30;
        	
        	ctrl.css('margin-top', ((winHeight/2) - cH/2) + 'px');
        	ctrl.css('margin-left', ((winWidth/2) - cW/2) + 'px');
        	
        }
        
        function setupSpritzerDefaultUI(){
        	$('.spritzer-container').css('background-color', 'transparent');
        	$('.spritzer-container').css('border', 'none');
        }

		function Reset(){
			Fixation = '0,0';
			SaveFixation();
			initCanvas();
		}
		
		function SaveFixation(){
	    	var dataString = {
	    		"settingID" : "4",
	    		"settingValue" : Fixation
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
		
        function initCanvas(){
			window.addEventListener("load", windowLoadHandler, false);
			function windowLoadHandler() {
				canvasApp();
			}
			
			function canvasSupport() {
				return Modernizr.canvas;
			}
			
			function canvasApp() {
				if (!canvasSupport()) {
					return;
				}
				
				var theCanvas = document.getElementById("canvasOne");
				$(theCanvas).css('width', $('#canvasHolder').width());
				$(theCanvas).css('height', $('#canvasHolder').height());
				var context = theCanvas.getContext("2d");
				
				init();
				
				var shapes;
				var numShapes;
				var dragIndex;
				var dragging;
				var mouseX;
				var mouseY;
				var dragHoldX;
				var dragHoldY;
				
				function init() {
					numShapes = 1;
					shapes = [];
					
					makeShapes();
					
					drawScreen();
					
					theCanvas.addEventListener("mousedown", mouseDownListener, false);
				}
				
				function makeShapes() {
					var i;
					var tempX = Fixation.split(',')[0];
					var tempY = Fixation.split(',')[1];
					var tempRad = 10;
					var tempR = 255;
					var tempG = 255;
					var tempB = 255;
					var tempColor = "rgb(" + tempR + "," + tempG + "," + tempB +")";
					tempShape = {x:tempX, y:tempY, rad:tempRad, color:tempColor};
					shapes.push(tempShape);
				}
				
				function mouseDownListener(evt) {
					var i;
					//We are going to pay attention to the layering order of the objects so that if a mouse down occurs over more than object,
					//only the topmost one will be dragged.
					var highestIndex = -1;
					
					//getting mouse position correctly, being mindful of resizing that may have occured in the browser:
					var bRect = theCanvas.getBoundingClientRect();
					mouseX = (evt.clientX - bRect.left)*(theCanvas.width/bRect.width);
					mouseY = (evt.clientY - bRect.top)*(theCanvas.height/bRect.height);
							
					//find which shape was clicked
					for (i=0; i < numShapes; i++) {
						if	(hitTest(shapes[i], mouseX, mouseY)) {
							dragging = true;
							if (i > highestIndex) {
								//We will pay attention to the point on the object where the mouse is "holding" the object:
								dragHoldX = mouseX - shapes[i].x;
								dragHoldY = mouseY - shapes[i].y;
								highestIndex = i;
								dragIndex = i;
							}
						}
					}
					
					if (dragging) {
						window.addEventListener("mousemove", mouseMoveListener, false);
					}
					theCanvas.removeEventListener("mousedown", mouseDownListener, false);
					window.addEventListener("mouseup", mouseUpListener, false);
					
					//code below prevents the mouse down from having an effect on the main browser window:
					if (evt.preventDefault) {
						evt.preventDefault();
					} //standard
					else if (evt.returnValue) {
						evt.returnValue = false;
					} //older IE
					return false;
				}
				
				function mouseUpListener(evt) {
					theCanvas.addEventListener("mousedown", mouseDownListener, false);
					window.removeEventListener("mouseup", mouseUpListener, false);
					if (dragging) {
						dragging = false;
						window.removeEventListener("mousemove", mouseMoveListener, false);
						Fixation = parseInt(shapes[0].x) + ',' + parseInt(shapes[0].y);
						SaveFixation();
					}
				}
			
				function mouseMoveListener(evt) {
					var posX;
					var posY;
					var shapeRad = shapes[dragIndex].rad;
					var minX = shapeRad;
					var maxX = theCanvas.width - shapeRad;
					var minY = shapeRad;
					var maxY = theCanvas.height - shapeRad;
					//getting mouse position correctly 
					var bRect = theCanvas.getBoundingClientRect();
					mouseX = (evt.clientX - bRect.left)*(theCanvas.width/bRect.width);
					mouseY = (evt.clientY - bRect.top)*(theCanvas.height/bRect.height);
					
					//clamp x and y positions to prevent object from dragging outside of canvas
					posX = mouseX - dragHoldX;
					posX = (posX < minX) ? minX : ((posX > maxX) ? maxX : posX);
					posY = mouseY - dragHoldY;
					posY = (posY < minY) ? minY : ((posY > maxY) ? maxY : posY);
					
					shapes[dragIndex].x = posX;
					shapes[dragIndex].y = posY;
					
					drawScreen();
				}
				
				function hitTest(shape,mx,my) {
					
					var dx;
					var dy;
					dx = mx - shape.x;
					dy = my - shape.y;
					
					//a "hit" will be registered if the distance away from the center is less than the radius of the circular object		
					return (dx*dx + dy*dy < shape.rad*shape.rad);
				}
				
				function drawShapes() {
					context.fillStyle = shapes[0].color;
					context.beginPath();
					context.arc(shapes[0].x, shapes[0].y, shapes[0].rad, 0, 2*Math.PI, false);
					context.closePath();
					context.fill();
				}
				
				function drawScreen() {
					//bg
					context.fillStyle = "#000000";
					context.fillRect(0,0,theCanvas.width,theCanvas.height);
					
					drawShapes();		
				}
				
			}
        }
        

	</script>
</head>
<body>
<!-- ###################################################################################### -->
<!-- page -->
    <div data-role="page" id="pgFixation">
        <div data-role="header">
        	<?php
			if($user_check > 0)
			{
				echo("<a id='btnBack' href='settingsMain.php' data-role='button' data-mini='true' data-ajax='false'>Settings</a>");
			}
        	?>
        	<h3>Fixation</h3>
       </div><!-- /header -->

        <div data-role="content">
            <div id="canvasHolder" style="background-color: #efefdd; position: absolute; top:97; left:0; width: 100%; height:100vh;">
				<canvas id="canvasOne" width="500" height="300">
					Your browser does not support HTML5 canvas.
				</canvas>
            </div>
            <div id="spritzer" style="position: absolute;"></div>
        </div><!-- /content -->
    </div><!-- /page -->
</body>
</html>
