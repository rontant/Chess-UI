<html>

<head>
<!--meta name="viewport" content="width=device-width, initial-scale=1"-->
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<script src="../chessboard/jquery.min.js"></script>
<script src="../chess.js/chess.js"></script>

<link rel="stylesheet" href="../chessboard/css/chessboard-1.0.0.css">

<title>Chess</title>
</head>
<body bgcolor="#a6a6a6">
<script src="../chessboard/loaddoc.js"></script>
<!--script src="../chessboard/mychess.js"></script-->
<script src="../chessboard/FileSaver.js"></script>
<script src="../chessboard/js/chessboard-1.0.0.js"></script>

<?php include "desktop.php"; ?>


<div class="w3-container w3-half">
<header class="w3-container w3-black">PGN</header>
<textarea rows=5 id="pgn" style="width:100%" class="w3-tiny" disabled></textarea>

<span class="w3-container">FEN:
<input onclick="document.getElementById('btnApplyFEN').style.display = 'block';" class="w3-white w3-border w3-tiny w3-rest" id="fen" style="width:75%" >
<button onclick="ApplyFEN()" id="btnApplyFEN" class="w3-tiny w3-button w3-border w3-white w3-right" style="width:15%">Apply
</button>
</span>
<p>
<button id='btnStartEngine' onclick="StartEngine()" disabled >Start Engine</button>
Auto-play <input id='chkAutoPlay' type="checkbox" >

<div id='status' >
  <input type="hidden" id="bestmove" style="width:100%">
</div>

</div>


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
	timerVar=setInterval(ProcessMove , 2000); // do not put () for the function here

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
		return;
	}
	clearInterval(timerVar);
	console.log("Engine moved "+bestmove);
	
	var time = new Date();
	//console.log("Time: " + dt.getTime() );
	console.log("~~~~~~~~~~~~~~~~~~~~~~~~~~");		
	console.log("@" +
						time.getHours() + ":" +
						time.getMinutes() + ":" + 
						time.getSeconds());
	console.log("~~~~~~~~~~~~~~~~~~~~~~~~~~");

		 
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
		return;
	}
	
	if(game.in_threefold_repetition()) {
		console.log("Draw -- Three Fold repetition");
		alert("Draw -- Three Fold repetition");
		WrapUp('1/2-1/2');
		document.getElementById("chkAutoPlay").checked=false;
		return;
	}
	if(game.insufficient_material()) {
		console.log("Draw -- Insufficient Material");
		document.getElementById("chkAutoPlay").checked=false;
		WrapUp('1/2-1/2');
		return;
	}
	if(game.in_draw()) {
		console.log("Game in draw");
		document.getElementById("chkAutoPlay").checked=false;
		WrapUp('1/2-1/2');
		return;
	}
	if(game.in_stalemate()) {
		console.log("Game in draw -- Stalemate");
		document.getElementById("chkAutoPlay").checked=false;
		WrapUp('1/2-1/2');
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
var patience=10;
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