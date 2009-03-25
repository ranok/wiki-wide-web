<?php
/*
*   about.php - About WikiWW
*   PHP by: Jacob I. Torrey
*   Design by: Jacob I. Torrey
*/
include('lib/misc.inc.php');
include('lib/classes.class.php');
include('lib/cache.lib.php');

$c = new Cache();
$c->startCache(60*60);

print statichtmlheader();
?>
</head><body>
	<div id="title">
		<h1>About Wiki Wide Web</h1>
	</div>
	<div id="central">
	  	<?php print menu(); ?>	
	<p class="indent">&bull; Wiki Wide Web is a revolutionary system that puts users in control of the content of the Internet. It allows for users to modify the content, look-and-feel and even the organization of any website. While Web 2.0 lets users comment and tag websites, Wiki Wide Web lets users interact with previously immutable pages in a while new way.<br /></p>
		<p class="indent">&bull; Some common uses for Wiki Wide Web are:</p><ul><li>Updating content to reflect current trends or new versions of software or hardware (i.e. updating a tutorial for an outdated piece of software)</li><li>Giving feedback on a product or web site</li><li>Showing alternate layouts or ways to do things on web development projects</li></ul>
		<p class="indent">&bull; Wiki Wide Web is also an interesting experiment to see what would happen if the web were truly free. Along with the development of the Wiki Wide Web software, the development team is performing a series of anonymous statistics that can viewed on <a href="/statistics">our Statistics page</a>.</p>

		<p class="bold">About the Wiki Wide Web Team</p>
		<ul><li><p class="indent"><span class="bold">Jacob Torrey (ranok) - Owner and Lead Programmer</span><br /><span class="margin">Jacob is a self taught programmer who is a second year computer science major at <a href="http://www.clarkson.edu">Clarkson University</a>. He enjoys canoeing, hiking, skiing, sailing, amateur radio, spending time with his girlfriend and learning about computers.</span></p></li>
		<li><p class="indent"><span class="bold">
		Keegan Lowenstein (lowensk) - Lead Designer</span><br /><span class="margin">Keegan is a second year computer science student at <a href="http://www.clarkson.edu">Clarkson University</a>, he enjoys making gorgeous websites, snowboarding and playing guitar.
		</span></p></li>
		<li><p class="indent"><span class="bold">
		Ryan Lewis (cosmotron) - Programmer</span><br /><span class="margin">Ryan is a third year computer science student at <a href="http://www.clarkson.edu">Clarkson University</a>, he enjoys programming, joking around and pwning n00bs.
		</span></p></li>
		<li><p class="indent"><span class="bold">
		Jon Rossi (pfald) - Designer</span><br /><span class="margin">Jon is a second year student at <a href="http://www.wesleyan.edu/">Wesleyan University</a>, he enjoys drawing, goofing off and pwning n00bs.
		</span></p></li>
		<li><p class="indent"><span class="bold">
		Max Edmands (fireballmage) - Designer</span><br /><span class="margin">Max is a third year student at <a href="http://www.clarkson.edu/">Clarkson University</a>, he enjoys pina coladas, reading, getting caught in the rain and the feel of the ocean.
		</span></p></li>
		</ul>
		
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
