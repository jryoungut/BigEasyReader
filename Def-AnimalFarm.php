<?php
//================================================================
include ("dbConfig.php");
include ("dbOpen.php");
include ("settingsUtilities.php");
//variables
if (session_id() == false) {
	session_start();
}

$displayType = "";
if (isset($_GET['t'])) {
	$displayType = $_GET['t'];
}
$displayData = "";
$user_name = "";
$user_check = 0;
$errClass = "";
$errMsg = "";

if (isset($_SESSION['brUserID'])) {
	$user_check = $_SESSION['brUserID'];
} else {
	//header("location:login.php");
}

//Get logged in user name
$query = "SELECT * FROM users WHERE ID = '" . $user_check . "'";
$result = $conn -> query($query);
if ($result === false) {
	trigger_error('Wrong SQL: ' . $query . ' Error: ' . $conn -> error, E_USER_ERROR);
} else {
	$rows_returned = $result -> num_rows;
}
$result -> data_seek(0);
while ($row = $result -> fetch_assoc()) {
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
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Animal Farm</title>

        <?php
			include ("_jqueryPartial.php");
		?>

        <script type="text/javascript">var IsLoggedIn =<?php echo($user_check) ?>;
			Settings.ColorTheme = '<?php echo($settingColorValue) ?>';
		    Settings.ColorThemeID = '<?php echo($settingColor) ?>';
		    Settings.Size = '<?php echo($settingSize) ?>';
		    Settings.Speed = '<?php echo($settingSpeed) ?>';
		    Settings.Fixation = '<?php echo($settingFixation) ?>';

			//## pgHome #################################################
			$(document).on('pageinit', '#pgHome', function(){  
				SpritzHelper.SetThemeColors();   
				SpritzHelper.SetFontSize();  
			    $('#speedCtrlBook').on('slidestop', function(event, ui){
			    	Settings.Speed = $(this).val();
			    	$('#spritzer').data('controller').setSpeed(parseInt(Settings.Speed));
			    });
  
				$('#speedCtrlBook').val(parseInt(Settings.Speed)).slider('refresh');
			});

			//## pgHome #################################################
			$(document).on('pagebeforeshow', '#pgHome', function(){  
				SpritzHelper.spritzingState = SpritzHelper.SPRITZ_STATE_RESET;
				SpritzHelper.doBtnUI();
				ContentUrl = 'http://ebooks.adelaide.edu.au/o/orwell/george/o79a/chapter1.html';
				SpritzHelper.SetupSpritzerUI();  
				TheSpritzerControl = $('#spritzer');
				//TheSpritzerControl.data('url', ContentUrl);
				TheSpritzerControl.empty();
	            //SpritzHelper.InitSpritz(false);
	            SpritzHelper.spritzController = new SPRITZ.spritzinc.SpritzerController(CustomOptions);
			    SpritzHelper.spritzController.attach(TheSpritzerControl);
			
			    // Create the BookController instance
			    new SPRITZ.bookmark.BookController(SpritzHelper.spritzController);

				//alert(SpritzClient.isUserLoggedIn());
	            window.setTimeout(function(){SpritzHelper.CenterSpritzerControl(TheSpritzerControl, $('#pgHome'), 4);},100);
	            
	            TheSpritzerControl.data('controller').setSpeed(parseInt(Settings.Speed));
	            $('#speedCtrlBook').val(parseInt(Settings.Speed)).slider('refresh');
	            
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

		    var init = function() {
			    // Customize controller's options
			    var options = {controlButtons: ["rewind", "back", "pauseplay", "forward", "end"]};
			
			    // Attach controller's container to this page's "spritzer" containers
			    var spritzerController = new SPRITZ.spritzinc.SpritzerController(options);
			    spritzerController.attach($("#spritzer"));
			
			    // Create the BookController instance
			    new SPRITZ.bookmark.BookController(spritzerController);
		    };
        </script>
		<?php include_once("_analyticsTracking.php") ?>
    </head>
    <body>
        <div data-role="page" id="pgHome" class="themeDark">
            <div data-role="header">
                <?php
				if ($user_check > 0) {
					echo("<a id='btnBack' href='' data-mini='true' data-ajax='false' data-rel='back' class='themeDark'>Back</a>");
				}
                ?>
                <h3 class="themeDark fontLarger-1">Animal Farm</h3>

            </div><!-- /header -->

            <div data-role="content">
            	<div class="divCtrlHolder">
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
		                <input type="range" name="speedCtrlBook" id="speedCtrlBook" data-highlight="true" min="1" max="1000" value="150" readonly="readonly">
		            </div>
				</div>
				<div class="clear"></div>
                <p>
                    <div class='spritz-book'>
                        <div class='book-title'>
                            <span class='book-name'>Animal Farm</span>, &nbsp;&nbsp; by &nbsp; <font size="5"><strong>George Orwell</strong></font>
                        </div>
                        <div class='book-title'>
                            Total Time:&nbsp;<span class='total-time'></span>
                            &nbsp;&nbsp;&nbsp;Remaining Time:&nbsp;<span class='remaining-time'></span>
                        </div>
                        <br>
                        <div class='book-chapter'
                        data-url="http://ebooks.adelaide.edu.au/o/orwell/george/o79a/chapter1.html"
                        data-selector="p"
                        >
                            <div class='status-image'></div>
                            <span class='chapter-name weightBold'>Chapter 1</span>
                            <span class="weightNormal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Time:&nbsp;</span><span class='total-time'></span>
                            <span class="weightNormal">&nbsp;&nbsp;&nbsp;Remaining Time:&nbsp;</span><span class='remaining-time'></span>
                        </div>
                        <div class='book-chapter'
                        data-url="http://ebooks.adelaide.edu.au/o/orwell/george/o79a/chapter2.html"
                        data-selector="p"
                        >
                            <div class='status-image'></div>
                            <span class='chapter-name weightBold'>Chapter 2</span>
                            <span class="weightNormal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Time:&nbsp;</span><span class='total-time'></span>
                            <span class="weightNormal">&nbsp;&nbsp;&nbsp;Remaining Time:&nbsp;</span><span class='remaining-time'></span>
                        </div>
                        <div class='book-chapter'
                        data-url="http://ebooks.adelaide.edu.au/o/orwell/george/o79a/chapter3.html"
                        data-selector="p"
                        >
                            <div class='status-image'></div>
                            <span class='chapter-name weightBold'>Chapter 3</span>
                            <span class="weightNormal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Time:&nbsp;</span><span class='total-time'></span>
                            <span class="weightNormal">&nbsp;&nbsp;&nbsp;Remaining Time:&nbsp;</span><span class='remaining-time'></span>
                        </div>
                        <div class='book-chapter'
                        data-url="http://ebooks.adelaide.edu.au/o/orwell/george/o79a/chapter4.html"
                        data-selector="p"
                        >
                            <div class='status-image'></div>
                            <span class='chapter-name weightBold'>Chapter 4</span>
                            <span class="weightNormal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Time:&nbsp;</span><span class='total-time'></span>
                            <span class="weightNormal">&nbsp;&nbsp;&nbsp;Remaining Time:&nbsp;</span><span class='remaining-time'></span>
                        </div>
                        <div class='book-chapter'
                        data-url="http://ebooks.adelaide.edu.au/o/orwell/george/o79a/chapter5.html"
                        data-selector="p"
                        >
                            <div class='status-image'></div>
                            <span class='chapter-name weightBold'>Chapter 5</span>
                            <span class="weightNormal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Time:&nbsp;</span><span class='total-time'></span>
                            <span class="weightNormal">&nbsp;&nbsp;&nbsp;Remaining Time:&nbsp;</span><span class='remaining-time'></span>
                        </div>
                        <div class='book-chapter'
                        data-url="http://ebooks.adelaide.edu.au/o/orwell/george/o79a/chapter6.html"
                        data-selector="p"
                        >
                            <div class='status-image'></div>
                            <span class='chapter-name weightBold'>Chapter 6</span>
                            <span class="weightNormal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Time:&nbsp;</span><span class='total-time'></span>
                            <span class="weightNormal">&nbsp;&nbsp;&nbsp;Remaining Time:&nbsp;</span><span class='remaining-time'></span>
                        </div>
                        <div class='book-chapter'
                        data-url="http://ebooks.adelaide.edu.au/o/orwell/george/o79a/chapter7.html"
                        data-selector="p"
                        >
                            <div class='status-image'></div>
                            <span class='chapter-name weightBold'>Chapter 7</span>
                            <span class="weightNormal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Time:&nbsp;</span><span class='total-time'></span>
                            <span class="weightNormal">&nbsp;&nbsp;&nbsp;Remaining Time:&nbsp;</span><span class='remaining-time'></span>
                        </div>
                        <div class='book-chapter'
                        data-url="http://ebooks.adelaide.edu.au/o/orwell/george/o79a/chapter8.html"
                        data-selector="p"
                        >
                            <div class='status-image'></div>
                            <span class='chapter-name weightBold'>Chapter 8</span>
                            <span class="weightNormal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Time:&nbsp;</span><span class='total-time'></span>
                            <span class="weightNormal">&nbsp;&nbsp;&nbsp;Remaining Time:&nbsp;</span><span class='remaining-time'></span>
                        </div>
                        <div class='book-chapter'
                        data-url="http://ebooks.adelaide.edu.au/o/orwell/george/o79a/chapter9.html"
                        data-selector="p"
                        >
                            <div class='status-image'></div>
                            <span class='chapter-name weightBold'>Chapter 9</span>
                            <span class="weightNormal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Time:&nbsp;</span><span class='total-time'></span>
                            <span class="weightNormal">&nbsp;&nbsp;&nbsp;Remaining Time:&nbsp;</span><span class='remaining-time'></span>
                        </div>
                        <div class='book-chapter'
                        data-url="http://ebooks.adelaide.edu.au/o/orwell/george/o79a/chapter10html"
                        data-selector="p"
                        >
                            <div class='status-image'></div>
                            <span class='chapter-name weightBold'>Chapter 10</span>
                            <span class="weightNormal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Time:&nbsp;</span><span class='total-time'></span>
                            <span class="weightNormal">&nbsp;&nbsp;&nbsp;Remaining Time:&nbsp;</span><span class='remaining-time'></span>
                        </div>
                    </div>
                </p>
            </div>
        </div>
    </body>
</html>