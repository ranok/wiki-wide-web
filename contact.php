<?php
/*
*   contact.php - WikiWW dev contact information
*   PHP by: Jacob I. Torrey
*   Design by: Jacob I. Torrey
*/
include('lib/misc.inc.php');
include('lib/classes.class.php');
include('lib/cache.lib.php');

$c = new Cache();
$c->startCache(60*60);
?>
  </head><body>
    	
	<div id="title">
		<h1>Contact Us</h1>
		</div><div id="central">
		    	<?php print menu(); ?>


		<p class="indent">&bull; By Email:<br />The Wiki Wide Web development team's email is: developers [at] wikiwideweb [dot] net, please only use this for either business or personal contact.</p>
		<p class="indent">&bull; Reporting Bugs or Feature Requests:<br />Please send as much information as possible regarding the bug or feature to bugs [at] wikiwideweb [dot] net.</p>
		<p class="indent">&bull; Real-Time Chat:<br />Wiki Wide Web has its official IRC channel at irc.cyberarmy.net #wikiwideweb, come join us and we'll hopefully answer any questions and just have a good time.</p>
	</div>
	
	
	<div id="footer"><form class="center" action="https://www.paypal.com/cgi-bin/webscr" method="post"><div>
<input type="hidden" name="cmd" value="_s-xclick" />
<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" name="submit" alt="Make payments with PayPal - it's fast, free and secure!" />
<img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
" /></div>
</form>

<p class="center">&copy; 2007 JIT Web Solutions</p></div>
</body>
</html>
<?php $c->endCache(); ?>
