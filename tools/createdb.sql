CREATE TABLE `diff` (
  `did` int(11) NOT NULL auto_increment,
  `sid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `toreplace` text NOT NULL,
  `replacewith` text NOT NULL,
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `comment` text,
  PRIMARY KEY  (`did`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=396 ;

CREATE TABLE `friend` (
  `uid1` int(11) NOT NULL,
  `uid2` int(11) NOT NULL,
  `accepted` int(11) NOT NULL default '0',
  PRIMARY KEY  (`uid1`,`uid2`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `site` (
  `sid` int(11) NOT NULL auto_increment,
  `parent` int(11) default NULL,
  `address` text NOT NULL,
  `lock` int(1) default '0',
  PRIMARY KEY  (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

CREATE TABLE `user` (
  `uid` int(11) NOT NULL auto_increment,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `email` text NOT NULL,
  `admin` int(1) NOT NULL,
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

INSERT INTO `user` (`uid`, `username`, `password`, `email`, `admin`) VALUES 
(1, 'ranok', 'e347e6e075e2a9041399d73a4aa234c3', 'ranok@r4n0k.com', 1);

CREATE TABLE `news` (
  `nid` int(5) NOT NULL auto_increment,
  `author` int(11) NOT NULL default '0',
  `title` varchar(55) collate utf8_unicode_ci NOT NULL default '',
  `body` text collate utf8_unicode_ci NOT NULL,
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`nid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;
