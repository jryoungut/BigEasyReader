<?php
include("dbConfig.php");
include("dbOpen.php");

//variables
session_start();
$displayType = "";
if (isset( $_GET['t'])){
	$displayType = $_GET['t'];
}
$displayData = "";
$user_name = "";
$user_check = 0;

$settingColor = "1";

if(isset($_SESSION['brUserID'])){
	$user_check = $_SESSION['brUserID'];
}


//Get logged in user name
$query = "SELECT * FROM users WHERE ID = '" . $user_check . "'";
$result = $conn->query($query);
if($result === false) {
	trigger_error('Wrong SQL: ' . $query . ' Error: ' . $conn->error, E_USER_ERROR);
} else {
	$rows_returned = $result->num_rows;
}
$result->data_seek(0);
while($row = $result->fetch_assoc())
{
	$user_name = $row['FirstName'] . " " . $row['LastName'];
}

//Get Setting for user
$query = "SELECT SettingValue FROM settings WHERE UserID = '" . $user_check . "' AND SettingID = '1'";
$result = $conn->query($query);
if($result === false) {
	trigger_error('Wrong SQL: ' . $query . ' Error: ' . $conn->error, E_USER_ERROR);
} else {
	$rows_returned = $result->num_rows;
}
$result->data_seek(0);
while($row = $result->fetch_assoc())
{
	$settingColor = $row['SettingValue'];
}

switch ($settingColor){
	case "1":
		$settingColor = "Dark Theme";
		break;
	case "2":
		$settingColor = "Light Theme";
		break;
}




