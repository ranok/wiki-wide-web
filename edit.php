<?php
require('lib/misc.inc.php');
require('lib/classes.class.php');
print statichtmlheader()."\r\n<body id=\"edit\">";
if(!isset($_GET['address'])) {
    print 'Sorry, you need to enter an address to edit</body></html>';
    die();
}
$s = new Site($_GET['address']);
if(!$s->loaded) {
	$s->checkWWW();
}
if($s->lock) {
	print "Sorry, this page is locked</body></html>";
	die();
}
?>
<h1 class="center">Edit this page</h1>
<?php
if(isset($_SESSION['userdata'])) {
$source = WikiWideWebize($s, getSource($_GET['address']));
?>

<form action="/doedit" method="post">
<input type="hidden" name="address" value="<?php print $_GET['address']; ?>" />
<textarea rows="15" cols="80" name="source"><?php print $source; ?></textarea><br />
Comment: <input type="text" name="comment" />
<p class="center"><input type="submit" value="Make Changes" /></p>
</form>
<?php
} else {
?>
Please login to edit this page:
<div id="user"><form action="/login?back=1" method="post">Username:
        <input type="text" name="username" class="text" /><br />Password:
        <input type="password" name="password" class="text" /><br />
        <input type="submit" value="Login" class="submit" /></form></div>
<?php
}
?>
</body></html>
