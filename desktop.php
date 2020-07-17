<?php 

?>

<div style="overflow-x:auto;">

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
    </div>
	</div>
</div>
<!------------------------- End of Top bar division ---------------------->

<p>
<!------------------------- CHESS BOARD division ---------------------->
<div class="w3-row ">
<div text-align:center; class="w3-container w3-half">
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
</div>
<!------------------------- End of Chess board division ---------------------->






<!------------------------- End of Right Pane division ---------------------->


