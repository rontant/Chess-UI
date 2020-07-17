<meta name="viewport" content="width=device-width, initial-scale=1">
<!----------------- Top bar division ----------------------------------------->
<div class="w3-container w3-black">

	 <!--&nbsp;&nbsp;You are -->
	 
	 &nbsp;
	 Side to move <input type="text" id="SideToMove" value="white" size="3" readonly>
	 &nbsp; &nbsp; &nbsp; &nbsp;
	 <span id='fullmove' class='w3-badge w3-blue'>1</span>
	 
	<div class="w3-dropdown-hover w3-right">
    <button class="w3-button w3-black"><img height=15 src="3-bar-menu-icon-6.png"></button>
    <div class="w3-dropdown-content w3-bar-block w3-border" style="right:0">
      <a href="#" class="w3-bar-item w3-button" onClick="FlipBoard()">Flip Board</a>	  	
      <a href="#" class="w3-bar-item w3-button" onClick="Option_Cancel()">Cancel move</a>
      <a href="#" class="w3-bar-item w3-button" onClick="Option_Reset()">Reset Board</a>
	  <a href="#" class="w3-bar-item w3-button" onClick="SavePgn()">Save to PGN</a>
	  <a href="#" class="w3-bar-item w3-button" onClick="document.getElementById('id01').style.display='block'">View PGN</a>
	  <a href="#" class="w3-bar-item w3-button" onClick="document.getElementById('id02').style.display='block'">Engine Log</a>
    </div>
	</div>
</div>
<!------------------------- End of Top bar division ---------------------->

<p>
<!------------------------- CHESS BOARD division ---------------------->


	<header class="w3-container w3-black" style="height:23px">	
		<?php 		
		$element_id='topside_engine';
		$top_choice='';
		include "getavailableengines.php" ;
		?>
		<span id="top_dot"></span>
		
	</header>
	<header id="top_progressbar" class="w3-black" style="height:4px">
				<div class="w3-black" style="height:2px;width:0%"></div>
	</header>
		
	<div class="w3-card" id="myBoard" style="width: 100%"></div>
	
	<footer class="w3-container w3-black" style="height:23px"> 	
		<?php 		
		$element_id='bottomside_engine';
		$top_choice='Human';
		include 'getavailableengines.php' ;
		?>


		<span id="bottom_dot"></span>
	</footer>
	<footer id="bottom_progressbar" class="w3-black" style="height:4px">
				<div class="w3-red" style="height:2px;width:0%"></div>
	</footer>
	<!--input type="hidden" id="user_color" value="white"-->

<!------------------------- End of Chess board division ---------------------->




<div class="w3-container">

<span class="w3-container">FEN:
<input onclick="document.getElementById('btnApplyFEN').style.display = 'block';" class="w3-white w3-border w3-tiny w3-rest" id="fen" style="width:60%" >
<button onclick="ApplyFEN()" id="btnApplyFEN" class="w3-tiny w3-button w3-border w3-white w3-right" style="width:15%">Apply
</button>
</span>
<p>
<button id='btnStartEngine' onclick="StartEngine()" disabled >Start Engine</button>
Auto-play <input id='chkAutoPlay' type="checkbox" >
</div>



<!--- Modals ---->

<div id="id01" class="w3-modal">
    <div class="w3-modal-content">
		<header class="w3-container w3-teal"><h3>PGN</h3>
			<span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-display-topright">X</span>
		</header>
		<textarea rows=5 id="pgn" style="width:100%" class="w3-tiny" disabled></textarea>
	</div>
</div>

<!--- Modals ---->
<div id="id02" class="w3-modal">
    <div class="w3-modal-content">
		<header class="w3-container w3-teal"><h3>Engine Log</h3>
			<span onclick="document.getElementById('id02').style.display='none'" class="w3-button w3-display-topright">X</span>
		</header>
		<div id='status' >
		<input type="hidden" id="bestmove" style="width:100%">
		</div>
	</div>
</div>





