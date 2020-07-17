<html>

<head>
<!--meta name="viewport" content="width=device-width, initial-scale=1"-->
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<script src="../chessboard/jquery.min.js"></script>
<script src="../chess.js/chess.js"></script>

<link rel="stylesheet" href="../chessboard/css/chessboard-1.0.0.css">

<title>Chess UI</title>
</head>
<body bgcolor="#a6a6a6">
<script src="loaddoc.js"></script>
<script src="../chessboard/FileSaver.js"></script>
<script src="../chessboard/js/chessboard-1.0.0.js"></script>


<?php 


$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
	include "mobile.php"; 	
} else {
	include "desktop.php"; 	
}
?>





</html>

<script>
// NOTE: this uses the chess.js library:
// https://github.com/jhlywa/chess.js

var board = null
const game = new Chess()
var $status = $('#status')
var $fen = $('#fen')
var $pgn = $('#pgn')

function onDragStart (source, piece, position, orientation) {
  // do not pick up pieces if the game is over
  if (game.game_over()) return false

  // only pick up pieces for the side to move
  if ((game.turn() === 'w' && piece.search(/^b/) !== -1) ||
      (game.turn() === 'b' && piece.search(/^w/) !== -1)) {
    return false
  }
}

function onDrop (source, target) {
  // see if the move is legal
  var move = game.move({
    from: source,
    to: target,
    promotion: 'q' // NOTE: always promote to a queen for example simplicity
  })

  // illegal move
  if (move === null) return 'snapback'
  console.log("Human moved "+source+"-"+target);
  document.getElementById("btnStartEngine").disabled = false;

  updateStatus();

  if(document.getElementById("chkAutoPlay").checked) {	
	StartEngine() ;
  }

}

// update the board position after the piece snap
// for castling, en passant, pawn promotion
function onSnapEnd () {
  board.position(game.fen())
}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
function updateStatus () {
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
/*
  var status = ''

  var moveColor = 'White'
  if (game.turn() === 'b') {
    moveColor = 'Black'
  }

  // checkmate?
  if (game.in_checkmate()) {
    status = 'Game over, ' + moveColor + ' is in checkmate.'
  }

  // draw?
  else if (game.in_draw()) {
    status = 'Game over, drawn position'
  }

  // game still on
  else {
    status = moveColor + ' to move'

    // check?
    if (game.in_check()) {
      status += ', ' + moveColor + ' is in check'
    }
  }

  //$status.html(status)
  */
  //$fen.html(game.fen())
  FEN=game.fen();
  document.getElementById("fen").value=FEN;
  $pgn.html(game.pgn())

  var pos = FEN.indexOf(" "); 
  var t=FEN.substr(pos+1,1)
  if (t==='w') {
	document.getElementById("SideToMove").value='white'
  } else {
	document.getElementById("SideToMove").value='black'
  }
  
  
  // Update the full move count at the top bar
  pos=FEN.lastIndexOf(" ");
  var t=parseInt(FEN.substr(pos+1));    
  document.getElementById("fullmove").innerText=t;
  
  console.log("Status Updated: "+FEN);
}


function updatePlayer() {
	
	var a=document.getElementById("topside_engine").value;
	var b=document.getElementById("bottomside_engine").value;
	if ((a==="") && (b==="")) {
		document.getElementById("btnStartEngine").disabled = true;
		document.getElementById("chkAutoPlay").disabled = true;
	} else {
		document.getElementById("btnStartEngine").disabled = false;
		document.getElementById("chkAutoPlay").disabled = false;
	}
}

function SavePgn() {          
	var correct_pgn =game.pgn({ max_width: 5, newline_char: '\n' });
	var blob = new Blob([correct_pgn], { type: "text/plain;charset=utf-8" });
	saveAs(blob, "output.pgn");

}

function pieceTheme (piece) {
  // wikipedia theme for white pieces
  if (piece.search(/w/) !== -1) {
    return '../chess/chesspieces/chess24/' + piece + '.png'
  }

  // alpha theme for black pieces
  return '../chess/chesspieces/chess24/' + piece + '.png'
}