include("dbClose.php");
?>
<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Big EZ Reader by Zayco, powered by Spritz</title>
	<?php //include("_jqueryPartial.php"); ?>
	
	<script type="text/javascript">
		var host = window.location.hostname;
		var http = location.protocol;
		var slashes = http.concat("//");
		var locandfile = '/ezbigread/login_success.html';
		var SpritzSettings = {
		    clientId: "cc7314548848c6af2",
		    redirectUri: locandfile
		};
	</script>
	
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.0/jquery.mobile-1.4.0.min.css" />
	
    <script type="text/javascript" src="//sdk.spritzinc.com/jQuery/jquery-2.1.0.min.js"></script>
    <script type="text/javascript" src="//sdk.spritzinc.com/js/1.2/js/spritz.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/mobile/1.4.0/jquery.mobile-1.4.0.min.js"></script>
    <script type="text/javascript" src="scripts/modernizr-2.0.6.js"></script>

    <!-- <script type="text/javascript" src="scripts/spritzHelper.js"></script> -->
    
    <link rel="stylesheet" type="text/css" href="css/ezBigRead.css">
	<!-- <link rel="stylesheet" type="text/css" href="css/themes/AMDDark.css" />
	<link rel="stylesheet" type="text/css" href="css/themes/jquery.mobile.icons.min.css" />
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.2/jquery.mobile.structure-1.4.2.min.css" /> -->
	<script type="text/javascript">
		var IsLoggedIn =<?php echo($user_check) ?>;
	
		var spritzController = null;

		var host = slashes.concat(host);
		var locandfile = host.concat(locandfile);

		$(document).ready(function() {

            
		});
		
		$(document).on('pageinit', '#pgColors', function(){       
			$('#btnDark').on('click', function(){
				$('div').removeClass('themeLight');
				$('div').addClass('themeDark');
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
			});
			$('#btnLight').on('click', function(){
				$('div').removeClass('themeDark');
				$('div').addClass('themeLight');
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
			});
		});

		//***********************************************
		//  Size
		//***********************************************
		$(document).on('pageinit', '#pgSize', function(){       
		    $('#sizerCtrl').on('slidestop', function(event, ui){
		    	var pct = $(this).val()/100;
		    	var bw = $('#sizerHolder').width() * pct;
				$('.spritz-canvas').prop('width', bw);
	        	$('.spritz-canvas').prop('height', bw * .2);
		    });

            initSpritz($('#spritzerSize'));
		});
		
		$(document).on('pagebeforeshow', '#pgSize', function(){       
		    //alert('Pagebeforeshow');
		});

		$(document).on('pageinit', '#pgSpeed', function(){     
		    $('#speedCtrl').on('slidestop', function(event, ui){
		    	var spd = $(this).val();
		    	
		    	var dataString = {
		    		"settingID" : "3",
		    		"settingValue" : spd
		    	};
		    	
    	        $.ajax({  
		            type: "POST",  
		            url: "settingsFunctions.php/SaveSettings",  
		            data: JSON.stringify(dataString),
		            success: function(){  
		                //alert('here');  
		            }  
		        });  

		    	$('#spritzerSpeed').data('controller').setSpeed(parseInt(spd));
		    });

		    var bw = $('#sizerHolder').width() * .6;
		    var customOptions = {
		        'debugLevel': 	0,
				'redicleWidth': 	340,
				'redicleHeight': 	70,
				'defaultSpeed': 	250,
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
					'lineStrokeWidth': .025,
					'backgroundColor': "#000011",
					'textNormalPaintColor': "#eeeeff",
					'textHighlightPaintColor': "#eeeeff", //red ORP
					'redicleLineColor': "#fff",
					'redicleLineWidth': 1,
					'countdownTime': 750,
					'countdownColor': "#e8e8e8",
					'countdownSlice': 5	// 5 milliseconds
				}
		    };
		    //spritzController = new SPRITZ.spritzinc.SpritzerController(customOptions);

            initSpritz($('#spritzerSpeed'), customOptions);
		});
		
		$(document).on('pagebeforeshow', '#pgSpeed', function(){       
			setupSpritzerDefaultUI();
		});

		$(document).on('pageinit', '#pgFixation', function(){     
			initCanvas();  
 
 		    var bw = $( window ).width() * .6;
		    var customOptions = {
		        "redicleWidth": bw,					                    // Specify Redicle width
		        "redicleHeight": bw * .2,				                // Specify Redicle height
		        "defaultSpeed": 200, 									// Specify default speed
		        "speedItems": [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134, 135, 136, 137, 138, 139, 140, 141, 142, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 153, 154, 155, 156, 157, 158, 159, 160, 161, 162, 163, 164, 165, 166, 167, 168, 169, 170, 171, 172, 173, 174, 175, 176, 177, 178, 179, 180, 181, 182, 183, 184, 185, 186, 187, 188, 189, 190, 191, 192, 193, 194, 195, 196, 197, 198, 199, 200, 201, 202, 203, 204, 205, 206, 207, 208, 209, 210, 211, 212, 213, 214, 215, 216, 217, 218, 219, 220, 221, 222, 223, 224, 225, 226, 227, 228, 229, 230, 231, 232, 233, 234, 235, 236, 237, 238, 239, 240, 241, 242, 243, 244, 245, 246, 247, 248, 249, 250, 251, 252, 253, 254, 255, 256, 257, 258, 259, 260, 261, 262, 263, 264, 265, 266, 267, 268, 269, 270, 271, 272, 273, 274, 275, 276, 277, 278, 279, 280, 281, 282, 283, 284, 285, 286, 287, 288, 289, 290, 291, 292, 293, 294, 295, 296, 297, 298, 299, 300, 301, 302, 303, 304, 305, 306, 307, 308, 309, 310, 311, 312, 313, 314, 315, 316, 317, 318, 319, 320, 321, 322, 323, 324, 325, 326, 327, 328, 329, 330, 331, 332, 333, 334, 335, 336, 337, 338, 339, 340, 341, 342, 343, 344, 345, 346, 347, 348, 349, 350, 351, 352, 353, 354, 355, 356, 357, 358, 359, 360, 361, 362, 363, 364, 365, 366, 367, 368, 369, 370, 371, 372, 373, 374, 375, 376, 377, 378, 379, 380, 381, 382, 383, 384, 385, 386, 387, 388, 389, 390, 391, 392, 393, 394, 395, 396, 397, 398, 399, 400, 401, 402, 403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417, 418, 419, 420, 421, 422, 423, 424, 425, 426, 427, 428, 429, 430, 431, 432, 433, 434, 435, 436, 437, 438, 439, 440, 441, 442, 443, 444, 445, 446, 447, 448, 449, 450, 451, 452, 453, 454, 455, 456, 457, 458, 459, 460, 461, 462, 463, 464, 465, 466, 467, 468, 469, 470, 471, 472, 473, 474, 475, 476, 477, 478, 479, 480, 481, 482, 483, 484, 485, 486, 487, 488, 489, 490, 491, 492, 493, 494, 495, 496, 497, 498, 499, 500, 501, 502, 503, 504, 505, 506, 507, 508, 509, 510, 511, 512, 513, 514, 515, 516, 517, 518, 519, 520, 521, 522, 523, 524, 525, 526, 527, 528, 529, 530, 531, 532, 533, 534, 535, 536, 537, 538, 539, 540, 541, 542, 543, 544, 545, 546, 547, 548, 549, 550, 551, 552, 553, 554, 555, 556, 557, 558, 559, 560, 561, 562, 563, 564, 565, 566, 567, 568, 569, 570, 571, 572, 573, 574, 575, 576, 577, 578, 579, 580, 581, 582, 583, 584, 585, 586, 587, 588, 589, 590, 591, 592, 593, 594, 595, 596, 597, 598, 599, 600, 601, 602, 603, 604, 605, 606, 607, 608, 609, 610, 611, 612, 613, 614, 615, 616, 617, 618, 619, 620, 621, 622, 623, 624, 625, 626, 627, 628, 629, 630, 631, 632, 633, 634, 635, 636, 637, 638, 639, 640, 641, 642, 643, 644, 645, 646, 647, 648, 649, 650, 651, 652, 653, 654, 655, 656, 657, 658, 659, 660, 661, 662, 663, 664, 665, 666, 667, 668, 669, 670, 671, 672, 673, 674, 675, 676, 677, 678, 679, 680, 681, 682, 683, 684, 685, 686, 687, 688, 689, 690, 691, 692, 693, 694, 695, 696, 697, 698, 699, 700, 701, 702, 703, 704, 705, 706, 707, 708, 709, 710, 711, 712, 713, 714, 715, 716, 717, 718, 719, 720, 721, 722, 723, 724, 725, 726, 727, 728, 729, 730, 731, 732, 733, 734, 735, 736, 737, 738, 739, 740, 741, 742, 743, 744, 745, 746, 747, 748, 749, 750, 751, 752, 753, 754, 755, 756, 757, 758, 759, 760, 761, 762, 763, 764, 765, 766, 767, 768, 769, 770, 771, 772, 773, 774, 775, 776, 777, 778, 779, 780, 781, 782, 783, 784, 785, 786, 787, 788, 789, 790, 791, 792, 793, 794, 795, 796, 797, 798, 799, 800, 801, 802, 803, 804, 805, 806, 807, 808, 809, 810, 811, 812, 813, 814, 815, 816, 817, 818, 819, 820, 821, 822, 823, 824, 825, 826, 827, 828, 829, 830, 831, 832, 833, 834, 835, 836, 837, 838, 839, 840, 841, 842, 843, 844, 845, 846, 847, 848, 849, 850, 851, 852, 853, 854, 855, 856, 857, 858, 859, 860, 861, 862, 863, 864, 865, 866, 867, 868, 869, 870, 871, 872, 873, 874, 875, 876, 877, 878, 879, 880, 881, 882, 883, 884, 885, 886, 887, 888, 889, 890, 891, 892, 893, 894, 895, 896, 897, 898, 899, 900, 901, 902, 903, 904, 905, 906, 907, 908, 909, 910, 911, 912, 913, 914, 915, 916, 917, 918, 919, 920, 921, 922, 923, 924, 925, 926, 927, 928, 929, 930, 931, 932, 933, 934, 935, 936, 937, 938, 939, 940, 941, 942, 943, 944, 945, 946, 947, 948, 949, 950, 951, 952, 953, 954, 955, 956, 957, 958, 959, 960, 961, 962, 963, 964, 965, 966, 967, 968, 969, 970, 971, 972, 973, 974, 975, 976, 977, 978, 979, 980, 981, 982, 983, 984, 985, 986, 987, 988, 989, 990, 991, 992, 993, 994, 995, 996, 997, 998, 999, 1000],
		        "controlButtons": [],
		        "controlTitles": {}
		    };

           	initSpritz($("#spritzerFixation"), customOptions);
            centerSpritzerControl($('#spritzerFixation'),bw*.2, bw);
		});
		
		$(document).on('pagebeforeshow', '#pgFixation', function(){     
		});
		

        function initSpritz(ctrl, customOps) {
          var contentUrl = 'http://www.zaycounlimited.com/bigread/Books/PoetryOfASoldier.txt';
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
              var successMethodName = 'onFetchSuccess_' + spritzerId;
                SpritzClient.fetchContents(contentUrl, eval(successMethodName), onFetchError);
            } else {
              // No, so we'll let the user initiate the Spritz. 
            }
        }

        function onFetchSuccess_spritzerSpeed(spritzText) {
          $('#spritzerSpeed').data('controller').startSpritzing(spritzText);
        }
        function onFetchSuccess_spritzerSize(spritzText) {
          $('#spritzerSize').data('controller').startSpritzing(spritzText);
        }
        
        function onFetchError(error) {
          alert('Spritzing failed: ' + error.message);
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
				
				var numShapes;
				var shapes;
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
					var tempX;
					var tempY;
					var tempRad;
					var tempR;
					var tempG;
					var tempB;
					var tempColor;
					for (i=0; i < numShapes; i++) {
						// tempRad = 10 + Math.floor(Math.random()*25);
						// tempX = Math.random()*(theCanvas.width - tempRad);
						// tempY = Math.random()*(theCanvas.height - tempRad);
						// tempR = Math.floor(Math.random()*255);
						// tempG = Math.floor(Math.random()*255);
						// tempB = Math.floor(Math.random()*255);
						tempRad = 10;
						tempX = 0;
						tempY = 0;
						tempR = 255;
						tempG = 255;
						tempB = 255;
						tempColor = "rgb(" + tempR + "," + tempG + "," + tempB +")";
						tempShape = {x:tempX, y:tempY, rad:tempRad, color:tempColor};
						shapes.push(tempShape);
					}
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
					var i;
					for (i=0; i < numShapes; i++) {
						context.fillStyle = shapes[i].color;
						context.beginPath();
						context.arc(shapes[i].x, shapes[i].y, shapes[i].rad, 0, 2*Math.PI, false);
						context.closePath();
						context.fill();
					}
				}
				
				function drawScreen() {
					//bg
					context.fillStyle = "#000000";
					context.fillRect(0,0,theCanvas.width,theCanvas.height);
					
					drawShapes();		
				}
				
			}
        }
        
        function centerSpritzerControl(ctrl, h, w){
        	var cW = w;
        	var cH = h;
        	var win = $(window);
        	var winWidth = win.width();
        	var winHeight = win.height();
        	
        	ctrl.css('margin-top', ((winHeight/2) - cH/2) + 'px');
        	ctrl.css('margin-left', ((winWidth/2) - cW/2) + 'px');
        	
        }
        
        function setupSpritzerDefaultUI(){
        	$('.spritzer-container').css('background-color', 'transparent');
        	$('.spritzer-container').css('border', 'none');
        }
	</script>
