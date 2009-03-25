<?php
/*
Database Class
*/

class DB {
    
    var $res;
    
    function DB() {
       mysql_connect('host', 'username', 'password'); 
       mysql_select_db('wikiwideweb');
       //$this->res = -1;
    }
    
    function query($query) {
        $this->res = mysql_query($query) or die($query." - ".mysql_error()); // Remove when moved to live
    }
    
    function numRows() {
        return mysql_num_rows($this->res);
    }
    
    function fetchRow() {
        return mysql_fetch_row($this->res);
    }
    
    /*
    Function fetchObject
    
    Returns an object representation of the current row in the DB
    */
    
    function fetchObject() {
    	return mysql_fetch_object($this->res);
    }
    
    /*
    Function close
    
    Closes the connection to the DB
    */
    
    function close() {
        @mysql_close();
    }
}

?>