function ApplyFEN() {
	var x=document.getElementById("fen").value;
	//console.log(game.validate_fen(x).valid);
	if (game.validate_fen(x).valid === false) {
		alert("Invalid FEN");
		return;
	}
	document.getElementById('btnApplyFEN').style.display="none";
	FEN=x;
	game.load(FEN);
	board.position(FEN);
	console.log("New game setup based on FEN: "+FEN);		
	updateStatus();		
	
}

function Option_Cancel() {
	console.log("Move canceled");		
	game.undo();
	board.position(game.fen());
	updateStatus();		
}

function FlipBoard() {
	board.flip();
	if (ORIENTATION) {
		ORIENTATION=false;
	} else {
		ORIENTATION=true;
	}
}

function show_image(src, width, height, alt,field){
	// Display "Please wait animated gif"
    var img = document.createElement("img");
    img.src = src;
    img.width = width;
    img.height = height;
    img.alt = alt;

    // This next line will just add it to the <body> tag
    document.getElementById(field).appendChild(img);
}

function Option_Reset() {
	console.log("Game Reset");
	game.reset();
	//FEN='rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1';
	//game.load(FEN);
	FEN=game.fen();
	board.position(FEN);
	document.getElementById("pgn").innerHTML="";
	document.getElementById("btnStartEngine").disabled=true;
	updateStatus();
}

function StartEngine() {
	var x=90;
	var y=18;
	document.getElementById("btnStartEngine").disabled = true;
	if(ORIENTATION) {
		if(document.getElementById("SideToMove").value==="black") {
			var engine=document.getElementById("topside_engine").value;
			show_image(image, x,y,'Wait','top_dot');
		} else {
			var engine=document.getElementById("bottomside_engine").value;	
			show_image(image, x,y,'Wait','bottom_dot');

		}
	} else {
		if(document.getElementById("SideToMove").value==="white") {
			var engine=document.getElementById("topside_engine").value;
			show_image(image, x,y,'Wait','top_dot');
		} else {
			var engine=document.getElementById("bottomside_engine").value;	
			show_image(image, x,y,'Wait','bottom_dot');
		}
	}
	if(engine==="") {
		document.getElementById("top_dot").innerText="";
		document.getElementById("bottom_dot").innerText="";
		console.log("Human to move, duh!")
		return;
	}
	console.log("Call "+engine);
	submit_to_engine(FEN,engine);

	
}

//========================================================
function submit_to_engine(fen,engine) {
//========================================================
	var count=document.getElementById('fullmove').innerText;
          
	
    if(count>3) {
		waittime=max_waittime;
	} else {
		waittime=min_waittime;
	}
	
	var cmd="position fen " + fen + "\n";
	var para="fen="+encodeURIComponent(fen)+"&count="+count+"&engine="+encodeURIComponent(engine);
	para=para+"&cmd="+cmd+"&movetime="+waittime;
	//console.log(para);
	document.getElementById('bestmove').value="";
	LoadDoc("engine.php",para,"status",true);
	//console.log("Command line for engine: ");
	//console.log(cmd);
	
	attempt=1;
	timerVar=setInterval(ProcessMove , 1500); // do not put () for the function here

}




