<?php


function bestmove($cmd,$engine,$movetime) {
	// echo "Get Bestmove Command: ".$cmd."</br>";
	$ret="";
	$descr = array(

		0 => array("pipe", "r"),
		1 => array("pipe", "w"),
		2 => array("pipe", "w")
	);

	$pipes = array();

	// open the process with those pipes
	$process = proc_open($engine, $descr, $pipes);

	// check if it's running
	if (is_resource($process)) {

		// send first universal chess interface command
		fwrite($pipes[0], "uci\n");
		fwrite($pipes[0], "setoption name Threads value 4\n");
		//fwrite($pipes[0], "setoption name hash value 1024\n");
		fwrite($pipes[0], "setoption name Ponder value false\n");
		fwrite($pipes[0], "isready\n");
		//fwrite($pipes[0], "ucinewgame\n");
		fwrite($pipes[0], $cmd);
		fwrite($pipes[0], "go movetime $movetime\n");
		$cnt=0;
		//$start_time=time();
		while($cnt<1000){
			usleep(100);
			$s = fgets($pipes[1],4096);
			$ret .= $s;
			if(strpos(' '.$s,'bestmove') != false){
				break;
			}
	
			if(strpos(' '.$s,'error') != false){
				break;
			}
			if(strpos(' '.$s,' mates}') != false){ // Igel gives this response when checkmated
				break;
			} 
			$cnt++;
		}
		fwrite($pipes[0], "quit\n");
		// close read pipe or STDOUTPUT can't be read
		fclose($pipes[0]);
		// close the last opened pipe
		fclose($pipes[1]);
		// at the end, close the process
		proc_close($process);
		//$elapsed_time=time()-$start_time;

	}
	return $ret;
}

function query($cmd,$engine) {
	// echo "Getfen command: ".$cmd."</br>";
	$ret="";

	// ok, define the pipes
	$descr = array(

		0 => array("pipe", "r"),
		1 => array("pipe", "w"),
		2 => array("pipe", "w")
	);

	$pipes = array();

	// open the process with those pipes
	$process = proc_open($engine, $descr, $pipes);

	// check if it's running
	if (is_resource($process)) {

		// send first universal chess interface command
		//fwrite($pipes[0], "setoption name threads value 4\n");
		//fwrite($pipes[0], "setoption name hash value 1024\n");
		//fwrite($pipes[0], "uci\n");

				// send first universal chess interface command
		
		// send analysis (5 seconds) command
		fwrite($pipes[0], "go movetime 5000\n");
		fwrite($pipes[0], $cmd);
		fwrite($pipes[0], "isready\n");
		fwrite($pipes[0], "go ponder\n");
		// close read pipe or STDOUTPUT can't be read
		//fclose($pipes[0]);
		// read and print all output comes from the pipe
		while (!feof($pipes[1])) {

			$ret=$ret . fgets($pipes[1]);

		}

		// close the last opened pipe
		fclose($pipes[1]);

		// at the end, close the process
		proc_close($process);

	}
	return $ret;
}
	
function uci2($cmd,$engine,$movetime) {

$wait=($movetime*1000)+500;
$ret="";

	// ok, define the pipes
	$descr = array(

		0 => array("pipe", "r"),
		1 => array("pipe", "w"),
		2 => array("pipe", "w")
	);

	$pipes = array();

	// open the process with those pipes
	$process = proc_open($engine, $descr, $pipes);

	// check if it's running
	if (is_resource($process)) {

		// send first universal chess interface command
		fwrite($pipes[0], "uci\n");
		fwrite($pipes[0], "isready\n");   // required by Baislicka
		fwrite($pipes[0], "setoption name Threads value 4\n");
		fwrite($pipes[0], "setoption name Hash value 1024\n");
		fwrite($pipes[0], "setoption name Ponder value false\n");

		fwrite($pipes[0], $cmd);
		fwrite($pipes[0], "go infinite\n");
	    usleep($wait);
		fwrite($pipes[0], "stop\n");
		fwrite($pipes[0], "isready\n"); // required by igel
		fwrite($pipes[0], "quit\n");


		// read and print all output comes from the pipe
		while (!feof($pipes[1])) {
			$ret=$ret . fgets($pipes[1]);
		}
		fclose($pipes[0]);
		fclose($pipes[1]);
		proc_close($process);

	}
	return $ret;
	
}
/* Not used
function piece($fen,$coord) {
	// return piece info of coordinate from a FEN string
	echo $fen;
	echo "<br>Coord:".$coord;
    $p=strpos($fen," ");
	if ($p !== false) {
       $boardfen=substr($fen,0,$p);
	} else {
		return;
	}
	
	echo "<p>boardfen [".$boardfen."]";
	

	$lineno=substr($coord,1,1);
	$colno=substr($coord,0,1);
	$colidx=strpos("abcdefgh",$colno);
	echo "<br>colidx=".$colidx;
	echo "<br>$colno $lineno";
	$line = explode('/',$boardfen);
	$linecontent=$line[$lineno-1];
	echo "<br>Line $lineno contains: ".$linecontent;
	for($i;$i<strlen($linecontent);$i++) {
		echo "<br>".substr($linecontent,$i,1);
	}
	
	
    $content=substr($line[$lineno-1],$colidx,1);
	echo "<br>content [$content]";
	}
	return;
	*/
?>