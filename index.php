<!DOCTYPE html>
<!-- 
	Website code for skinned game project
	Currently a work-in-progress
	Until there is some real work done, there will be very limited documentation. It will come though.

	index.php
	Camden I. Wagner
	11/17/2016
-->

<html>
<head>
	<title>skinned</title>
	<link rel="stylesheet" href="style.css">

	<script type="text/javascript" src="./js/game.js"></script>
	
	<script type="text/javascript">

		var player_location = {x:500,y:500};

		var game = new Game();
		console.log(game);

		function setup() {
			game_canvas = document.getElementById("game");
			// disables default context menu on rightclick on game_canvas
			game_canvas.oncontextmenu = function(events) {
    			events.preventDefault();
    		};
			context = game_canvas.getContext("2d");

			draw();

			/*
			game_canvas.addEventListener('mousedown',function(events){
				
			});
			game_canvas.addEventListener('mouseup',function(events){

			});
			game_canvas.addEventListener('mouseenter',function(events){

			});
			game_canvas.addEventListener('mouseleave',function(events){
				
			});
			game_canvas.addEventListener('mousemove',function(events){
				
			});
			*/
		}

		window.addEventListener('load', setup, false);

	</script>
</head>
<body>

	<div id="frame">
		<center><canvas id="game" width="900" height="900">canvas</canvas></center>
	</div>

</body>
</html>