function ProcessMove() {
	console.log("Checking for response....");
	var bestmove=document.getElementById('bestmove').value;

	ProgressBar();
	if (bestmove==="" ) {
		attempt++;
		if (attempt<patience) { return; }
		clearInterval(timerVar);
		
		console.log("Time Forfeit");
		game.header('Termination', 'Time forfeit');
		if (game.turn() === 'w') {
			WrapUp('0-1');
		} else {
			WrapUp('1-0');
		}
		game.header('Termination', 'Time forfeit');
		return;
	}
	clearInterval(timerVar);
	console.log("Engine moved "+bestmove);
	
	var time = new Date();
	//console.log("Time: " + dt.getTime() );
	
	console.log("@ " + 	time.getHours() + ":" +
						time.getMinutes() + ":" + 
						time.getSeconds());


		 
	document.getElementById('top_dot').innerText="";
	document.getElementById('bottom_dot').innerText="";
	document.getElementById('top_progressbar').innerHTML="";
	document.getElementById('bottom_progressbar').innerHTML="";
	
	var src=bestmove.substr(0,2);
	var dest=bestmove.substr(2,2);
	var mv=src+"-"+dest;

	//Castling(src,dest);
	//board.move(mv);	

	game.move({
		from: src,
		to: dest,
		promotion: 'q' // NOTE: always promote to a queen for example simplicity
    })
	
	board.position(game.fen());
	updateStatus();
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	if(game.in_checkmate()) {
		console.log("Game over");
		document.getElementById("chkAutoPlay").checked=false;
		// if(document.getElementById("SideToMove").value==="white") {
		if (game.turn() === 'w') {
			WrapUp('0-1');
		} else {
			WrapUp('1-0');
		}
		game.header('Termination', 'Normal');
		return;
	}
	
	if(game.in_threefold_repetition()) {
		console.log("Draw -- Three Fold repetition");
		alert("Draw -- Three Fold repetition");
		WrapUp('1/2-1/2');
		game.header('Termination', 'Adjudication: 3-fold repetition');
		document.getElementById("chkAutoPlay").checked=false;
		return;
	}
	if(game.insufficient_material()) {
		console.log("Draw -- Insufficient Material");
		document.getElementById("chkAutoPlay").checked=false;
		WrapUp('1/2-1/2');
		game.header('Termination', 'Adjudication: Insufficient material');
		return;
	}
	if(game.in_stalemate()) {
		console.log("Game in draw -- Stalemate");
		document.getElementById("chkAutoPlay").checked=false;
		game.header('Termination', 'Adjudication: Stalemate');
		WrapUp('1/2-1/2');
		return;
	}
	if(game.in_draw()) {
		console.log("Game in draw");
		document.getElementById("chkAutoPlay").checked=false;
		WrapUp('1/2-1/2');
		game.header('Termination', 'Adjudication');
		return;
	}



//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	if(document.getElementById("chkAutoPlay").checked) {		
		if(document.getElementById("bottomside_engine").value==="") {
			console.log("Waiting for human to move");
			document.getElementById("btnStartEngine").disabled=true;
			return;
		} else {
			StartEngine();
		}
	} else {
		console.log("Game paused");
		document.getElementById("btnStartEngine").disabled=false;
	}
}

function today() {
	var today = new Date();
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
	var yyyy = today.getFullYear();
	today = yyyy+"."+mm +"."+dd;
	return today;
}

function ProgressBar() {
	var pct=(100*attempt/patience);
	var bar='<div class="w3-red" style="height:4px;width:'+ pct +'%"></div>';
	if(ORIENTATION) {
		if(document.getElementById("SideToMove").value==="black") {
			document.getElementById('top_progressbar').innerHTML=bar;
		} else {
			document.getElementById('bottom_progressbar').innerHTML=bar;
		}
	} else {
		if(document.getElementById("SideToMove").value==="white") {
			document.getElementById('bottom_progressbar').innerHTML=bar;
		} else {
			document.getElementById('top_progressbar').innerHTML=bar;
		}
	}
}

function WrapUp(result) {
	game.header('Date',today());
	if(ORIENTATION) {
		game.header('White', document.getElementById('bottomside_engine').value);
		game.header('Black', document.getElementById('topside_engine').value);

		
	} else {
		game.header('White', document.getElementById('topside_engine').value);
		game.header('Black', document.getElementById('bottomside_engine').value);	
	}
	game.header('result', result);
	$pgn.html(game.pgn())
}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ DECLARATION ~~~~~~~~~~~~~~~~~~~~~
var FEN;
var ORIENTATION=true;
var min_waittime=3000;
var max_waittime=12000;
var timerVar;
var patience=15;
var attempt=0;
var image="Rider.gif";
var config = {
  draggable: true,
  position: 'start',
  onDragStart: onDragStart,
  onDrop: onDrop,
  moveSpeed: 'slow',
  pieceTheme: pieceTheme,
  onSnapEnd: onSnapEnd
}
var board = Chessboard('myBoard', config);
$(window).resize(board.resize);
updateStatus();

document.getElementById("btnApplyFEN").style.display = "none";


</script>