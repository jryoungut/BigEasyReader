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
    <title>Color Settings - Big EZ Reader</title>
    
	<?php include("_jqueryPartial.php"); ?>
	
	<script type="text/javascript">
		var IsLoggedIn =<?php echo($user_check) ?>;
		var ColorTheme = '<?php echo($settingColorValue) ?>';
		var ColorThemeID = '<?php echo($settingColor) ?>';
	
		$(document).on('pageinit', '#pgColors', function(){       
			$('#btnDark').on('click', function(){
				ColorTheme = "Dark Theme";
				ColorThemeID = 1;
				SetThemeColors();
				SaveThemeColors();
			});
			$('#btnLight').on('click', function(){
				ColorTheme = "Light Theme";
				ColorThemeID = 2;
				SetThemeColors();
				SaveThemeColors();
			});
			
			SetThemeColors();
		});

		function SetThemeColors(){
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
		}
		
		function SaveThemeColors(){
	    	var dataString = {
	    		"settingID" : "1",
	    		"settingValue" : String(ColorThemeID)
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
    <div data-role="page" id="pgColors">
        <div data-role="header">
        	<?php
			if($user_check > 0)
			{
				echo("<a id='btnBack' href='settingsMain.php' data-role='button' data-mini='true' data-ajax='false'>Settings</a>");
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
</body>
</html>
