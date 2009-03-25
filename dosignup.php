<?php
require('lib/misc.inc.php');
require('lib/classes.class.php');
if($_POST['username'] == '' || $_POST['password'] == '' || $_POST['email'] == '') {
    $out = "Sorry, it seems that you didn't fill out all the fields, please go back and try again";
} else {
    $user = new User();
    if(1) {
    	$status = $user->newUser($_POST['username'], $_POST['password'], $_POST['email']);
    	switch($status) {
    	    case -1:
    	        $out = "That username has been taken, please go back and choose another";
    	        break;
    	    case 0:
    	        header('Location: index.php');
    	        die();
    	        break;
    	    default:
    	        break;
    	}
    } else {
    	$out = 'Sorry, invalid invite code';
    }
}
?>
    <body>
    <?php print $out; ?>
    </body>
</html>
