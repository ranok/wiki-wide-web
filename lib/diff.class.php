<?php
/*
Diff Class
*/

class Diff {

    /*
        Each diff gets it's own Diff object
    */

    var $did, $sid, $uid, $toreplace, $replacewith, $time, $comment;
    
    /*
    Function Diff
    
    Dummy Diff object construstor
    */
    
    function Diff() {
        $this->did = -1;
        $this->sid = -1;
        $this->uid = -1;
        $this->time = -1;
        $this->ip = -1;
	$this->rating = 0;
        $this->toreplace = '';
        $this->replacewith = '';
        $this->comment = '';
    }
    
    /*
    Function commitChanges
    
    Updates the DB with any changes made to the object
    */
    
    function commitChanges() {
      $db = new DB();
      $db->query("INSERT INTO diff (sid, uid, toreplace, replacewith, comment, ip) VALUES ('{$this->sid}', '{$this->uid}', '".addslashes($this->toreplace)."', '".addslashes($this->replacewith)."', '".addslashes($this->comment)."', '".ip2long($this->ip)."');");
      $db->close();
    }

    /*
      Functio save

      Saves the changes to the diff object
    */

    function save() {
      $db = new DB();
      $db->query("UPDATE diff SET sid = '{$this->sid}', uid = '{$this->uid}', toreplace = '".addslashes($this->toreplace)."', replacewith = '".addslashes($this->replacewith)."', comment = '".addslashes($this->comment)."', ip = '".ip2long($this->ip)."' WHERE did = '{$this->did}';");
      $db->close();
    }

    /*
    Function delete
    
    Removes the passed diff from existence
    */
    
    function delete($did) {
        $db = new DB();
        $db->query("DELETE FROM diff WHERE did = '$did' LIMIT 1;");
        $db->close();
    }
    
    /*
    Function populateDiff
    
    Fills in the object fields from the DB using the DID as a key
    */
    
    function populateDiff($did) {
        $db = new DB();
        $db->query("SELECT * FROM diff WHERE did = $did;");
	if($db->numRows() > 0) {
	  $diffinfo = $db->fetchRow();
	  $this->did = $did;
	  $this->sid = $diffinfo[1];
	  $this->uid = $diffinfo[2];
	  $this->toreplace = $diffinfo[3];
	  $this->replacewith = $diffinfo[4];
	  $this->time = $diffinfo[5];
	  $this->ip = long2ip($diffinfo[7]);
	  $this->comment = $diffinfo[6];
	  $db->query("SELECT SUM(rating) FROM rating WHERE did = {$this->did};");
	  $row = $db->fetchRow();
	  $this->rating = $row[0];
	  $db->close();
	  return true;
	} else {
	  $db->close();
	  return false;
	}
    }
    

}

?>
