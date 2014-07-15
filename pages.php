<?php
//================================================================
include ("dbConfig.php");
include ("dbOpen.php");
include ("settingsUtilities.php");

//variables
if(session_id() == false){
	session_start();
}

$displayType = "";
if (isset( $_GET['t'])){
	$displayType = $_GET['t'];
}
$displayData = "";
$user_name = "";
$user_check = 0;
$errClass = "";
$errMsg = "";

if(isset($_SESSION['brUserID'])){
	$user_check = $_SESSION['brUserID'];
}
else{
	header("location:login.php");
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

//################################################################
//Get Setting for user
$settingsArray = GetSettingsValues($conn);
extract($settingsArray);



include ("dbClose.php");
//================================================================
?>
<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Big Easy Reader by Zayco</title>
    	
	<?php include("_jqueryPartial.php"); ?>

	<script type="text/javascript">
		var IsLoggedIn =<?php echo($user_check) ?>;
		Settings.ColorTheme = '<?php echo($settingColorValue) ?>';
		Settings.ColorThemeID = '<?php echo($settingColor) ?>';
		Settings.Size = '<?php echo($settingSize) ?>';
		Settings.Speed = '<?php echo($settingSpeed) ?>';
		Settings.Fixation = '<?php echo($settingFixation) ?>';

	
		$(document).bind("mobileinit", function() {
			$.mobile.page.prototype.options.contentTheme = "e";
			//your theme
		});
		
		//## pgHome #################################################
		$(document).on('pageinit', '#pgHome', function(){       
		});

		//## pgSettingsLanding #################################################
		$(document).on('pagebeforeshow', '#pgSettingsLanding', function(){   
			$('#spnColorTheme').html(Settings.ColorTheme);    
			$('#spnSize').html(Settings.Size + ' %');    
			$('#spnSpeed').html(Settings.Speed + ' wpm');    
			$('#spnFixation').html(Settings.Fixation);    
		});

		//## pgSettingsColor #################################################
		$(document).on('pageinit', '#pgSettingsColor', function(){       
			$('#btnDark').on('click', function(){
				Settings.ColorTheme = "Dark Theme";
				Settings.ColorThemeID = 1;
				SpritzHelper.SetThemeColors();
				SpritzHelper.SaveThemeColors();
			});
			$('#btnLight').on('click', function(){
				Settings.ColorTheme = "Light Theme";
				Settings.ColorThemeID = 2;
				SpritzHelper.SetThemeColors();
				SpritzHelper.SaveThemeColors();
			});
			
			SpritzHelper.SetThemeColors();
		});

		//## pgSettingsSize #################################################
		$(document).on('pagebeforeshow', '#pgSettingsSize', function(){ 
			SpritzHelper.SetupSpritzerUI();  
			TheSpritzerControl = $('#spritzerSize');
			TheSpritzerControl.empty();
            SpritzHelper.InitSpritz(true);
            SpritzHelper.CenterSpritzerControl(TheSpritzerControl, $('#pgSettingsSize'));
		});

		//## pgSettingsSize #################################################
		$(document).on('pageinit', '#pgSettingsSize', function(){       
		    $('#sizerCtrl').on('slidestop', function(event, ui){
		    	Settings.Size = $(this).val();
		    	SpritzHelper.SaveSize();
		    	SpritzHelper.SetupSpritzerUI();
				var spritzerCtrl = $('#spritzerSize');
				spritzerCtrl.empty();
	            SpritzHelper.InitSpritz(spritzerCtrl, CustomOptions, true);
	            SpritzHelper.CenterSpritzerControl(spritzerCtrl, $('#pgSettingsSize'));
		    });
		    
			$('#sizerCtrl').val(parseInt(Settings.Size)).slider('refresh');

			SpritzHelper.SetThemeColors();
		});

		//## pgSettingsSpeed #################################################
		$(document).on('pagebeforeshow', '#pgSettingsSpeed', function(){ 
			SpritzHelper.SetupSpritzerUI();  
			TheSpritzerControl = $('#spritzerSpeed');
			TheSpritzerControl.empty();
            SpritzHelper.InitSpritz(true);
            SpritzHelper.CenterSpritzerControl(TheSpritzerControl, $('#pgSettingsSpeed'));
		});

		//## pgSettingsSpeed #################################################
		$(document).on('pageinit', '#pgSettingsSpeed', function(){       
		    $('#speederCtrl').on('slidestop', function(event, ui){
		    	Settings.Speed = $(this).val();
		    	SpritzHelper.SaveSpeed();
				CustomOptions.defaultSpeed = Settings.Speed;
		    	SpritzHelper.SetupSpritzerUI();
				var spritzerCtrl = $('#spritzerSpeed');
				spritzerCtrl.empty();
	            SpritzHelper.InitSpritz(spritzerCtrl, CustomOptions, true);
	            SpritzHelper.CenterSpritzerControl(spritzerCtrl, $('#pgSettingsSpeed'));
		    });
		    
			$('#speederCtrl').val(parseInt(Settings.Speed)).slider('refresh');

			SpritzHelper.SetThemeColors();
		});

		//## pgSettingsFixation #################################################
		$(document).on('pagebeforeshow', '#pgSettingsFixation', function(){ 
			SpritzHelper.SetupSpritzerUI();  
			TheSpritzerControl = $('#spritzerFixation');
			TheSpritzerControl.empty();
            SpritzHelper.InitSpritz(true);
            SpritzHelper.CenterSpritzerControl(TheSpritzerControl, $('#pgSettingsFixation'));
            
            if(Settings.Fixation === 'None' ){
            	$('.fixationPoint').addClass('hide');
            }
            else{
	            var fpX = Settings.Fixation.split(',')[0];
	            var fpY = Settings.Fixation.split(',')[1];
            	$('.fixationPoint').removeClass('hide');
	            $('.fixationPoint').css('left', fpX + 'px');
	            $('.fixationPoint').css('top', fpY + 'px');
            	$('.fixationPoint').draggable({
            		stop:function(){
            			var position = $(this).position();
            			var newPos = position.left + ',' + position.top;
            			Settings.Fixation = newPos;
            			SpritzHelper.SaveFixation();
            		}
            	});
           }

		});

		//## pgSettingsFixation #################################################
		$(document).on('pageinit', '#pgSettingsFixation', function(){    
			SpritzHelper.SetThemeColors();
			//SpritzHelper.InitCanvas();  

		});

		//## pgNewsRead #################################################
		$(document).on('pagebeforeshow', '#pgNewsRead', function(){ 
			ContentUrl = NewsReadInfo.Link;
			SpritzHelper.SetupSpritzerUI();  
			TheSpritzerControl = $('#spritzer');
			TheSpritzerControl.data('url', ContentUrl);
			TheSpritzerControl.empty();
            SpritzHelper.InitSpritz(false);
			//alert(SpritzClient.isUserLoggedIn());
            window.setTimeout(function(){SpritzHelper.CenterSpritzerControl(TheSpritzerControl, $('#pgNewsRead'));},100);
            
            TheSpritzerControl.append("<div class='divGlassCover' />");
            $('.divGlassCover').on('click', SpritzHelper.DoGlassCover);
            
            if(Settings.Fixation === 'None' ){
            	$('.fixationPoint').addClass('hide');
            }
            else{
	            var fpX = Settings.Fixation.split(',')[0];
	            var fpY = Settings.Fixation.split(',')[1];
	            $('.fixationPoint').css('left', fpX + 'px');
	            $('.fixationPoint').css('top', fpY + 'px');
            	$('.fixationPoint').removeClass('hide');
            	$('.fixationPoint').draggable({
            		stop:function(){
            			var position = $(this).position();
            			var newPos = position.left + ',' + position.top;
            			Settings.Fixation = newPos;
            			SpritzHelper.SaveFixation();
            		}
            	});
           }
		});

		//## pgNewsRead #################################################
		$(document).on('pageinit', '#pgNewsRead', function(){    
		    $('#speedCtrlNews').on('slidestop', function(event, ui){
		    	Settings.Speed = $(this).val();
		    	$('#spritzer').data('controller').setSpeed(parseInt(Settings.Speed));
		    });
  
			$('#speedCtrlNews').val(parseInt(Settings.Speed)).slider('refresh');
            $("#startSpritz").on("click", SpritzHelper.onStartSpritzClick);

			SpritzHelper.SetThemeColors();
			
		    var container = TheSpritzerControl;
		    if(container != null){
		        container.on("onSpritzComplete", function (event) {
		            window.history.back();
		        });
		    }

		});

		//## pgAbout #################################################
		$(document).on('pagebeforeshow', '#pgAbout', function(){    
			SpritzHelper.SetThemeColors();
		});

		$(document).ready(function() {
			$('#blkBooks').on('click', function() {
				$.mobile.changePage ('#pgBookList')
			});
			$('#blkNews').on('click', function() {
				$.mobile.changePage ('#pgNewsList')
			});
			$('#blkText').on('click', function() {
				window.location = "textread.php";
			});
			$('#blkWebLink').on('click', function() {
				window.location = "weblinkread.php";
			});

			$('.btnBlockDark').on('mouseover', function() {
				$(this).addClass('btnBlockDarkHover');
			});
			$('.btnBlockDark').on('mouseout', function() {
				$(this).removeClass('btnBlockDarkHover');
			});
	
	
		    // var bw = $(document).width() * .8;
		    // var customOptions = {
		        // 'debugLevel': 	0,
				// 'redicleWidth': 	bw,
				// 'redicleHeight': 	bw * .2,
				// 'defaultSpeed': 	180,
				// 'speedItems': 	[250, 300, 350, 400, 450, 500, 550, 600],
				// 'controlButtons': [],
				// 'header': { //show above the redicle
					// 'close': false, //close "x" button
					// 'closeHandler': '', //optional callback the dev can set, otherwise use our default
				// },
				// 'controlTitles' : {},
				// 'placeholderText': { //full sentence text that displays before/after a spritz
					// //startText': "Click to spritz.",
					// 'startTextColor': "#bababa",
					// 'endText': "",
					// 'endTextColor': ""
				// },
				// //"advanced" redicle options
				// redicle: {
					// 'lineStrokeWidth': .025,
					// 'backgroundColor': "#000011",
					// 'textNormalPaintColor': "#eeeeff",
					// 'textHighlightPaintColor': "#eeeeff", //red ORP
					// 'redicleLineColor': "#fff",
					// 'redicleLineWidth': 1,
					// 'countdownTime': 750,
					// 'countdownColor': "#e8e8e8",
					// 'countdownSlice': 5	// 5 milliseconds
				// }
		    // };
			// var contentUrl = 'http://www.zaycounlimited.com/ezbigread/welcomeMessage.txt';
			// var spritzer = $('#spritzerWelcome');
// 			
			// spritzer.data('url', contentUrl);
		    // spritzerController = new SPRITZ.spritzinc.SpritzerController(customOptions);
			// spritzerController.spritzClient = SpritzClient;
			// spritzerController.attach(spritzer);
// 			    
			// // Is there a user logged in?
            // if (SpritzClient.isUserLoggedIn()) {
              // // Yes, so we'll kick off the content retrieval process, and start the Spritz
              // // if and when retrieval completes successfully.
                // SpritzClient.fetchContents(contentUrl, onFetchSuccess, onFetchError);
            // } else {
              // // No, so we'll let the user initiate the Spritz. 
            // }

		});
		
        function onFetchSuccess(spritzText) {
          $('#spritzerWelcome').data('controller').startSpritzing(spritzText);
        }
        
        function onFetchError(error) {
          alert('Spritzing failed: ' + error.message);
        }
        

	</script>
</head>
<body>
	<!-- ## pgHome ################################################## -->
    <div data-role="page" id="pgHome">
        <div data-role="header">
        	<?php
			if($user_check > 0)
			{
				echo("<a id='btnLogOut' href='logout.php' data-role='button' data-mini='true' data-ajax='false'>Log Out</a>");
			}
        	?>
        	<h3>Big Easy Reader</h3>
        	<?php
        	if($user_check > 0)
        	{
        		echo("<div class='alignCenter welcomeHeader'>Welcome: " . $user_name . "</div>");
        	}

        	?>
        	<?php
			if($user_check > 0)
			{
				echo("<a id='btnSettings' href='#pgSettingsLanding' data-role='button' data-mini='true' data-transition='forward flip'>Settings</a>");
			}
        	?>

        </div><!-- /header -->

        <div data-role="content">
        	<?php
			if($user_check > 0)
			{
				//echo("<div class='btnBlock btnBlockDark'>Resume</div>");
				//echo("<div id='blkBooks' class='btnBlock btnBlockDark'>Books</div>");
				echo("<div id='blkNews' class='btnBlock btnBlockDark'>News</div>");
				//echo("<div id='blkText' class='btnBlock btnBlockDark'>Text</div>");
				//echo("<div id='blkWebLink' class='btnBlock btnBlockDark'>Web Link</div>");
			}
			else
			{
				echo("<div id='spritzerWelcomeHolder'><div id='spritzerWelcome'></div></div>");
				echo("<div id='txtPleaseLogInText'>
                        <a id='btnLogIn' href='#pgLogin' data-role='button' data-mini='true' data-ajax='false' data-inline='true'>Log In...</a>
                        &nbsp; 
                        <a id='btnSignUp' href='signup.php' data-role='button' data-mini='true' data-ajax='false' data-inline='true'>Sign Up...</a>
                        </div>");
			}
        	?>
        </div><!-- /content -->

		<?php
			include("_footerPartial.php");
		?>
		<div data-role="popup" id="popupActionError" class="ui-content" style="max-width:280px; background-color:#00ff00;">
		    <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
			<p>Oops.  You are not logged in.</p>
			<p>Please log in or sign up <span id="logInActionErrorMessage"></span>.</p>
			<p>Thank you.</p>
		</div>
    </div><!-- /pgHome -->

	<!-- ## pgLogin ################################################## -->
    <div data-role="page" id="pgLogin">
        <div data-role="header">
        	<a id="btnHome" href="#pgHome" data-role="button" data-mini="true" id="hdrBack" data-transition="reverse flip">Home</a>
	        <h3>Log In</h3>
        </div><!-- /header -->
        <div data-role="content" data-inset="true">
			<form id="frmLogin" action="checklogin.php" method="post" data-ajax="false">
				<div id="messageError" class="<?php echo($errClass); ?> hide"><?php echo($errMsg);  ?></div>
				<label>Email :</label>
				<input type="text" name="myemail" id="myemail"/>
				<div id="messageEmail"></div>
				<br />
				<label>Password :</label>
				<input type="password" name="mypassword" id="mypassword"/><br/>
				<div class="alignCenter">
					<a id="btnLogInPage" href="" onclick='DoLogin();' data-inline='true' data-role='button' data-mini='true'>Login</a><br />
					<a id="btnLogInPage" href="#pgSignUp" data-inline='true' data-role='button' data-mini='true'>Sign Up</a><br />
				</div>
			</form>
        </div><!-- /content -->
    </div><!-- /pgLogin -->
    
	<!-- ## pgSignUp ################################################## -->
    <div data-role="page" id="pgSignUp">
        <div data-role="header">
        	<a id="btnHome" href="#pgHome" data-role="button" data-mini="true" id="hdrBack" data-transition="reverse flip">Home</a>
	        <h3>Sign Up</h3>
        </div><!-- /header -->
        <div data-role="content" data-inset="true">
        </div><!-- /content -->
    </div><!-- /pgSignUp -->
    
	<!-- ## pgSettingsLanding ################################################## -->
    <div data-role="page" id="pgSettingsLanding">
        <div data-role="header">
        	<a href="#pgHome" data-role="button" data-mini="true" id="hdrBack" data-transition="reverse flip">Back</a>
        	<h3>Settings</h3>
        </div><!-- /header -->
        <div data-role="content">
        	<ul data-role="listview" data-inset="true">
			    <li><a href="#pgSettingsColor" data-transition="forward slide">Colors<span class="listAside" id='spnColorTheme'></span></a></li>
			    <li><a href="#pgSettingsSize" data-transition="forward slide">Size<span class="listAside" id='spnSize'></span></a></li>
			    <li><a href="#pgSettingsSpeed" data-transition="forward slide">Speed<span class="listAside" id='spnSpeed'></span></a></li>
			    <li><a href="#pgSettingsFixation" data-transition="forward slide">Fixation<span class="listAside" id="spnFixation"></span></a></li>
			</ul>
        </div><!-- /content -->
    </div><!-- /pgLogin -->

	<!-- ## pgSettingsColor ################################################## -->
    <div data-role="page" id="pgSettingsColor">
        <div data-role="header">
        	<a href="#pgSettingsLanding" data-role="button" data-mini="true" id="hdrBack" data-rel="back" data-transition="reverse slide">Back</a>
        	<h3>Color</h3>
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
    </div><!-- /pgSettingsColor -->

	<!-- ## pgSettingsSize ################################################## -->
    <div data-role="page" id="pgSettingsSize">
        <div data-role="header">
        	<a href="#pgSettingsLanding" data-role="button" data-mini="true" id="hdrBack" data-rel="back" data-transition="reverse slide">Back</a>
        	<h3>Size</h3>
        </div><!-- /header -->
        <div data-role="content">
        	<div id="sizerHolder">
        		<input type="range" name="sizerCtrl" id="sizerCtrl" data-highlight="true" min="20" max="100" step="10" value="40" readonly>
        	</div>
        	<div class="clear"></div>
        	<div class="divCtrlHolder">
            	<div id="spritzerSize" style="position: absolute;"></div>
        	</div>
        </div><!-- /content -->
    </div><!-- /pgSettingsSize -->

	<!-- ## pgSettingsSpeed ################################################## -->
    <div data-role="page" id="pgSettingsSpeed">
        <div data-role="header">
        	<a href="#pgSettingsLanding" data-role="button" data-mini="true" id="hdrBack" data-rel="back" data-transition="reverse slide">Back</a>
        	<h3>Speed</h3>
        </div><!-- /header -->
        <div data-role="content">
        	<div id="speederHolder">
        		<input type="range" name="speederCtrl" id="speederCtrl" data-highlight="true" min="1" max="1000" step="1" value="200" readonly>
        	</div>
        	<div class="clear"></div>
        	<div class="divCtrlHolder">
            	<div id="spritzerSpeed" style="position: absolute;"></div>
        	</div>
        </div><!-- /content -->
    </div><!-- /pgSettingsSpeed -->

	<!-- ## pgSettingsFixation ################################################## -->
    <div data-role="page" id="pgSettingsFixation">
        <div data-role="header">
        	<table>
        		<tr>
        			<td><a href="#pgSettingsLanding" data-role="button" data-mini="true" id="hdrBack" data-rel="back" data-transition="reverse slide">Back</a></td>
        			<td style="width:100%;"><h3>Fixation</h3></td>
        			<td><a href="javascript:SpritzHelper.ResetFixation();" data-role="button" data-mini="true" id="hdrReset">Reset</a></td>
        			<td><a href="javascript:SpritzHelper.NoneFixation();" data-role="button" data-mini="true" id="hdrReset">None</a></td>
        		</tr>
        	</table>
        	
        	
        	
        	
        </div><!-- /header -->
        <div data-role="content">
            <!-- <div id="canvasHolder" style="background-color: #efefdd; position: absolute; top:97; left:0; width: 100%; height:100vh;">
				<canvas id="canvasOne" width="500" height="300">
					Your browser does not support HTML5 canvas.
				</canvas>
            </div> -->
           	<div class="divCtrlHolder">
                <img src='images/FixationPoint.png' id="imgFixationPoint" class='fixationPoint' />
            	<div id="spritzerFixation"></div>
        	</div>
        	<div class="clear"></div>
        </div><!-- /content -->
    </div><!-- /pgSettingsFixation -->

	<!-- ## pgBookList ################################################## -->
    <div data-role="page" id="pgBookList">
        <div data-role="header">
        	<a href="#pgHome" data-role="button" data-mini="true" id="hdrBack" data-rel="back" data-transition="reverse slide">Back</a>
        	<h3>Book List</h3>
        </div><!-- /header -->
        <div data-role="content">
        </div><!-- /content -->
    </div><!-- /pgBookList -->

	<!-- ## pgNewsList ################################################## -->
    <div data-role="page" id="pgNewsList">
        <div data-role="header">
        	<a href="#pgHome" data-role="button" data-mini="true" id="hdrBack" data-rel="back" data-transition="reverse slide">Back</a>
        	<h3>News List</h3>
        </div><!-- /header -->
        <div data-role="content">
	        <select data-native-menu="false" onchange="SpritzHelper.ShowRSS(this.value)">
				<option value="-1" data-placeholder="true">Choose one...</option>
	        	<option value="CNN">CNN News</option>
	        	<option value="CBS">CBS News</option>
	        	<option value="NBC">NBC News</option>
	        	<option value="TRUMP">The Trumpet</option>
	        </select>
	        <div style="height:20px;"></div>
	        <ul id="newsStories" data-role="listview" data-inset="true">
			</ul>
        </div><!-- /content -->
    </div><!-- /pgNewsList -->

<	<!-- ## pgNewsRead ################################################## -->
    <div data-role="page" id="pgNewsRead">
        <div data-role="header">
        	<a href="#pgNewsList" data-role="button" data-mini="true" id="hdrBack" data-rel="back" data-transition="reverse slide">Back</a>
        	<h3>News</h3>
        </div><!-- /header -->
        <div data-role="content">
        	<div class="divCtrlHolder">
                <img src='images/FixationPoint.png' id="imgFixationPoint" class='fixationPoint' />
	            <div id="spritzer" style="position:relative;"></div>
	        
	            <div class="spacer-b">
	                <img src='images/Play.png' id="startSpritz" class='ctrlBtn' />
	               	<img src='images/Pause.png' id="togglepauseSpritz" class='ctrlBtn'  />
	                <img src='images/Beginning.png' id="btnReset" class='ctrlBtn ctrlBtnSpacerLeft'  />
	                <img src='images/Back-Sentence.png' id="btnBackSentence" class='ctrlBtn ctrlBtnSpacerLeft'  />
	                <img src='images/Back-Word.png' id="btnBackWord" class='ctrlBtn'  />
	                <img src='images/Forward-Word.png' id="btnNextWord" class='ctrlBtn'  />
	                <img src='images/Forward-Sentence.png' id="btnNextSentence" class='ctrlBtn'  />
	            </div>
	            <div class="hide" style="color:#fff;"></div>
	            <div id="speedHolder" class=''>
	                <input type="range" name="speedCtrlNews" id="speedCtrlNews" data-highlight="true" min="1" max="1000" value="150" readonly="readonly">
	            </div>
            </div>
        </div><!-- /content -->
    </div><!-- /pgNewsRead -->

	<!-- ## pgAbout ################################################## -->
    <div data-role="page" id="pgAbout">
        <div data-role="header">
        	<a href="#pgHome" data-role="button" data-mini="true" id="hdrBack" data-rel="back" data-transition="reverse slide">Back</a>
        	<h3>About</h3>
        </div><!-- /header -->
        <div data-role="content">
        	<p>We're all about making things easy.</p>
        	<p>And we want reading to be easy for you, too.</p>
        </div><!-- /content -->
    </div><!-- /pgAbout -->


</body>
</html>