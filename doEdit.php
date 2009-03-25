<?php
require('lib/misc.inc.php');
require('lib/classes.class.php');
if(!isset($_SESSION['uid'])) {
	die('Sorry, you need to be logged in to edit pages');
}
if($_POST['address'] == '' || $_POST['source'] == '') {
	die('Sorry, there was an error recieving all the fields');
}
$s = new Site($_POST['address']);
if(!$s->loaded) {
	$s->checkWWW();
}
if($s->lock) {
	print "Sorry, this page is locked";
	die();
}
if(!$s->loaded) {
	$s->save();
}

$ip = $_SERVER['REMOTE_ADDR'];
$rating = 0;
$comment = htmlentities($_POST['comment']);

$source = str_replace("\r", '', WikiWideWebize($s, getSource($_POST['address'])));
$source = str_replace(" />", ">", $source);
$nsource = str_replace("\r", '', stripslashes($_POST['source']));
$nsource = str_replace(" />", ">", $nsource);
$nsource = preg_replace("/<\/?([A-Z]+)[^>]*>/Ue", "strtolower('$1')", $nsource);
$source = preg_replace("/<\/?([A-Z]+)[^>]*>/Ue", "strtolower('$1')", $source);

$source2 = explode("\n", $source);
$source = explode("\n", entityScrub($source));
$nsource = explode("\n", $nsource);

$diff = array_diff($nsource, $source);
$diff2 = array_diff($source, $nsource);

if(current($diff2)) {
	$temp = new Diff();
	$temp->uid = $_SESSION['uid'];
	$temp->sid = $s->sid;
	$temp->comment = $comment;
	$temp->toreplace = $source2[key($diff2)];
	$temp->replacewith = $nsource[key($diff2)];
	$temp->ip = $ip;
	$temp->rating = $rating;
	$temp->commitChanges();
}

while(next($diff2)) {
	$temp = new Diff();
	$temp->sid = $s->sid;
	$temp->comment = $comment;
	$temp->uid = $_SESSION['uid'];
	$temp->toreplace = $source2[key($diff2)];
	$temp->replacewith = $nsource[key($diff2)];
	$temp->ip = $ip;
	$temp->rating = $rating;
	$temp->commitChanges();
}

$diff = array_diff_key($diff, $diff2);

if (current($diff)) {
	$temp = new Diff();
	$temp->uid = $_SESSION['uid'];
	$temp->sid = $s->sid;
	$temp->comment = $comment;
	$temp->toreplace = $source2[key($diff) - 1];
	$temp->ip = $ip;
	$temp->rating = $rating;
	$temp->replacewith = $source2[key($diff) - 1]."\n".$nsource[key($diff)];
	$temp->commitChanges();
}

while(next($diff)) {
	$temp = new Diff();
	$temp->sid = $s->sid;
	$temp->comment = $comment;
	$temp->uid = $_SESSION['uid'];
	$temp->toreplace = $source2[key($diff) - 1];
	$temp->ip = $ip;
	$temp->rating = $rating;
	$temp->replacewith = $source2[key($diff) - 1]."\n".$nsource[key($diff)];
	$temp->commitChanges();
}
?>
<html>

<body onload="window.close();">
</body>
</html>
