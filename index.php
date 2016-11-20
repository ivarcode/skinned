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
		var CHAR = {_player:new Image()};

		MAP._0.src = "./img/map/map_0.png";

		CHAR._player.src = "./img/player.png";

		var map_size = {x:1100,y:1100};
		var map_origin = {x:100,y:100};
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
			/*for loop to draw the map*/
			for (var i = parseInt(map_origin.x/100); i < parseInt((map_origin.x/100)+10); i++) {
				for (var j = parseInt(map_origin.y/100); j < parseInt((map_origin.y/100)+10); j++) {
					var image = null;
					/*setting image to the proper map image from game.map*/
					switch(game.map[i][j]) {
						case 0: image = MAP._0;break;
						default:break;
					}
					/*calculating coordinates to draw the img to based on the map_origin*/
					var draw_x = (i*100)-(map_origin.x);
					var draw_y = (j*100)-(map_origin.y);
					context.drawImage(image,draw_x,draw_y);
				}
			}
			/*draws player in at player_location*/
			context.drawImage(CHAR._player,player_location.x-(map_origin.x+30),player_location.y-(map_origin.y+30));
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