</head>
<body>
<!-- ###################################################################################### -->
    <div data-role="page" id="pgSettings">
        <div data-role="header">
        	<?php
			if($user_check > 0)
			{
				echo("<a id='btnBack' href='index.php' data-role='button' data-mini='true' data-ajax='false'>Back</a>");
			}
        	?>
        	<h3>Settings</h3>
        </div><!-- /header -->

        <div data-role="content">
        	<ul data-role="listview" data-inset="true">
			    <li><a href="#pgColors">Colors<span class="listAside"><?php echo $settingColor; ?></span></a></li>
			    <li><a href="#pgSize">Size</a></li>
			    <li><a href="#pgSpeed">Speed</a></li>
			    <li><a href="#pgFixation">Fixation</a></li>
			</ul>
        </div><!-- /content -->

		<div data-role="footer"></div>
    </div><!-- /page -->

<!-- ###################################################################################### -->
    <div data-role="page" id="pgColors">
        <div data-role="header">
        	<?php
			if($user_check > 0)
			{
				echo("<a id='btnBack' href='#pgSettings' data-role='button' data-mini='true' data-ajax='false'>Settings</a>");
			}
        	?>
        	<h3>Colors</h3>
       </div><!-- /header -->

        <div data-role="content">
         	<table>
         		<tr>
         			<td><span id="spnDark" class='themeDark themeSelect'>&#10152;</span></td>
         			<td>
         				<a id='btnDark' data-role='button' data-ajax='false'>Dark Theme</a>
         			</td>
         		</tr>
          		<tr>
         			<td><span id="spnLight" class='themeDark themeSelect'></span></td>
         			<td>
						<a id='btnLight' data-role='button' data-ajax='false'>Light Theme</a>
					</td>
         		</tr>
        	</table>
        </div><!-- /content -->

    </div><!-- /page -->

