<?php
session_start();
include('../lib/classes.class.php');
if(isset($_GET['fusername'])) {
	$user = unserialize($_SESSION['userdata']);
	$status = $user->requestFriend($_GET['fusername']);
	switch($status) {
		case 0:
			print "Friend successfully added!";
			break;
		case -1:
			print "That username doesn't exist!";
			break;
		default:
			print "Some error occurred!";
			break;
	}
} else {
	print "No username supplied";
}
?>
