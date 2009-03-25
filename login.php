<?php
require('lib/misc.inc.php');
require('lib/classes.class.php');
if($_POST['username'] == '' || $_POST['password'] == '') {
    die("Sorry, it seems that you forgot to fill out all the fields.");
} else {
    $user = new User();
    $status = $user->login($_POST['username'], $_POST['password']);
    switch($status) {
        case -1:
            die("Wrong password!");
        case -2:
            die("Username not found");
            break;
        case -3:
            die("Database error, please try again");
            break;
        case 0:
            if($_POST['remember'] == 'on') {
            	setcookie('remember', $_SESSION['uid'].'.'.md5("s3cr3t".$_SESSION['uid']), time()*60*60*24*30);
            }
            break;
        default:
            die("There was some error, please try again");
            break;
    }
}
if(!isset($_GET['back'])) {
	header('Location: ./');
	die();
} else {
?>
<html>
<body onload="history.go(-1)">
</body>
</html>
<?php
}
?>

