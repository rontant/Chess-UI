<?php
// The following variables must be setup before including this routine
//  $element_id 
//  $top_choice

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$file = 'engine_list.json';
$json = file_get_contents($file,0,null,null);
$arr = json_decode($json,true);    
echo "<select id='". $element_id. "' onChange='updatePlayer()' class='w3-black'>";

if($top_choice==="") {
	$s="selected";
} else {
	$s="";
	echo "<option value=''>Human</option>";
}

for ($x = 0; $x < sizeof($arr); $x++) {
   echo "<option $s value='". $arr[$x][1]."'>".$arr[$x][0]."</option>";
   $s="";
}
echo "</select>"


?>