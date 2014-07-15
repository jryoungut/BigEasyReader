<?php



?>
<div data-role="footer">
    <h4 id="txtFooterTitle">Experimentation Beta.  Try it.  Enjoy it.</h4>
    <div class="alignCenter">
    <?php
	if($user_check > 0)
	{
    	echo('<a id="btnFeedback" href="feedback.php" data-role="button" data-inline="true" data-ajax="false">Feedback...</a>');
	}
    ?>
    	<a id="btnAboutUs" href="#pgAbout" data-role="button" data-inline="true" data-ajax="false">About...</a>
    	<a id="btnInstructions" href="instructions.php" data-role="button" data-inline="true" data-ajax="false">How It Works...</a>
    	<div class="copywrite alignCenter">&copy; 2014, Zayco Unlimited</div>
	</div>
</div><!-- /footer -->
