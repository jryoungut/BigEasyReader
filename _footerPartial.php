<?php



?>
<div data-role="footer" class="themeDark">
    <h4 id="txtFooterTitle">Experimentation Beta.  Try it.  Enjoy it.</h4>
    <div class="alignCenter">
    <?php
	if($user_check > 0)
	{
    	echo('<a id="btnFeedback" href="feedback.php" data-role="button" data-inline="true" data-ajax="false" class="themeDark hide">Feedback</a>');
	}
    ?>
    	<a id="btnAboutUs" href="#pgAbout" data-role="button" data-inline="true" data-ajax="false" class="themeDark">About</a>
    	<a id="btnInstructions" href="javascript:return !1;" data-role="button" data-inline="true" data-ajax="false" class="themeDark hide">How It Works</a>
    	<div class="fontSmaller-3 alignCenter">&copy; 2014, Zayco Unlimited</div>
	</div>
</div><!-- /footer -->
