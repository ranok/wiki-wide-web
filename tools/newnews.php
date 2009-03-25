<?php
include('../lib/classes.class.php');
include('../lib/misc.inc.php');
if(isset($_POST['submit'])) {
	adminCheck();
	$title = htmlentities($_POST['title']);
	$body = $_POST['body'];
	$db = new DB();
	$db->query("INSERT INTO news (`author`, `title`, `body`) VALUES ('{$_SESSION['uid']}', '$title', '$body');");
	$db->close();
	header("Location: ../index.php");
	die();
} else {
?>
<html>
	<head>
		<title>Submit a News Story</title>
	</head>
	<body>
		<form action="newnews.php" method="post">
		Title: <input type="text" name="title" /><br />
		Body: <br /><textarea cols="35" rows="5" name="body"></textarea><br /><br />
		<input type="submit" name="submit" value="Submit News" />
		</form>
	</body>
</html>
<?php
}
?>
