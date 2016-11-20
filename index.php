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

		var MAP = {_0:new Image()};

		MAP._0.src = "./img/map/map_0.png";

		var map_size = {x:1100,y:1100};
		var player_location = {x:550,y:550};

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

		function draw() {
			context.globalAlpha = 1;
			context.restore();
			var x = player_location.x-450;
			var y = player_location.y-450;
			console.log(x+","+y);
			var mod_x = x%100;
			var mod_y = y%100;
			for (var i = 0; i < 9; i++) {
				for (var j = 0; j < 9; j++) {
					
				}
			}
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