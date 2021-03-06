<?php

/**
 * Alternate file to drive the gallery designed to be included within another 
 * page. 
 * 
 * This page will only send content-type headers if they have not already
 * been sent. Placing a call to ob_start() within the including page before any
 * content has been output will allow singapore to use non-default charsets.
 * 
 * @package singapore
 * @author Tamlyn Rhodes <tam at zenology dot co dot uk>
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License
 * @copyright (c)2003-2005 Tamlyn Rhodes
 * @version $Id: external.php,v 1.4 2006/08/07 10:04:28 thepavian Exp $
 */

//you may leave this blank as it will be automatically detected
$relativePath = "";

//check whether this file has been included by another and if 
//so calculate the relative path and instantiate $sg with it 
if(isset($_SERVER["SCRIPT_FILENAME"]) && realpath(__FILE__) != realpath($_SERVER["SCRIPT_FILENAME"])) {
  //split each path into chunks
  $you = explode(DIRECTORY_SEPARATOR,dirname(realpath($_SERVER["SCRIPT_FILENAME"])));
  $me  = explode(DIRECTORY_SEPARATOR,dirname(realpath(__FILE__)));
  $i=0;
  //find at what level paths first differ
  while($i<count($you) && $i<count($me) && $you[$i] == $me[$i]) $i++;
  //travel up appropriate number of directories
  for($j=$i;$j<count($you);$j++) $relativePath .= "../";
  //travel down appropriate directories
  for($j=$i;$j<count($me); $j++) $relativePath .= $me[$j]."/";

//otherwise print an error as this is not how external.php is supposed to work.
} else {
  die("This file is not intended to be used in this way. Please see the singapore integration guide.");
}
  

//include main class
require_once $relativePath."includes/singapore.class.php";

//create a wrapper
$sg = new Singapore($relativePath);

//send content-type and character encoding header
if(!headers_sent())
  header("Content-type: text/html; charset=".$sg->character_set);

//pass control over to template
 @include $sg->config->base_path.$sg->config->pathto_current_template."index.tpl.php";

?>
