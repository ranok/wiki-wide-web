<?php
include('lib/misc.inc.php');
include('lib/classes.class.php');

if(isset($_SESSION['uid'])) {
	$user = unserialize($_SESSION['userdata']);
	$user->logout();
}
header('Location: ./');
die();
?>
