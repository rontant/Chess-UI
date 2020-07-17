<?php
// Set to display all errors 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "common_functions.php";

if(isset($_POST["engine"])) {
    $engine=$_POST["engine"];
} else {
	$engine="stockfish";
}




$fen=$_POST["fen"];
if ($fen==="") {exit;}	 
$count=intval($_POST["count"]);


$cmd=$_POST["cmd"];
if(isset($_POST["movetime"])) {
	$movetime=$_POST["movetime"];
} else {
	$movetime=3000;
}	

if(strpos($engine,"Leela")!== false) {

	$para='fen='. urlencode($fen).'&engine='.urlencode($engine).'&count='.urlencode($count);
	$para=$para . '&cmd='. urlencode($cmd). '&movetime='. urlencode($movetime);
	$url="http://192.168.2.66/chess/engine.php?".$para;
	echo ( file_get_contents($url));
	usleep(7200000);
	exit;
}


//$uciret= bestmove($cmd,$engine,$movetime);
$uciret= uci2($cmd,$engine,$movetime);
//$uciret=$cmd. "\n" .$uciret;

echo "<textarea id='uciwindow' rows='15' style='width:100%' class='w3-tiny' readonly>".$uciret."</textarea>";

$bestmove="";
$ponder="";

// Locate and extract Bestmove


   $t=strpos($uciret,"bestmove ");
   if ($t !== false) {
		$bestmove=trim(substr($uciret,$t+9,5));
		echo "<div class='w3-tiny'>";
		echo "<input type='hidden' id='bestmove' value='".$bestmove. "' disabled>";
		//
		$ponder_pos=strpos($uciret," ponder ");
		if($ponder_pos != false) {
			$ponder=substr($uciret,$ponder_pos+8,4);
			echo " Ponder: <input id='ponder' value='". $ponder. "' size=3 disabled>";
		}
		echo "</div>";

		if (strpos($uciret,"bestmove (none)") != false) {
			   echo "<h2>Checkmate!</h2>";   
		} 
		if (strpos($uciret,"bestmove none") != false) {
			   echo "<h2>Checkmate!</h2>";   
		} 
		if (strpos($uciret,"bestmove 0000") != false) {  //igel
			   echo "<h2>Checkmate!</h2>";   
		} 
		if (strpos($uciret,"bestmove a1a1") != false) {   //lc0
			   echo "<h2>Checkmate!</h2>";   
		} 
		
   } else {
	   
	   echo "<input type='hidden' id='bestmove' value='none' disabled>";
       echo "<p>The engine just walked out<br>";
	   exit;
   }


?>