<!-- ###################################################################################### -->
    <div data-role="page" id="pgSize">
        <div data-role="header">
        	<?php
			if($user_check > 0)
			{
				echo("<a id='btnBack' href='#pgSettings' data-role='button' data-mini='true' data-ajax='false'>Settings</a>");
			}
        	?>
        	<h3>Sizes</h3>
        </div><!-- /header -->

        <div data-role="content">
        	<div id="sizerHolder">
        		<input type="range" name="sizerCtrl" id="sizerCtrl" data-highlight="true" min="20" max="100" step="10" value="40">
        	</div>
            <div id="spritzerSize"></div>

        </div><!-- /content -->
    </div><!-- /page -->

<!-- ###################################################################################### -->
    <div data-role="page" id="pgSpeed">
        <div data-role="header">
        	<?php
			if($user_check > 0)
			{
				echo("<a id='btnBack' href='#pgSettings' data-role='button' data-mini='true' data-ajax='false'>Settings</a>");
			}
        	?>
        	<h3>Speed</h3>
        </div><!-- /header -->

        <div data-role="content">
        	<div id="sizerHolder">
        		<input type="range" name="speedCtrl" id="speedCtrl" data-highlight="true" min="1" max="1000" step="1" value="200">
        	</div>
            <div id="spritzerSpeed"></div>
       </div><!-- /content -->

    </div><!-- /page -->

<!-- ###################################################################################### -->
    <div data-role="page" id="pgFixation">
        <div data-role="header">
        	<?php
			if($user_check > 0)
			{
				echo("<a id='btnBack' href='#pgSettings' data-role='button' data-mini='true' data-ajax='false'>Settings</a>");
			}
        	?>
        	<h3>Fixation</h3>
        </div><!-- /header -->

        <div data-role="content" style="position: relative; height:100vh;">
            <div id="canvasHolder" style="background-color: #efefdd; position: absolute; top:0; left:0; width: 100%; height:100vh;">
				<canvas id="canvasOne" width="500" height="300">
					Your browser does not support HTML5 canvas.
				</canvas>
            </div>
            <div id="spritzerFixation"></div>

        </div><!-- /content -->
    </div><!-- /page -->

<!-- ###################################################################################### -->

</body>
</html>
