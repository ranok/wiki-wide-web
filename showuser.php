<?php
/*
Wiki Wide Web user stats and RSS generator
Author: Jacob I. Torrey
Date: 11/28/07
*/
include('lib/misc.inc.php');
include('lib/classes.class.php');
include('lib/cache.lib.php');

define(NUMITEMS, 5);

if(!isset($_GET['username']) || ($user = usernameToUid($_GET['username'])) == -1) {
  die("User not found");
}

$u = new User();
$u->populateUser($user);

if(isset($_GET['rss'])) {
  header("Content-type: application/rss+xml");
  print '<?xml version="1.0"?><rss version="2.0"><channel>'."\r\n";
  print "<title>".ucfirst($u->username)."'s Wiki Wide Web Feed</title>\r\n";
  print "<link>http://{$_SERVER['SERVER_NAME']}".$_SERVER['PHP_SELF']."?username={$_GET['username']}</link>\r\n";
  print "<description>".ucfirst($_GET['username'])."'s Wiki Wide Web Feed</description>\r\n";
  print "<language>en-us</language>\r\n";
  $db = new DB();
  $db->query("SELECT diff.did, site.address, diff.comment, UNIX_TIMESTAMP(diff.time) FROM diff, site WHERE diff.uid = '{$u->uid}' AND site.sid = diff.sid ORDER BY diff.time DESC LIMIT ".NUMITEMS.";");
  while($row = $db->fetchRow()) {
    print "<item>\r\n";
    print "<title>Diff #{$row[0]} - {$row[2]}</title>\r\n";
    print "<link>{$row[1]}</link>\r\n";
    print "<description>".ucfirst($_GET['username'])." modified {$row[1]} on ".date(DATE_RFC822, $row[3])."</description>\r\n";
    print "<pubDate>".date(DATE_RFC822, $row[3])."</pubDate>\r\n";
    print "</item>\r\n";
  }
  $db->close();
  print '</channel></rss>';
} else {
  print statichtmlheader()."\r\n";
    ?>
    <style type="text/css">
       h1 {
	 text-align: center;
       }
  a {
  color: black;
  font-weight: bold;
  }
  
  </style>
      <link rel="alternate" type="text/xml" title="RSS 2.0" href="/rss/user/<?php print $_GET['username']; ?>" />
      </head>
      <body>
      <?php
      
      print "<h1>".ucfirst($_GET['username'])."'s Page</h1>\r\n";
  $db = new DB();
  $db->query("SELECT count(did) FROM diff WHERE uid = '{$u->uid}';");
  $row = $db->fetchRow();
  $num = $row[0];
  print ucfirst($u->username)." has made $num changes since joining Wiki Wide Web.\r\n";
  if($u->admin) {
    print ucfirst($u->username)." is an <span class=\"bold\">admin</span>";
  }
  print "<br /><h3>Here are his/her last few changes:</h3>\r\n";
  $db = new DB();
  $db->query("SELECT diff.did, site.address, diff.comment, UNIX_TIMESTAMP(diff.time) FROM diff, site WHERE diff.uid = '{$u->uid}' AND site.sid = diff.sid ORDER BY diff.time DESC LIMIT ".NUMITEMS.";");
  while($row = $db->fetchRow()) {
    print "<p>\r\n";
    print "<span style=\"font-size: big; font-weight: bold;\">Diff #{$row[0]} - {$row[2]}</span><br />\r\n";
    print ucfirst($_GET['username'])." modified <a href=\"{$row[1]}\">{$row[1]}</a> on ".date(DATE_RFC822, $row[3])."\r\n";
    print "</p>\r\n";
  }
  $db->close();
  print '</body></html>';
}
?>
