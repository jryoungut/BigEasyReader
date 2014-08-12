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

    <script type="text/javascript">
	var IsEmailValid = false;
	var IsPasswordValid = false;

	$(document).ready(function() {
		$('#myemail2').on('keyup blur change paste',function(){
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
			IsPasswordValid = ($(this).val().length === 0 || $(this).val() === $('#mypassword3').val());
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

		$('#mypassword3').keyup(function(){
			PasswordStrength($(this).val());
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
			if($.trim($('#myfirstname2').val()).length === 0){
				errMsgForm.push('Please enter your first name.');
			}
			if($.trim($('#mylastname2').val()).length === 0){
				errMsgForm.push('Please enter your last name.');
			}
			if($.trim($('#mybirthmonth').val()).length === 0 && (!parseInt($('#mybirthmonth').val()) >= 1 && !parseInt($('#mybirthmonth').val()) <= 12)){
				errMsgForm.push('The birth month must be a number between 1 and 12.');
			}
			var d = new Date();
			var y = isNaN(parseInt($('#mybirthyear').val())) === true ? 0 : parseInt($('#mybirthyear').val());
			if($.trim($('#mybirthyear').val()).length === 0 && (y < 1900 || y > d.getFullYear())){
				errMsgForm.push('The birth year must be a number between 1900 and ' + d.getFullYear() + '.');
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
				DoSignUpSubmit();
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
	
	function DoSignUpSubmit(){
		$('#myemail').val($('#myemail2').val());
		$('#mypassword').val($('#mypassword3').val());
		$('#myfirstname').val($('#myfirstname2').val());
		$('#mylastname').val($('#mylastname2').val());
		$('#mybirthdate').val($('#mybirthmonth').val() + '/1/' + $('#mybirthyear').val());
		$("#signupForm").submit();
	}

</script>
</head>
<body>
    <div data-role="page" id="pgSignup-1" class="themeDark">
        <div data-role="header">
	        <h3>Big Easy Reader - Sign Up</h3>
        </div><!-- /header -->
        <div data-role="content" data-inset="true">
	        <!-- <div class="subTitle alignCenter">
	        	<h3>1. Email</h3>
	        </div> -->
	        <div id="messageError" class="<?php echo($errClass); ?>"><?php echo($errMsg);  ?></div>
			<label class="fontLarger-1">Email:</label>
			<input type="text" name="myemail2" id="myemail2" class="fontLarger-1"/>
			<div id="messageEmail"></div>
		</div>
        <div data-role="footerFloating" class="alignRight">
        	<a href="index.php" data-role="button" data-inline="true" data-ajax="false" class="themeDark fontLarger-1">Back</a>
        	<a href="#pgSignup-2" data-role="button" data-inline="true" class="themeDark fontLarger-1 nextBtn">Next</a>
        </div>
	</div>
	
    <div data-role="page" id="pgSignup-2" class="themeDark">
        <div data-role="header">
	        <h3>Big Easy Reader - Sign Up</h3>
        </div><!-- /header -->
        <div data-role="content" data-inset="true">
	        <!-- <div class="subTitle alignCenter">
	        	<h3>2. Password</h3>
	        </div> -->
			<label class="fontLarger-1">Password:</label>
			<input type="password" name="mypassword3" id="mypassword3" class="fontLarger-1"/>
			<p>
				<div id="passwordDescription">Password not entered</div>
				<div id="passwordStrength" class="strength0"></div>
			</p>

			<br/>
			<label class="fontLarger-1">Confirm password:</label>
			<input type="password" name="mypassword2" id="mypassword2" class="fontLarger-1"/>
			<div id="messageConfirm"></div>
		</div>
        <div data-role="footerFloating" class="alignRight">
        	<a href="#" data-role="button" data-inline="true" data-rel="back" class="themeDark fontLarger-1">Back</a>
        	<a href="#pgSignup-3" data-role="button" data-inline="true" class="themeDark fontLarger-1 nextBtn">Next</a>
        </div>
	</div>
	
    <div data-role="page" id="pgSignup-3" class="themeDark">
        <div data-role="header">
	        <h3>Big Easy Reader - Sign Up</h3>
        </div><!-- /header -->
        <div data-role="content" data-inset="true">
	        <!-- <div class="subTitle alignCenter">
	        	<h3>3. First Name</h3>
	        </div> -->
			<label class="fontLarger-1">First name:</label>
			<input type="text" name="myfirstname2" id="myfirstname2" class="fontLarger-1"/>
		</div>
        <div data-role="footerFloating" class="alignRight">
        	<a href="#" data-role="button" data-inline="true" data-rel="back" class="themeDark fontLarger-1">Back</a>
        	<a href="#pgSignup-4" data-role="button" data-inline="true" class="themeDark fontLarger-1 nextBtn">Next</a>
        </div>
	</div>
	
    <div data-role="page" id="pgSignup-4" class="themeDark">
        <div data-role="header">
	        <h3>Big Easy Reader - Sign Up</h3>
        </div><!-- /header -->
        <div data-role="content" data-inset="true">
	        <!-- <div class="subTitle alignCenter">
	        	<h3>4. Last Name</h3>
	        </div> -->
			<label class="fontLarger-1">Last name:</label>
			<input type="text" name="mylastname2" id="mylastname2" class="fontLarger-1"/>
			<br/>
		</div>
        <div data-role="footerFloating" class="alignRight">
        	<a href="#" data-role="button" data-inline="true" data-rel="back" class="themeDark fontLarger-1">Back</a>
        	<a href="#pgSignup-5" data-role="button" data-inline="true" class="themeDark fontLarger-1 nextBtn">Next</a>
        </div>
	</div>
	
    <div data-role="page" id="pgSignup-5" class="themeDark">
        <div data-role="header">
	        <h3>Big Easy Reader - Sign Up</h3>
        </div><!-- /header -->
        <div data-role="content" data-inset="true">
	        <!-- <div class="subTitle alignCenter">
	        	<h3>5. Birth Date</h3>
	        </div> -->
	        <div style="float:left; width:40%; margin-right:20px;">
				<label class="fontLarger-1">Birth month:</label>
				<input type="text" name="mybirthmonth" id="mybirthmonth" data-inline="true" class="fontLarger-1" placeholder="1-12" />
	        </div>
	        <div style="float:left; width:40%;">
				<label class="fontLarger-1">Birth year:</label>
				<input type="text" name="mybirthyear" id="mybirthyear" data-inline="true" class="fontLarger-1" placeholder="1900 - 2014" />
	        </div>
			<br/>
			<div id="messageForm" class="clear"></div>
		</div>
        <div data-role="footerFloating" class="alignRight">
        	<a href="#" data-role="button" data-inline="true" data-rel="back" class="themeDark fontLarger-1">Back</a>
        	<a href="#" id="btnOKSignUp" data-role="button" data-inline="true" class="themeDark fontLarger-1 nextBtn">Sign Up</a>
        </div>
	</div>
	
	
	<form id="signupForm" action="checksignup.php" method="post" data-ajax="false" class="hide">
		<input type="text" name="myemail" id="myemail"/>
		<input type="password" name="mypassword" id="mypassword"/>
		<input type="text" name="myfirstname" id="myfirstname"/>
		<input type="text" name="mylastname" id="mylastname"/>
		<input type="text" name="mybirthdate" id="mybirthdate" />
	</form>
	
</body>
</html>