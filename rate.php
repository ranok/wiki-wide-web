<?php
/*
Wiki Wide Web rating page
Author: Jacob Torrey
Date: 11/28/07
*/

include('lib/misc.inc.php');
include('lib/classes.class.php');

print htmlheader()."\r\n<body>\r\n";

if(isset($_SESSION['userdata'])) {
  vote();
} else if(isset($_POST['username'])) {
  $u = new User();
  if($u->login($_POST['username'], $_POST['password']) == 0) {
    vote();
  }
} else {
  ?>
 <h1 class="center">Please Login to Rate</h1>
    <form action="rate.php" method="post">
    Username: <input type="text" name="username" /><br />
    Password: <input type="password" name="password" /><br />
    <input type="submit" value="login" />
    </form>
    </body>
    </html>
    <?php
    die();
}

function vote() {
  if(isset($_GET['dir']) && isset($_GET['did'])) {
    $d = new Diff();
    if($d->populateDiff($_GET['did'])) {
      if($_GET['dir'] == 1 || $_GET['dir'] == -1) {
	$db = new DB();
	$db->query("SELECT * FROM `rating` WHERE `did` = '{$_GET['did']}' AND `uid` = '{$_SESSION['uid']}';");
	if($db->numRows() == 0) {
	  $db->query("INSERT INTO `rating` (`did`, `uid`, `rating`) VALUES ('{$_GET['did']}', '{$_SESSION['uid']}', '{$_GET['dir']}');");
	} else {
	  $db->query("UPDATE `rating` SET `rating` = '{$_GET['dir']}' WHERE `did` = '{$_GET['did']}' AND `uid` = '{$_SESSION['uid']}';");
	}
	$db->close();
      }
    }
  }
}
?>

