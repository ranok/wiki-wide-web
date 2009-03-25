<?php
/*
Site
*/

class Site {
    
    var $address, $parent, $lock, $sid, $loaded;
    
    /*
    Function Site
    
    Site constructor
    */
    
    function Site($address) {
        $db = new DB();
        $this->address = $address;
        if(substr($this->address, -1, 1) == '/') {
	  $this->address = substr($this->address, 0, strlen($this->address) - 1); // Remove trailing /
        }
        $db->query("SELECT * FROM site WHERE address = '".$this->address."'");
        if($db->numRows() == 0) { // No record in the DB
            $db->close();
            $this->loaded = 0;
	    $newad = str_replace("http://", '', $this->address);
	    $newad = preg_replace('/\/.*$/', '', $newad);
	    $newad = 'http://'.$newad;
	    if($this->address == $newad) {
	      $this->parent = "";
	      $this->lock = 0;
	    } else {
	      $s = new Site($newad);
	      if($s->loaded) {
		$this->parent = $s->sid;
		$this->lock = $s->lock;
	      } else {
		$s->save();
	      }
	    }
	   
	} else {
            $siteinfo = $db->fetchRow();
            $this->lock = $siteinfo[3];
	    $this->parent = $siteinfo[1];
            $this->sid = $siteinfo[0];
            $db->close();
            $this->loaded = 1;
        }
    }
    
    /*
    Function checkWWW
    
    This will make www.google.com and google.com synonymous
    */
    
    function checkWWW() {
    	if(substr($this->address, 0, 11) == "http://www.") {
            	$s = new Site(replaceOnce('www.', '', $this->address));
            	if($s->loaded) {
            		$this->sid = $s->sid;
            		$this->loaded = 1;
			$this->parent = $s->parent;
            		$this->lock = $s->lock;
            	}
            } else {
            	$s = new Site(replaceOnce("http://", "http://www.", $this->address));
            	if($s->loaded) {
            		$this->sid = $s->sid;
            		$this->loaded = 1;
			$this->parent = $s->parent;
            		$this->lock = $s->lock;
            	}
            }
    }
    
    /*
    Function save
    
    Saves the object data to the DB
    */
    
    function save() {
    	if(!$this->loaded) {
    		$db = new DB();
    		$db->query("INSERT INTO site (`address`, `parent`, `lock`) VALUES ('{$this->address}', '{$this->parent}', '{$this->lock}');");
    		$db->close();
    		$this->load();
    		return 0;
    	}
 	    return -1;
    }
    
    /*
    Function load
    
    Loads the site info from the DB
    */
    
    function load() {
    	$db = new DB();
        $db->query("SELECT * FROM site WHERE address = '".$this->address."'");
        if($db->numRows() == 0) {
            $db->close();
	    $this->parent = "";
            $this->loaded = 0;
            $this->lock = 0;
        } else {
            $siteinfo = $db->fetchRow();
            $this->lock = $siteinfo[3];
	    $this->parent = $siteinfo[1];
            $this->sid = $siteinfo[0];
            $db->close();
            $this->loaded = 1;
        }
    }
    
    /*
    Function populateXML
    
    This generates the XML for the AJAX requests
    */
    
    function populateXML() {
        if($this->loaded == 1) {
            $toapply = $this->getDiffs();
            for($i = 0; $i < count($toapply); $i++) {
                print '<diff id="'.$toapply[$i]->did.'">'."\r\n";
                print "\t".'<comment>'.$toapply[$i]->comment.'</comment>'."\r\n";
                print "\t".'<user>'.uidToUsername($toapply[$i]->uid).'</user>'."\r\n";
                print "\t".'<toreplace><![CDATA['.$toapply[$i]->toreplace.']]></toreplace>'."\r\n";
                print "\t".'<replacewith><![CDATA['.$toapply[$i]->replacewith.']]></replacewith>'."\r\n";
                print '</diff>'."\r\n";
            }
        }
    }

    /*
    Function getDiffs
    
    Returns an array of diffs in reverse chronological order to be made into XML
    */
    
    function getDiffs() {
        $db = new DB();
        if(!$this->sid) { // If there are none, return an empty array
        	return array();
        }
        $query = "SELECT did FROM diff WHERE sid = ".$this->sid." ORDER BY time ASC;";
        $db->query($query);
        $diffs = array();
        for($i = 0; $i < $db->numRows(); $i++) { // Look at this lovely little loop :)
            $diffs[] = new Diff();
            $row = $db->fetchRow();
            $diffs[$i]->populateDiff($row[0]);
        }
        $db->close();
        return $diffs;
    }
    
}   

?>
