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
	//header("location:login.php");
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
			SpritzHelper.SetThemeColors();   
			SpritzHelper.SetFontSize();  
		});

		//## pgSettingsLanding #################################################
		$(document).on('pagebeforeshow', '#pgSettingsLanding', function(){   
			$('#spnColorTheme').html(Settings.ColorTheme);    
			$('#spnSize').html(Settings.Size + ' pt');    
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
			ContentUrl = ContentUrlDOI;
			SpritzHelper.SetupSpritzerUI();  
			TheSpritzerControl = $('#spritzerSize');
			TheSpritzerControl.empty();
            SpritzHelper.InitSpritz(true);
            SpritzHelper.CenterSpritzerControl(TheSpritzerControl, $('#pgSettingsSize'), 2);
                
		    $('.spritzer-container').addClass('reset-this');
		});

		//## pgSettingsSize #################################################
		$(document).on('pageinit', '#pgSettingsSize', function(){       
		    $('.fontSizeBtn').on('click', function(event, ui){
		    	Settings.Size = $(this).data('fontsize');
		    	SpritzHelper.SaveSize();
		    	SpritzHelper.SetupSpritzerUI();
				var spritzerCtrl = $('#spritzerSize');
				spritzerCtrl.empty();
	            SpritzHelper.InitSpritz(spritzerCtrl, CustomOptions, true);
	            SpritzHelper.CenterSpritzerControl(spritzerCtrl, $('#pgSettingsSize'), 2);
	            
	            $('.fontSizeBtn').removeClass('fontSizeBtnSelected');
	            $(this).addClass('fontSizeBtnSelected');
		    });
		    
		     var btn = $('#fontSizeBtnHolder').find("[data-fontsize='" + Settings.Size + "']");
		     btn.addClass('fontSizeBtnSelected');
			SpritzHelper.SetThemeColors();
		});

		//## pgSettingsSpeed #################################################
		$(document).on('pagebeforeshow', '#pgSettingsSpeed', function(){ 
			ContentUrl = ContentUrlDOI;
			SpritzHelper.SetupSpritzerUI();  
			TheSpritzerControl = $('#spritzerSpeed');
			TheSpritzerControl.empty();
            SpritzHelper.InitSpritz(true);
            SpritzHelper.CenterSpritzerControl(TheSpritzerControl, $('#pgSettingsSpeed'), 3);
                
		    $('.spritzer-container').addClass('reset-this');
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
	            SpritzHelper.CenterSpritzerControl(spritzerCtrl, $('#pgSettingsSpeed'),3);
		    });
		    
			$('#speederCtrl').val(parseInt(Settings.Speed)).slider('refresh');

			SpritzHelper.SetThemeColors();
		});

		//## pgSettingsFixation #################################################
		$(document).on('pagebeforeshow', '#pgSettingsFixation', function(){ 
			ContentUrl = ContentUrlDOI;
			SpritzHelper.SetupSpritzerUI();  
			TheSpritzerControl = $('#spritzerFixation');
			TheSpritzerControl.empty();
            SpritzHelper.InitSpritz(true);
            SpritzHelper.CenterSpritzerControl(TheSpritzerControl, $('#pgSettingsFixation'), 4);
                 
		    $('.spritzer-container').addClass('reset-this');
           
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
		});



		//## pgBookList #################################################
		$(document).on('pagebeforeshow', '#pgBookList', function(){ 
		    $.ajax({  
		        type: "GET",  
		        url: "getBookList.php",  
		        success: function(data){  
		            //alert(data);
		            if(data.length > 0){
		            	$('#lstBooks').html(data);
		            }  
		            else{
		            	$('#lstBooks').html('<li>No Books to Display</li>');
		            }
		        },
		        complete: function(){
					$('#lstBooks').css('font-size', Settings.Size + 'pt'); 
					SpritzHelper.SetThemeColors();
					$('#lstBooks').listview('refresh');
		        } 
		    });  
		});

		//## pgBookList #################################################
		$(document).on('pageinit', '#pgBookList', function(){  
		});

		//## pgNewsList #################################################
		$(document).on('pageinit', '#pgNewsList', function(){  
			$('#newsSourcesHolder a').on('click', function(){
				SpritzHelper.ShowRSS($(this).data('newssource'))
			});  
			$('.newsNameLink').on('click', function(){
				SpritzHelper.GetNewsList($(this))
			});  
			SpritzHelper.SetThemeColors();
		});

		//## pgNewsRead #################################################
		$(document).on('pagebeforeshow', '#pgNewsRead', function(){ 
			SpritzHelper.spritzingState = SpritzHelper.SPRITZ_STATE_RESET;
			SpritzHelper.doBtnUI();
			ContentUrl = NewsReadInfo.Link;
			SpritzHelper.SetupSpritzerUI();  
			TheSpritzerControl = $('#spritzer');
			TheSpritzerControl.data('url', ContentUrl);
			TheSpritzerControl.empty();
            SpritzHelper.InitSpritz(false);
			//alert(SpritzClient.isUserLoggedIn());
            window.setTimeout(function(){SpritzHelper.CenterSpritzerControl(TheSpritzerControl, $('#pgNewsRead'), 4);},100);
            
            TheSpritzerControl.data('controller').setSpeed(parseInt(Settings.Speed));
            $('#speedCtrlNews').val(parseInt(Settings.Speed)).slider('refresh');
            
		    $('.spritzer-container').addClass('reset-this');
                  
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
           
           $('.ctrlBtn').css('width', Settings.Size * 1.5 + 'px');
           $('input.ui-slider-input').css('font-size', Settings.Size + 'pt');
           $('input.ui-slider-input').css('height', Settings.Size * 2 + 'px');
           $('input.ui-slider-input').css('line-height', Settings.Size * 2 + 'px');
           $('input.ui-slider-input').css('width', Settings.Size * 3 + 'px');
           
           $('.ui-slider-track .ui-btn.ui-slider-handle').css('width', Settings.Size + 'px');
           $('.ui-slider-track .ui-btn.ui-slider-handle').css('height', Settings.Size + 'px');
           $('.ui-slider-track .ui-btn.ui-slider-handle').css('margin-top', (Settings.Size * .60)*-1 + 'px');

           $('.ui-slider-track').css('height', Settings.Size + 'px');
           $('.ui-slider-track').css('top', Settings.Size * .5 + 'px');
           $('.ui-slider-track').css('margin-left', Settings.Size * 4 + 'px');
           
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
			
		    var container = $('#spritzer');
		    if(container != null){
		        container.on("onSpritzComplete", function (event) {
					SpritzHelper.spritzingState = SpritzHelper.SPRITZ_STATE_RESET;
		        	SpritzHelper.doBtnUI();
		            $('body').pagecontainer('change', '#pgNewsList');
		        });
		    }

		});

		//## pgTextRead #################################################
		$(document).on('pagebeforeshow', '#pgTextRead', function(){ 
			ContentUrl = $('#pastedText').val();
			SpritzHelper.SetupSpritzerUI();  
			TheSpritzerControl = $('#spritzer');
			TheSpritzerControl.data('url', ContentUrl);
			TheSpritzerControl.empty();
            SpritzHelper.InitSpritz(false);
			//alert(SpritzClient.isUserLoggedIn());
            window.setTimeout(function(){SpritzHelper.CenterSpritzerControl(TheSpritzerControl, $('#pgNewsRead'), 4);},100);
            
		    $('.spritzer-container').addClass('reset-this');
                  
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

		//## pgTextRead #################################################
		$(document).on('pageinit', '#pgTextRead', function(){    
		    $('#speedCtrlNews').on('slidestop', function(event, ui){
		    	Settings.Speed = $(this).val();
		    	$('#spritzer').data('controller').setSpeed(parseInt(Settings.Speed));
		    });
  
			$('#speedCtrlNews').val(parseInt(Settings.Speed)).slider('refresh');
            $("#startSpritz").on("click", SpritzHelper.onStartSpritzClick);

			SpritzHelper.SetThemeColors();
			
		    var container = $('#spritzer');
		    if(container != null){
		        container.on("onSpritzComplete", function (event) {
					SpritzHelper.spritzingState = SpritzHelper.SPRITZ_STATE_RESET;
		        	SpritzHelper.doBtnUI();
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
				window.location = "#pgTextPaste";
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

			$('.doneBtn').on('click', function() {
				$('body').pagecontainer('change', SettingsReturnURL);
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
					// 'textNormalPaintColor': "#FEFEFF",
					// 'textHighlightPaintColor': "#FEFEFF", //red ORP
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
	<?php include_once("_analyticsTracking.php") ?>
</head>
<body>
	<!-- ## pgHome ################################################## -->
    <div data-role="page" id="pgHome" class="themeDark">
        <div data-role="header">
        	<?php
			if($user_check > 0)
			{
				echo("<a id='btnLogOut' href='logout.php' data-mini='true' data-ajax='false' class='themeDark'>Log Out</a>");
			}
        	?>
        	<h3 class="themeDark fontLarger-1">Big Easy Reader</h3>
        	<?php
        	if($user_check > 0)
        	{
        		echo("<div class='alignCenter fontSmaller-1 themeDark'>Welcome: " . $user_name . "</div>");
        	}

        	?>
        	<?php
			if($user_check > 0)
			{
			}
        	?>

        </div><!-- /header -->

        <div data-role="content">
        	<?php
			if($user_check > 0)
			{
				//echo("<div class='btnBlock btnBlockDark'>Resume</div>");
				//echo("<div id='blkBooks' class='btnBlock btnBlockDark'>Books</div>");
				echo("<div class='alignCenter'>");
				echo("<a id='blkNews' href='#' data-role='button' data-inline='true' class=' themeDark fontLarger-2'>News</a>");
				//echo("<a id='blkBooks' href='#' data-role='button' data-inline='true' class=' themeDark fontLarger-2'>Books</a>");
				echo("</div>");
				echo("<div class='alignCenter'><a id='btnSettings' href='#' onclick='javascript:SpritzHelper.GoToSettings(\"#pgHome\");' data-role='button' data-inline='true' class='themeDark fontLarger-1'>Settings</a></div>");
				//echo("<div id='blkText' class='btnBlock themeDark fontLarger-3'>Text</div>");
				//echo("<div id='blkWebLink' class='btnBlock btnBlockDark'>Web Link</div>");
			}
			else
			{
				echo("<div id='spritzerWelcomeHolder'><div id='spritzerWelcome'></div></div>");
				echo("<div id='txtPleaseLogInText' class='alignCenter themeDark'>
                        <a id='btnLogIn' href='login.php' data-role='button' data-ajax='false' data-inline='true' class='fontLarger-1'>Log In...</a>
                        &nbsp; 
                        <a id='btnSignUp' href='signup.php' data-role='button' data-ajax='false' data-inline='true' class='fontLarger-1'>Sign Up...</a>
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
        	<a id="btnHome" href="#pgHome" data-role="button" data-mini="true" id="hdrBack">Home</a>
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
        	<a id="btnHome" href="#pgHome" data-role="button" data-mini="true" id="hdrBack">Home</a>
	        <h3>Sign Up</h3>
        </div><!-- /header -->
        <div data-role="content" data-inset="true">
        </div><!-- /content -->
    </div><!-- /pgSignUp -->
    
	<!-- ## pgSettingsLanding ################################################## -->
    <div data-role="page" id="pgSettingsLanding" class="themeDark">
        <div data-role="header">
        	<a href="#pgHome" data-role="button" data-mini="true" id="hdrBack" class="themeDark">Back</a>
        	<h3 class="themeDark fontLarger-1">Settings</h3>
        </div><!-- /header -->
        <div data-role="content">
        	<ul data-role="listview" data-inset="true" class="themeDark">
			    <li><a href="#pgSettingsColor" class="themeDark">Colors<span class="listAside" id='spnColorTheme'></span></a></li>
			    <li><a href="#pgSettingsSize" class="themeDark">Size<span class="listAside" id='spnSize'></span></a></li>
			    <li><a href="#pgSettingsSpeed" class="themeDark">Speed<span class="listAside" id='spnSpeed'></span></a></li>
			    <li><a href="#pgSettingsFixation" class="themeDark">Fixation<span class="listAside" id="spnFixation"></span></a></li>
			</ul>
        </div><!-- /content -->
    </div><!-- /pgLogin -->

	<!-- ## pgSettingsColor ################################################## -->
    <div data-role="page" id="pgSettingsColor" class="themeDark">
        <div data-role="header">
        	<h3 class="themeDark fontLarger-1">Color</h3>
        </div><!-- /header -->
        <div data-role="content" class="alignCenter">
        	<div class="fontLarger-1">Choose color theme:</div>
        	<a id='btnDark' data-role="button" class="themeDarkUnchanging fontLarger-1" data-inline="true">White<br />on<br/>Black</a>
        	<span class="fontLarger-1">or &nbsp;</span>
        	<a id='btnLight' data-role="button" class="themeLightUnchanging fontLarger-1" data-inline="true">Black<br />on<br/>White</a>
        </div><!-- /content -->
        <div data-role="footer" class="alignRight">
        	<a href="#" data-role="button" data-mini="true" data-rel="back" class="themeDark fontLarger-75">Back</a>
        	<a href="#pgSettingsSize" data-role="button" data-mini="true" class="themeDark fontLarger-75">Next</a>
        	<a href="#" data-role="button" data-mini="true" class="themeDark fontLarger-75 nextBtn doneBtn">Done</a>
        </div>
    </div><!-- /pgSettingsColor -->

	<!-- ## pgSettingsSize ################################################## -->
    <div data-role="page" id="pgSettingsSize" class="themeDark">
        <div data-role="header">
        	<h3 class="themeDark fontLarger-1">Size</h3>
        </div><!-- /header -->
        <div data-role="content">
        	<div id="fontSizeBtnHolder" class="alignCenter">
        		<a href="javascript:return -1;" style="font-size: 24pt;" data-fontsize="24" data-role="button" data-mini="true" data-inline="true" class="fontSizeBtn">24</a>
        		<a href="javascript:return -1;" style="font-size: 36pt;" data-fontsize="36" data-role="button" data-mini="true" data-inline="true" class="fontSizeBtn">36</a>
        		<a href="javascript:return -1;" style="font-size: 48pt;" data-fontsize="48" data-role="button" data-mini="true" data-inline="true" class="fontSizeBtn">48</a>
        		<a href="javascript:return -1;" style="font-size: 60pt;" data-fontsize="60" data-role="button" data-mini="true" data-inline="true" class="fontSizeBtn">60</a>
        		<a href="javascript:return -1;" style="font-size: 72pt;" data-fontsize="72" data-role="button" data-mini="true" data-inline="true" class="fontSizeBtn">72</a>
         		<a href="javascript:return -1;" style="font-size: 84pt;" data-fontsize="84" data-role="button" data-mini="true" data-inline="true" class="fontSizeBtn">84</a>
       			<a href="javascript:return -1;" style="font-size: 96pt;" data-fontsize="96" data-role="button" data-mini="true" data-inline="true" class="fontSizeBtn" id="fnt96">96</a>
        		<a href="javascript:return -1;" style="font-size: 108pt;" data-fontsize="108" data-role="button" data-mini="true" data-inline="true" class="fontSizeBtn" id="fnt108">108</a>
        	</div>
        	<div class="divCtrlHolder">
            	<div id="spritzerSize" class="themeDark reset-this" style="position: absolute;"></div>
        	</div>
        	<div class="clear"></div>
        </div><!-- /content -->
        <div data-role="footer" class="alignRight">
        	<a href="#" data-role="button" data-mini="true" id="hdrBack" data-rel="back" class="themeDark fontLarger-75">Back</a>
        	<a href="#pgSettingsSpeed" data-role="button" data-mini="true" id="hdrBack" class="themeDark fontLarger-75 nextBtn">Next</a>
        	<a href="#" data-role="button" data-mini="true" class="themeDark fontLarger-75 nextBtn doneBtn">Done</a>
        </div>
    </div><!-- /pgSettingsSize -->

	<!-- ## pgSettingsSpeed ################################################## -->
    <div data-role="page" id="pgSettingsSpeed" class="themeDark">
        <div data-role="header">
        	<h3 class="themeDark fontLarger-1">Speed</h3>
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
        <div data-role="footer" class="alignRight">
        	<a href="#" data-role="button" data-mini="true" id="hdrBack" data-rel="back" class="themeDark fontLarger-75">Back</a>
        	<a href="#pgSettingsFixation" data-role="button" data-mini="true" id="hdrBack" class="themeDark fontLarger-75 nextBtn">Next</a>
        	<a href="#" data-role="button" data-mini="true" class="themeDark fontLarger-75 nextBtn doneBtn">Done</a>
        </div>
    </div><!-- /pgSettingsSpeed -->

	<!-- ## pgSettingsFixation ################################################## -->
    <div data-role="page" id="pgSettingsFixation" class="themeDark">
        <div data-role="header" id="fixationHeader">
			<h3 class="themeDark fontLarger-1">Fixation</h3>
        </div><!-- /header -->
        <div class="clear"></div>
        <div data-role="content">
           	<div class="divCtrlHolder">
                <img src='images/FixationPoint.png' id="imgFixationPoint" class='fixationPoint' />
            	<div id="spritzerFixation"></div>
        	</div>
        	<div class="clear"></div>
        </div><!-- /content -->
        <div data-role="footer" class="alignRight" id="fixationFooter">
        	<a href="javascript:SpritzHelper.ResetFixation();" data-role="button" data-mini="true" id="hdrReset" class="themeDark fontLarger-75 fixationActionBtn">Reset</a>
        	<a href="javascript:SpritzHelper.NoneFixation();" data-role="button" data-mini="true" id="hdrReset" class="themeDark fontLarger-75 fixationActionBtn">None</a>
        	<a href="#" data-role="button" data-mini="true" id="hdrBack" data-rel="back" class="themeDark fontLarger-75">Back</a>
        	<a href="#" data-role="button" data-mini="true" id="hdrDone" class="themeDark fontLarger-75 nextBtn doneBtn">Done</a>
        </div>
    </div><!-- /pgSettingsFixation -->

	<!-- ## pgBookList ################################################## -->
    <div data-role="page" id="pgBookList" class="themeDark">
        <div data-role="header">
        	<a href="#pgHome" data-role="button" data-mini="true" data-inline="true" id="hdrBack" class="themeDark">Back</a>
        	<h3 class="themeDark fontLarger-1">Book List</h3>
        	<a id='btnSettings' href='#' onclick='javascript:SpritzHelper.GoToSettings("#pgBookList");' data-role='button' data-mini='true' class='themeDark'>Settings</a>
        </div><!-- /header -->
        <div data-role="content">
	        <ul id="lstBooks" data-role="listview" data-filter="true" data-inset="true" data-shadow="false">
			</ul>
        </div><!-- /content -->
    </div><!-- /pgBookList -->

	<!-- ## pgNewsList ################################################## -->
    <div data-role="page" id="pgNewsList" class="themeDark">
        <div data-role="header">
        	<a href="#pgHome" data-role="button" data-mini="true" data-inline="true" id="hdrBack" class="themeDark">Back</a>
        	<h3 class="themeDark fontLarger-1">News List</h3>
        	<a id='btnSettings' href='#' onclick='javascript:SpritzHelper.GoToSettings("#pgNewsList");' data-role='button' data-mini='true' class='themeDark'>Settings</a>
        </div><!-- /header -->
        <div data-role="content">
			<ul data-role="listview" data-inset="true" data-shadow="false">
			  <li data-role="collapsible" data-iconpos="right" data-inset="false">
			    <h2 data-newssource="CNN" class="newsNameLink fontLarger-1 hide">CNN</h2>
			    <ul data-role="listview" data-theme="b">
			    </ul>
			  </li>
			  <li data-role="collapsible" data-iconpos="right" data-inset="false">
			    <h2 data-newssource="REU-TOP" class="newsNameLink fontLarger-1">Reuters - Top News</h2>
			    <ul data-role="listview" data-theme="b">
			    </ul>
			  </li>
			  <li data-role="collapsible" data-iconpos="right" data-inset="false">
			    <h2 data-newssource="REU-BUS" class="newsNameLink fontLarger-1">Reuters - Business News</h2>
			    <ul data-role="listview" data-theme="b">
			    </ul>
			  </li>
			  <li data-role="collapsible" data-iconpos="right" data-inset="false">
			    <h2 data-newssource="REU-MON" class="newsNameLink fontLarger-1">Reuters - Money News</h2>
			    <ul data-role="listview" data-theme="b">
			    </ul>
			  </li>
			  <li data-role="collapsible" data-iconpos="right" data-inset="false">
			    <h2 data-newssource="REU-POL" class="newsNameLink fontLarger-1">Reuters - Political News</h2>
			    <ul data-role="listview" data-theme="b">
			    </ul>
			  </li>
			  <li data-role="collapsible" data-iconpos="right" data-inset="false">
			    <h2 data-newssource="REU-SCI" class="newsNameLink fontLarger-1">Reuters - Science News</h2>
			    <ul data-role="listview" data-theme="b">
			    </ul>
			  </li>
			  <li data-role="collapsible" data-iconpos="right" data-inset="false">
			    <h2 data-newssource="TRM" class="newsNameLink fontLarger-1 hide">The Trumpet</h2>
			    <ul data-role="listview" data-theme="b">
			    </ul>
			  </li>
			</ul>	        
		<div style="height:20px;"></div>
	        <ul id="newsStories" data-role="listview" data-inset="true">
			</ul>
        </div><!-- /content -->
    </div><!-- /pgNewsList -->

	<!-- ## pgNewsRead ################################################## -->
    <div data-role="page" id="pgNewsRead" class="themeDark">
        <div data-role="header">
        	<a href="#pgNewsList" data-role="button" data-mini="true" data-inline="true" id="hdrBack" class="themeDark">Back</a>
        	<h3 class="themeDark fontLarger-1">News</h3>
        	<a id='btnSettings' href='#' onclick='javascript:SpritzHelper.GoToSettings("#pgNewsRead");' data-role='button' data-mini='true' class='themeDark'>Settings</a>
        </div><!-- /header -->
        <div data-role="content">
        	<div class="divCtrlHolder">
                <img src='images/FixationPoint.png' id="imgFixationPoint" class='fixationPoint' />
	            <div id="spritzer" style="position:relative;"></div>
	        
	            <div class="spacer-b alignCenter">
	                <img src='images/Beginning.png' id="btnReset" class='ctrlBtn ctrlBtnSpacerLeft hide'  />
	                <img src='images/Back-Sentence.png' id="btnBackSentence" class='ctrlBtn ctrlBtnSpacerLeft'  />
	                <img src='images/Back-Word.png' id="btnBackWord" class='ctrlBtn'  />
	                <img src='images/Play.png' id="startSpritz" class='ctrlBtn' />
	               	<img src='images/Pause.png' id="togglepauseSpritz" class='ctrlBtn'  />
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

	<!-- ## pgTextPaste ################################################## -->
    <div data-role="page" id="pgTextPaste" class="themeDark">
        <div data-role="header">
        	<a href="#pgHome" data-role="button" data-mini="true" data-inline="true" id="hdrBack" data-rel="back" class="themeDark">Back</a>
        	<h3 class="themeDark fontLarger-1">Text</h3>
        </div><!-- /header -->
        <div data-role="content">
        	<p>Paste text to read below:.</p>
        	<textarea class="fontLarger-2 pastedText" id="pastedText"></textarea>
        </div><!-- /content -->
        <div data-role="footer" class="alignRight">
        	<a href="#pgHome" data-role="button" data-mini="true" id="hdrBack" class="themeDark fontLarger-1">Back</a>
        	<a href="#pgTextRead" data-role="button" data-mini="true" id="hdrTextRead" class="themeDark fontLarger-1 nextBtn">Read</a>
        </div>
    </div><!-- /pgTextPaste -->

	<!-- ## pgTextRead ################################################## -->
    <div data-role="page" id="pgTextRead" class="themeDark">
        <div data-role="header">
        	<a href="#pgNewsList" data-role="button" data-mini="true" data-inline="true" id="hdrBack" class="themeDark">Back</a>
        	<h3 class="themeDark fontLarger-1">News</h3>
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
	            <div id="speedHolder" class=''>
	                <input type="range" name="speedCtrlNews" id="speedCtrlNews" data-highlight="true" min="1" max="1000" value="150" readonly="readonly">
	            </div>
            </div>
        </div><!-- /content -->
    </div><!-- /pgTextRead -->

	<!-- ## pgAbout ################################################## -->
    <div data-role="page" id="pgAbout">
        <div data-role="header">
        	<a href="#pgHome" data-role="button" data-mini="true" id="hdrBack" data-rel="back">Back</a>
        	<h3>About</h3>
        </div><!-- /header -->
        <div data-role="content" class="bigText">
        	<p>We're all about making things easy.</p>
        	<p>And we want reading to be easy for you, too.</p>
        </div><!-- /content -->
    </div><!-- /pgAbout -->


</body>
</html>