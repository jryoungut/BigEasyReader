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
    
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.0/jquery.mobile-1.4.0.min.css" />
	
    <script type="text/javascript" src="//sdk.spritzinc.com/jQuery/jquery-2.1.0.min.js"></script>
    <script type="text/javascript" src="//sdk.spritzinc.com/js/1.2/js/spritz.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/mobile/1.4.0/jquery.mobile-1.4.0.min.js"></script>
    <script type="text/javascript" src="scripts/modernizr-2.0.6.js"></script>

    <link rel="stylesheet" type="text/css" href="css/ezBigRead.css">

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
			$("#frmLogin").submit();
		}
</script>
</head>
<body>
    <div data-role="page">
        <div data-role="header">
        	<a id="btnHome" href="pages.php" data-role="button" data-mini="true" class="hide">Home</a>
	        <h3>Big Easy Reader</h3>
        </div><!-- /header -->
        <div data-role="content" data-inset="true">
	        <div class="subTitle">
	        	<h3>Log In</h3>
	        </div>
			<form id="frmLogin" action="checklogin.php" method="post" data-ajax="false">
				<div id="messageError" class="<?php echo($errClass); ?> hide"><?php echo($errMsg);  ?></div>
				<label>Email :</label>
				<input type="text" name="myemail" id="myemail"/>
				<div id="messageEmail"></div>
				<br />
				<label>Password :</label>
				<input type="password" name="mypassword" id="mypassword"/><br/>
				<div class="alignCenter">
					<a id="btnLogInPage" href="" onclick='DoLogin();' data-inline='true' data-role='button' data-mini='true'>Login</a>
					<label>&nbsp;or&nbsp;</label>
					<a id="btnSignUpPage" href="signup.php" data-inline='true' data-role='button' data-mini='true' data-ajax="false">Sign Up</a>
				</div>
			</form>
		</div>
	</div>
</body>
</html>