<?php
include('../lib/classes.class.php');
include('../lib/misc.inc.php');

adminCheck();

$db = new DB();
$db->query("SET NAMES utf8");
$toremove = array();
$db->query("SELECT * FROM diff;");
while($row = $db->fetchRow()) {
	if($row[4] == entityScrub($row[3])) {
		$toremove[] = $row[0];
	}
}

for($i = 0; $i < count($toremove); $i++) {
	$db->query("DELETE FROM diff WHERE did = '{$toremove[$i]}';");
}
$db->query("OPTIMIZE TABLE `diff`;");
$db->close();
header('Location: index.php');
die();
?>
