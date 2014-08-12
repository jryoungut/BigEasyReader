<?php

$err = 0;
$errMsg = "";
$errClass = "";

if (isset( $_GET['e'])){
	$err = $_GET['e'];
}

switch($err){
	case 1:
		$errMsg = "Wrong email address or password.  Please try again, or sign up.";
		$errClass = "messageError";
		break;
	case 2:
		$errMsg = "Thank you for signing up. Please log in.";
		$errClass = "messageSuccess";
		break;
	case 3:
		$errMsg = "You must be logged in to create an event. Please log in.";
		$errClass = "messageError";
		break;
	case 4:
		$errMsg = "Please log in.";
		$errClass = "messageError";
		break;
	default:
		$errMsg = "Please enter your email address and password to log in.";
		$errClass = "messageSuccess";
		break;
}
?>
<HTML>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log In</title>
    
	<?php include("_jqueryPartial.php"); ?>

    <script type="text/javascript">
		$(document).on('pageinit', function() {
			//alert("hi");
		});
		$(document).ready(function() {
			$('#myemail').on('keyup blur change paste focus',function(){
				var self = this;
				setTimeout(function(){
					var isValid = ($(this).val().length === 0 || IsEmail($(this).val()));
					if(isValid === true){
						$('#messageEmail').html('');
						$('#messageEmail').hide("fade");
					}
					else {
						$('#messageEmail').html('A valid email address is required.');
						$('#messageEmail').show("fade");
					}
				}, 0);
			});

			$('#messageEmail').hide();
		});

		function IsEmail(email) {
			var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			return regex.test(email);
		}

		function DoLogin(){
			$('#myemail').val($('#myemail2').val());
			$('#mypassword').val($('#mypassword2').val());
			$("#frmLogin").submit();
		}
</script>
</head>
<body>
    <div data-role="page" id="pgLogin1" class="themeDark">
        <div data-role="header">
	        <h3>Big Easy Reader - Log In</h3>
        </div><!-- /header -->
        <div data-role="content" data-inset="true">
	        <div class="subTitle alignCenter">
	        	<span class="themeDark fontLarger-1">1. Log In Now, or... </span>
	        	<a id="btnSignUpPage" href="signup.php" class="fontLarger-1" data-inline='true' data-role='button' data-mini='true' data-ajax="false">Sign Up</a>
	        </div>
			<div id="messageError" class="<?php echo($errClass); ?>"><?php echo($errMsg);  ?></div>
			<label class="fontLarger-1">Email:</label>
			<input type="text" name="myemail2" id="myemail2" class="fontLarger-2"/>
			<div id="messageEmail"></div>
		</div>
        <div data-role="footer" class="alignRight">
        	<a href="index.php" data-role="button" data-mini="true" data-ajax="false" class="themeDark fontLarger-1">Back</a>
        	<a href="#pgLogin2" data-role="button" data-mini="true" class="themeDark fontLarger-1 nextBtn">Next</a>
        </div>
	</div>
	
    <div data-role="page" id="pgLogin2" class="themeDark">
        <div data-role="header">
        	<a id="btnHome" href="index.php" data-role="button" data-mini="true" class="hide">Home</a>
	        <h3>Big Easy Reader - Log In</h3>
        </div><!-- /header -->
        <div data-role="content" data-inset="true">
	        <div class="subTitle alignCenter">
	        	<span class="themeDark fontLarger-1">2. Password</span>
	        </div>
				<label class="fontLarger-1">Password:</label>
				<input type="password" name="mypassword2" id="mypassword2" class="fontLarger-2"/><br/>
		</div>
        <div data-role="footer" class="alignRight">
        	<a href="#" data-role="button" data-mini="true" data-rel="back" class="themeDark fontLarger-1">Back</a>
        	<a href="#" onclick="DoLogin();" data-role="button" data-mini="true" class="themeDark fontLarger-1 nextBtn">Log In</a>
        </div>
	</div>

	<form id="frmLogin" action="checklogin.php" method="post" data-ajax="false" class="hide">
		<input type="text" name="myemail" id="myemail" class="fontLarger-2"/>
		<input type="password" name="mypassword" id="mypassword"/>
	</form>
</body>
</html>