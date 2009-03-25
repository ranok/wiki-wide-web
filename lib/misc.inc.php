<?php
/*
Wiki Wide Web Misc Functions
Author: Jacob I. Torrey
Copyright 2008 JIT Web Solutions
*/
ini_set('session.use_only_cookies', "1");
session_start();

function entityScrub($src) { // Replaces entities with what they should be
	$src = str_replace("&#039;", "'", $src);
	$src = str_replace("&copy;", "©", $src);
	$src = str_replace("&#8211;", "–", $src);
	$src = str_replace("&#8217;", "’", $src);
	$src = str_replace("&#160;", " ", $src);
	$src = str_replace("&nbsp;", " ", $src);
	$src = str_replace("&amp;", "&", $src);
	$src = str_replace("&reg;", "®", $src);
	$src = str_replace("&#8220;", "“", $src);
	$src = str_replace("&#8221;", "”", $src);
	$src = str_replace("&#8230;", "…", $src);
	return $src;
}

function printNews($num) {
	$db = new DB();
	$db->query("SELECT `author`, `title`, `body` FROM `news` ORDER BY `time` DESC LIMIT $num;");
	while($row = $db->fetchRow()) {
		print '<p class="news"><span class="bold">'.$row[1].'</span></p>'."\r\n";
		print '<p class="small indent">By '.uidToUsername($row[0]).'</p>'."\r\n";
		print '<p>'.$row[2].'</p>';
		print "\r\n";
	}
	$db->close();
}

function adminCheck() { // Are they an admin?
	if(!isset($_SESSION['userdata'])) {
		return false;
	}

	$u = unserialize($_SESSION['userdata']);
	if(!$u->admin) {
		return false;
	}
	return true;
}

function htmlheader() {
    $out = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
    <head>
        <title>Wiki Wide Web: Enter Web 3.0</title>
        <link rel="stylesheet" type="text/css" href="/css/master.css" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="author" content="Jacob I. Torrey" />
        <meta name="author" content="Keegan M. Lowenstein" />
        <script type="text/javascript" src="./lib/misc.js"></script>
        <meta name="desc" content="Wiki Wide Web: Where the internet is YOUR playground" />
    </head>';
    return $out;
}

function statichtmlheader() {
	$out = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Wiki Wide Web: Welcome to the Future!</title>
	<meta name="author" content="Jacob Torrey">
	<meta name="designer" content="Max Edmands">
	<script type="text/javascript" src="lib/misc.js"></script>
	<link rel="stylesheet" href="/css/master.css" type="text/css" media="screen" title="no title" charset="utf-8">';
    return $out;
}

function menu() {
	$out = '<div id="navbar"><ul>
    			<li><a href="/">Wiki Wide Web Home</a></li>
    			<li><a href="/about">About</a></li>
    			<li><a href="/statistics">Statistics</a></li>
    			<li><a href="/contact">Contact Us</a></li>
    		</ul></div>';
    	return $out;
}

function getSource($address) { // Gets the remote source
	ini_set("user_agent", 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.4) Gecko/20061201 Firefox/2.0.0.4 (Ubuntu-feisty)');
        $fp = fopen($address, 'r');
        if(!$fp) {
            return null;
        }
        $content = '';
        while(!feof($fp)) {
            $content .= fread($fp, 5000);
        }
        fclose($fp);
        return $content;
}

function replaceOnce($search, $replace, $content) { // Like string_replace, but only replaces the first instance
    $pos = strpos($content, $search);
    if ($pos === false) {
    	return $content;
    } else {
    	return substr($content, 0, $pos) . $replace . substr($content, $pos+strlen($search));
    }
}

function uidToUsername($uid) {
	$db = new DB();
	$db->query("SELECT username FROM user WHERE uid = '$uid';");
	if($db->numRows() == 0) {
		$db->close();
		return -1;
	} else {
		$row = $db->fetchRow();
		$row = $row[0];
		$db->close();
		return $row;
	}
}

function usernameToUid($username) {
  $db = new DB();
  $db->query("SELECT uid FROM user WHERE username = '$username';");
  if($db->numRows() == 0) {
    $db->close();
    return -1;
  } else {
    $row = $db->fetchRow();
    $row = $row[0];
    $db->close();
    return $row;
  }
}

if (!function_exists('array_diff_key')) {
    function array_diff_key()
    {
        $arrs = func_get_args();
        $result = array_shift($arrs);
        foreach ($arrs as $array) {
            foreach ($result as $key => $v) {
                if (array_key_exists($key, $array)) {
                    unset($result[$key]);
                }
            }
        }
        return $result;
   }
}

function WikiWideWebize($s, $source) {
	$diffs = $s->getDiffs();
	for($i = 0; $i < count($diffs); $i++) {
		$source = replaceOnce($diffs[$i]->toreplace, $diffs[$i]->replacewith, $source);
	}
	return $source;
}
?>