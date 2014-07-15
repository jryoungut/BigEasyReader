<?php
include ("dbConfig.php");
include ("dbOpen.php");
include ("settingsUtilities.php");

//################################################################
//variables

if (isset($_SESSION['brUserID'])) {
	$user_check = $_SESSION['brUserID'];
}

//################################################################
//Get Setting for user
$settingsArray = GetSettingsValues($conn);
extract($settingsArray);


//################################################################
//Get logged in user name
$userFullName = GetUserFullName($conn);


include ("dbClose.php");
?>
<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Settings - for Big EZ Reader</title>
	
	<?php include("_jqueryPartial.php"); ?>

	<script type="text/javascript">
		var IsLoggedIn = <?php echo($user_check) ?>;
		var ColorTheme = '<?php echo($settingColorValue) ?>';

        $(document).on('pageinit', '#pgSettings', function() {
        	if(ColorTheme === 'Dark Theme'){
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
        	}
        	else{
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
        	}
        });

	</script>
</head>
<body>
<!-- ###################################################################################### -->
<!-- page -->
    <div data-role="page" id="pgSettings">
        <div data-role="header">
        	<?php
			if ($user_check > 0) {
				echo("<a id='btnBack' href='index.php' data-role='button' data-mini='true' data-ajax='false'>Back</a>");
			}
        	?>
        	<h3>Settings</h3>
        </div><!-- /header -->

        <div data-role="content">
        	<ul data-role="listview" data-inset="true">
			    <li><a href="settingsColor.php" data-ajax='false'>Colors<span class="listAside"><?php echo $settingColorValue; ?></span></a></li>
			    <li><a href="settingsSize.php" data-ajax='false'>Size<span class="listAside"><?php echo $settingSize; ?> %</span></a></li>
			    <li><a href="settingsSpeed.php" data-ajax='false'>Speed<span class="listAside"><?php echo $settingSpeed; ?> wpm</span></a></li>
			    <li><a href="settingsFixation.php" data-ajax='false'>Fixation<span class="listAside"><?php echo $settingFixation; ?></span></a></li>
			</ul>
        </div><!-- /content -->
    </div><!-- /page -->
</body>
</html>
