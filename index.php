<?php
/*
*   index.php - Main entry point to WikiWW
*   PHP by: Jacob I. Torrey
*   Design by: Max Edmands
*/
include('lib/misc.inc.php');
include('lib/classes.class.php');
include('lib/cache.lib.php');

$c = new Cache();

print statichtmlheader();
?>
</head><body>
    	<div id="title">
		<h1>Wiki Wide Web</h1>
	</div>
	<div id="central">
		<div id="navbar">
			<ul>
				<li><a href="/">Home</a></li>
				<li><a href="/about">About</a></li>
				<li><a href="/download">Download</a></li>
				<li><a href="/statistics">Statistics</a></li>
				<li><a href="/contact">Contact</a></li>
			</ul>
		</div>
		<div id="main">
			<div id="warning">
				<img src="images/beta_medal.png" alt="still in beta" />
				<h2>Warning:</h2>
				<p>This project is still in beta. Use it at your own risk. If you'd like to help develop for it, please let us know by using the information in the contact page.</p>
				<hr />
			</div>
			<div id="important_links">
    	<div id="login">
    	<?php
    	if(isset($_COOKIE['remember']) && !isset($_SESSION['username'])) {
    		$parse = $_COOKIE['remember'];
    		$uid = substr($parse, 0, strpos($parse, '.'));
    		$md5 = substr($parse, strpos($parse, '.') + 1);
    		if(md5("s3cr3t".$uid) == $md5) {
    			$u = new User();
    			$u->populateUser($uid);
    			$_SESSION['uid'] = $uid;
    			$_SESSION['username'] = $u->username;
    			$_SESSION['userdata'] = serialize($u);
    			header('Location: index.php');
    			die();
    		}
      } elseif(!isset($_SESSION['username'])) {
    ?>
        <form action="login" method="post" accept-charset="utf-8">
						<div class="form_entry">
							<label for="username">usernm</label><input type="text" name="username" value="" id="username">
						</div>
						<div class="form_entry">
							<label for="password">passwd</label><input type="password" name="password" value="" id="password">
						</div>
						<label for="remember">remember me</label><input type="checkbox" name="remember" value="" id="remember">
						<input type="submit" name="login" value="Log in!" id="login">
						<button id="signupbutton" onclick="javascript:showSignUp();">Signup!</button>
					</form>
    <?php
        } else {
    ?>
        <p class="bold center">Welcome back <?php print $_SESSION['username']; ?><br /><a href="logout">Logout</a></p>
    <?php
    	}
    ?>
    	</div>
    	<div id="plugin_download"><p class="center bold big">Download The Extension</p><p class="bold center">Just click <a href="wikiwideweb.xpi">here</a> to download and install the extension for Firefox.</p>
    	</div>
    	<div id="news"><p class="center bold big">Wiki Wide Web News</p>
    		<?php $c->call(60*10, 'printNews', 5); ?>
    	</div>
    	<div id="faq">
	<?php
		if(!isset($_SESSION['username']) || !isset($_SESSION['userdata'])) {
	?>
    	<p class="center bold big">Wiki Wide Web FAQ</p>
	<p><span class="bold margin">&bull; What is Wiki Wide Web</span> - Wiki Wide Web puts you in control of the content on the Internet. It allows for 100% creativity and the power to think outside of the box. You now have the ability to edit any website! Some screenshots are viewable <a href="./screenshots/">here</a> is you want to see how powerful Wiki Wide Web is (please take into account that this is a very early version of Wiki Wide Web).</p>
	<p><span class="bold margin">&bull; How do I get/use it</span> - If you're using Firefox, then great, all you need to do is <a href="wikiwideweb.xpi">install the Wiki Wide Web extension</a> and you're good to go! If you're using another browser, either download and install <a class="white" href="http://www.firefox.com">Firefox</a>, or wait until the Internet Explorer plugin arrives (late 2007).</p>
	<p><span class="bold margin">&bull; How much does it cost</span> - Nothing! If you like it, and want to help support development, please feel free to donate using the Paypal button below.</p>
	<p><span class="bold margin">&bull; How do I get in touch with you</span> - You can join us on IRC, irc.cyberarmy.net channel #wikiwideweb.</p>
	<?php
		} else {
	?>
	<p class="center bold big">Your Friends:</p>
        <?php
        	$user = unserialize($_SESSION['userdata']);
        	$friends = $user->getFriends();
        	for($i = 0; $i < count($friends); $i++) {
        		print "&bull; <a href=\"user/".$friends[$i]->username."\">{$friends[$i]->username}</a><br />";
        	}
        	if(count($friends) == 0) {
        		print "Sorry, you don't have any friends yet :(<br />";
        	}
        	
        	
        ?>
   	<input type="text" id="friendusername" />
   	<button onclick="addFriend();">Add as a friend</button><br />
        <span id="friendstatus"></span><br />
        
        <?php 
        $requests = $user->getRequests();
        if(count($requests) > 0) {
        	print '<p class="center bold big">Friend Requests:</p>';
        	for($i = 0; $i < count($requests); $i++) {
        		print $requests[0]->username;
        	}
        }
        ?>
        <?php
        	}
        ?>
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
