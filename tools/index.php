<?php
/*
Wiki Wide Web tools
Author: Jacob Torrey
*/

require('../lib/classes.class.php');
require('../lib/misc.inc.php');

print statichtmlheader()."<body>\r\n";

if(isset($_SESSION['userdata'])) {
  $u = unserialize($_SESSION['userdata']);
  if($u->admin) {
    print "<a href=\"newnews.php\">Add a news article</a><br />\r\n";
    print "<a href=\"clean.php\">Clean the DB</a.<br />\r\n";
  }
}
print "<a href=\"wikiwideweb/wikiwideweb.xpi\">Wiki Wide Development Extension</a><br />\r\n";
print "</body></html>";

?>
