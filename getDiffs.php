<?php
require_once('lib/misc.inc.php');
require_once('lib/classes.class.php');

header('Content-type: application/xml');

if(isset($_GET['address'])) {
			    $s = new Site($_GET['address']);
			    $s->checkWWW();
			    print '<?xml version="1.0"?>'."\r\n";
			    print '<diffs>'."\r\n";
			    $s->populateXML();
			    print '</diffs>'."\r\n";
}
?>
