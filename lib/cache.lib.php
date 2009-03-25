<?php
/*
SuperPHP - Caching Library
Author: Jacob I. Torrey
Date: 11/14/07
*/

class Cache {

  /* Configuration Variables
    Change the below variables to reflect your usage
  */
  var $cachedir = 'cache/';
  // End configuration
  
  var $cachefile = '';
  var $maxage = 0; // Number of seconds before the cache will expire

  function Cache() {
    $cachefilename = func_get_args();
    $cachefilename = $cachefilename[0];
    $cachefilename = str_replace('/', '', isset($cachefilename) ? $cachefilename : $_SERVER['PHP_SELF']).'.cache';
    $this->cachefile = $this->cachedir.$cachefilename;
  }
  
  function startCache($age) {
    $this->maxage = $age;
    if(file_exists($this->cachefile) && (time() - filemtime($this->cachefile)) < $this->maxage) {
      readfile($this->cachefile);
      die();
    }
    ob_start();
  }
  
  function endCache() {
    $cache = ob_get_contents();
    ob_end_flush();
    $fp = fopen($this->cachefile, 'w');
    flock($fp, LOCK_EX);
    fwrite($fp, $cache);
    flock($fp, LOCK_UN);
    fclose($fp);
  }
  
  function clearCache() {
    if(file_exists($this->cachefile)) {
      unlink($this->cachefile);
    }
  }
  
  function call() {
    $args = func_get_args();
    $age = $args[0];
    $filename = $this->cachedir.base64_encode(join('', array_slice($args, 1))).'.cache';
    $function = $args[1];
    if(file_exists($filename) && (time() - filemtime($filename)) < $age) {
      include($filename);
      print $output;
      return $return;
    }
    ob_start();
    $return = call_user_func_array($function, array_slice($args, 2));
    $output = ob_get_contents();
    ob_end_flush();
    $fp = fopen($filename , 'w');
    flock($fp, LOCK_EX);
    fwrite($fp, "<?php\r\n");
    if($return != '') fwrite($fp, '$return = <<<CACHEEOF'."\r\n".$return."\r\nCACHEEOF;\r\n");
    if($output != '') fwrite($fp, '$output = <<<CACHEEOF'."\r\n".$output."\r\nCACHEEOF;\r\n");
    fwrite($fp, "?>");
    flock($fp, LOCK_UN);
    fclose($fp);
    return $return;
  }
  
  function clearCall() {
    $args = func_get_args();
    $filename = $this->cachedir.base64_encode(join('', $args)).'.cache';
    if(file_exists($filename)) {
      unlink($filename);
    }
  }

}

?>
