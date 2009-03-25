<?php
/*
User Class
*/

class User {

    /*
        A user class, which allows for easy mapping to the database
    */
    
    var $username, $password, $uid, $email, $admin;
    
    /*
    Function User
    
    Creates a dummy User object
    */
    
    function User() {
        $this->username = -1;
        $this->password = -1;
        $this->uid = -1;
        $this->email = -1;
        $this->admin = -1;
    }
    
    /*
    Function populateUser
    
    Fills in the fields in the object from the database using the UID as a key
    */
    
    function populateUser($uid) {
        $this->uid = $uid;
        $db = new DB();
        $db->query("SELECT * FROM user WHERE uid=".$this->uid);
        $userinfo = $db->fetchRow();
        $this->username = $userinfo[1];
        $this->password = $userinfo[2];
        $this->email = $userinfo[3];
        $this->admin = $userinfo[4];
        $db->close();
        return 0;
    }
    
    /*
    Function getFriends
    
    Returns an array of Users who are friends with the User
    */
    
    function getFriends() {
    	$db = new DB();
    	$db->query("SELECT uid1, uid2 FROM friend WHERE (uid1 = '".$this->uid."' OR uid2 = '".$this->uid."') AND accepted = '1';");
    	$friends = array();
    	$num = $db->numRows();
    	for($i = 0; $i < $num; $i++) {
    		$finfo = $db->fetchRow();
    		if($finfo[0] == $this->uid) {
    			$friends[] = new User();
    			$friends[$i]->populateUser($finfo[1]);
    		} else {
    			$friends[] = new User();
    			$friends[$i]->populateUser($finfo[0]);
    		}
    	}
    	$db->close();
    	return $friends;
    }
    
    /*
    Function requestFriend
    
    Requests friendship with the passed user, if there is an outstanding friend request, then it makes them friends
    */
    
    function requestFriend($username) {
    	if($username == $this->username) {
    		return -3;
    	}
    	$db = new DB();
    	$username = strtolower($username);
    	$db->query("SELECT uid FROM user WHERE username = '$username';");
    	if($db->numRows() == 0) {
    		return -1; // That person doesn't exist.
    	}
    	$fid = $db->fetchRow();
    	$fid = $fid[0];
    	$db->query("SELECT * FROM friend WHERE (uid1 = '{$this->uid}' AND uid2 = '$fid') OR (uid2 = '{$this->uid}' AND uid1 = '$fid');");
    	if($db->numRows() == 0) { // Makes friend request
    		$db->query("INSERT INTO friend (uid1, uid2) VALUES ('".$this->uid."', '$fid');");
    	} else {
    		$row = $db->fetchObject();
    		if(1) { // They can be friends :)
    			$db->query("UPDATE friend SET accepted = '1' WHERE (uid1 = '{$this->uid}' AND uid2 = '$fid') OR (uid2 = '{$this->uid}' AND uid1 = '$fid');");
    		} else {
    			return -2;
    		}
    	}
    	$db->close();
    	return 0;
    }
    
    /*
    Function getRequests
    
    Returns an array of User objects
    */
    
    function getRequests() {
    	$db = new DB();
    	$db->query("SELECT uid1, uid2 FROM friend WHERE (uid1 = ".$this->uid." OR uid2 = ".$this->uid.") AND accepted = '0';");
    	$friends = array();
    	$num = $db->numRows();
    	for($i = 0; $i < $num; $i++) {
    		$finfo = $db->fetchRow();
    		if($finfo[0] == $this->uid) {
    			$friends[] = new User();
    			$friends[$i]->populateUser($finfo[1]);
    		} else {
    			$friends[] = new User();
    			$friends[$i]->populateUser($finfo[0]);
    		}
    	}
    	$db->close();
    	return $friends;
    }
    
    /*
    Function newUser
    
    Creates a new user from the passed information
    */
    
    function newUser($username, $password, $email) {
        $this->username = $username;
        $this->password = md5($password);
        $this->email = $email;
        $this->admin = 0;
        $db = new DB();
        $db->query("SELECT * FROM user WHERE username = '$this->username'");
        if($db->numRows() != 0) {
            return -1;
        }
        $db->query("INSERT INTO user VALUES(NULL, '$this->username', '$this->password', '$this->email', '$this->admin')");
        $_SESSION['username'] = $this->user;
        $db->query("SELECT * FROM user WHERE username ='$this->username'");
        $userinfo = $db->fetchRow(); 
        $_SESSION['uid'] = $userinfo[0];
        $this->uid = $userinfo[0];
        $_SESSION['username'] = $userinfo[1];
        $this->username = $userinfo[1];
        $_SESSION['userdata'] = serialize($this);
        $db->close();
        return 0;
    }
    
    /*
    Function commitChanges
    
    Saves the current values in the object to the database.
    */
    
    function commitChanges() {
        $db = new DB();
        $db->query("UPDATE user SET (password='".$this->password."', admin=".$this->admin.", email='".$this->email."') WHERE uid = ".$this->uid);
        $db->close();
        return 0;
    }
    
    /*
    Function login
    
    Checks the passed username and password combo, and returns the following:
    -2: No such username
    -1: Wrong Pass
    0: All is well
    */
    
    function login($username, $password) {
        $this->username = $username;
        $this->password = md5($password);
        $db = new DB();
	$this->username = (get_magic_quotes_gpc()) ? $username : addslashes($username);
        $db->query("SELECT * FROM user WHERE username= '$this->username'");
        if ($db->numrows() == 0) {
            $db->close();
            return -2; // User not found!
        }
        $userdata = $db->fetchRow();
        $dbpassword = $userdata[2];
        if($dbpassword != $this->password) {
            $db->close();
            return -1; // Wrong pass!
        }
        $this->uid = $userdata[0];
        $this->email = $userdata[3];
        $this->admin = $userdata[4];
        $db->close();
        $_SESSION['uid'] = $this->uid; // This is all we'll need to populate any future objects
        $_SESSION['username'] = $this->username;
        $_SESSION['userdata'] = serialize($this);
        return 0;
    }
    
    /*
    Function logout
    
    Removes the session data
    */
    
    function logout() { // Good bye !
    	unset($_SESSION['uid']);
    	unset($_SESSION['username']);
    	unset($_SESSION['userdata']);
    	setcookie('remember', '', time() - 3600);
    }
    
}

?>
