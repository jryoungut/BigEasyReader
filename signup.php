<?php

$err = 0;
$errMsg = "";
$errClass = "";

if (isset( $_GET['e'])){
	$err = $_GET['e'];
}

switch($err){
	case 1:
		$errMsg = "This email address is already registered.";
		$errClass = "messageError";
		break;
	case 2:
		$errMsg = "Failed to create account.  Please try again.";
		$errClass = "messageError";
		break;
	default:
		$errMsg = "Please fill out all fields.";
		$errClass = "messageSuccess";
		break;
}
?>
<HTML>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up</title>
	<?php include("_jqueryPartial.php"); ?>

    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/my.css">

    <script type="text/javascript">
	var IsEmailValid = false;
	var IsPasswordValid = false;

	$(document).ready(function() {
		$('#myemail').on('keyup blur change paste',function(){
			var self = this;
			setTimeout(function(){
				var isValid = ($(self).val().length === 0 || IsEmail($(self).val()));
				if(isValid === true){
					$('#messageEmail').html('');
					$('#messageEmail').hide("fade");
					IsEmailValid = isValid;
				}
				else {
					$('#messageEmail').html('A valid email address is required.');
					$('#messageEmail').show("fade");
				}
			}, 0);
		});

		$('#messageEmail').hide();

		$('#mypassword2').keyup(function(){
			IsPasswordValid = ($(this).val().length === 0 || $(this).val() === $('#mypassword').val());
			if(IsPasswordValid === true){
				$('#messageConfirm').html('');
				$('#messageConfirm').hide();
			}
			else {
				$('#messageConfirm').html('Password fields must match.');
				$('#messageConfirm').show();
			}
		});

		$('#messageEmail').hide();
		$('#messageConfirm').hide();
		$('#messageForm').hide();

		$('#mypassword').keyup(function(){
			PasswordStrength($(this).val());
		});
		
		$('#mybirthdate').datepicker({
			changeMonth: true,
      		changeYear: true,
      		yearRange: '1900:2014'
		});

		$('#btnOKSignUp').on('click', function(){
			//Is form valid?
			var errMsgForm = new Array();
			if(IsEmailValid === false){
				errMsgForm.push('You must enter a valid email address.');
			}
			if(IsPasswordValid === false){
				errMsgForm.push('Your passwords must match.');
			}

			if(errMsgForm.length > 0){
				var msgStr = "<ul>";
				for(var i = 0; i < errMsgForm.length; i++){
					msgStr += "<li>" + errMsgForm[i] + "</li>";
				}
				msgStr += "</ul>";
				$('#messageForm').html(msgStr);
				$('#messageForm').show();
			}
			else{
				$('#messageForm').html("");
				$('#messageForm').hide();
				//Submit form
				$('#signupForm').submit();
			}
		});
	});

	function IsEmail(email) {
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		return regex.test(email);
	}

	function PasswordStrength(password)
	{
		var desc = new Array();
		desc[0] = "Very Weak";
		desc[1] = "Weak";
		desc[2] = "Better";
		desc[3] = "Medium";
		desc[4] = "Strong";
		desc[5] = "Strongest";

		var score   = 0;

		//if password bigger than 6 give 1 point
		if (password.length > 6) score++;

		//if password has both lower and uppercase characters give 1 point
		if ( ( password.match(/[a-z]/) ) && ( password.match(/[A-Z]/) ) ) score++;

		//if password has at least one number give 1 point
		if (password.match(/\d+/)) score++;

		//if password has at least one special caracther give 1 point
		if ( password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) )	score++;

		//if password bigger than 12 give another 1 point
		if (password.length > 12) score++;

		document.getElementById("passwordDescription").innerHTML = desc[score];
		document.getElementById("passwordStrength").className = "strength" + score;
	}
</script>
</head>
<body>
    <div data-role="page">
        <div data-role="header">
        	<a id="btnHome" href="login.php" data-role="button" data-mini="true" data-ajax="false">Back</a>
	        <h3>Big Easy Reader</h3>
        </div><!-- /header -->
        <div data-role="content" data-inset="true">
	        <div class="subTitle">
	        	<h3>Sign Up</h3>
	        </div>
			<form id="signupForm" action="checksignup.php" method="post" data-ajax="false">
				<div id="messageError" class="<?php echo($errClass); ?>"><?php echo($errMsg);  ?></div>
				<label>Email:</label>
				<input type="text" name="myemail" id="myemail"/>
				<div id="messageEmail"></div>
				<br />
				<label>Password:</label>
				<input type="password" name="mypassword" id="mypassword"/>
				<p>
					<div id="passwordDescription">Password not entered</div>
					<div id="passwordStrength" class="strength0"></div>
				</p>

				<br/>
				<label>Confirm password:</label>
				<input type="password" name="mypassword2" id="mypassword2"/>
				<div id="messageConfirm"></div>
				<br/>
				<label>First name:</label>
				<input type="text" name="myfirstname" id="myfirstname"/>
				<br/>
				<label>Last name:</label>
				<input type="text" name="mylastname" id="mylastname"/>
				<br/>
				<label>Birth date:</label>
				<input type="text" name="mybirthdate" id="mybirthdate" />
				<br/>
				<div id="messageForm"></div>
				<div class="alignCenter">
					<a href="#" id="btnOKSignUp" data-role="button" data-mini="true" data-inline="true">OK, Sign Me Up</a>
				</div>
			</form>
		</div>
	</div>
</body>
</html>