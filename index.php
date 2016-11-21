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
		var PLAYER_STATS = {_SPEED:5};
		var GAME_STATS = {_SPEED:10};
		var KEY_DATA = {_w_IS_PRESSED:false,_a_IS_PRESSED:false,_s_IS_PRESSED:false,_d_IS_PRESSED:false};

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

			/*GAME CONTROLS
			window eventlisteners for keydown and keyup*/
			window.addEventListener("keydown",function(events) {
				var keycode = events.keyCode;
				// console.log(keycode);
				/*KEYPRESSED == 'W'*/
				if (keycode == 87) {
					KEY_DATA._w_IS_PRESSED = true;
				}
				/*KEYPRESSED == 'A'*/
				if (keycode == 65) {
					KEY_DATA._a_IS_PRESSED = true;
				}
				/*KEYPRESSED == 'S'*/
				if (keycode == 83) {
					KEY_DATA._s_IS_PRESSED = true;
				}
				/*KEYPRESSED == 'D'*/
				if (keycode == 68) {
					KEY_DATA._d_IS_PRESSED = true;
				}
			});
			window.addEventListener("keyup",function(events) {
				var keycode = events.keyCode;
				// console.log(keycode);
				/*KEYPRESSED == 'W'*/
				if (keycode == 87) {
					KEY_DATA._w_IS_PRESSED = false;
				}
				/*KEYPRESSED == 'A'*/
				if (keycode == 65) {
					KEY_DATA._a_IS_PRESSED = false;
				}
				/*KEYPRESSED == 'S'*/
				if (keycode == 83) {
					KEY_DATA._s_IS_PRESSED = false;
				}
				/*KEYPRESSED == 'D'*/
				if (keycode == 68) {
					KEY_DATA._d_IS_PRESSED = false;
				}
			});

			// start timing function at end of setup
			console.log("Game begins now.")
			tick();
		}

		/*timing function*/
		function tick() {
			// console.log("tick");
			movePlayer();

			draw();
			var t = setTimeout(tick,GAME_STATS._SPEED);
		}

		/*function responsible for manipulating the player*/
		function movePlayer() {
			/*if 'W' key is down*/
			if (KEY_DATA._w_IS_PRESSED) {
				// move player "up"
				player_location.y = player_location.y-PLAYER_STATS._SPEED;
			}
			/*if 'A' key is down*/
			if (KEY_DATA._a_IS_PRESSED) {
				// move player "left"
				player_location.x = player_location.x-PLAYER_STATS._SPEED;
			}
			/*if 'S' key is down*/
			if (KEY_DATA._s_IS_PRESSED) {
				// move player "down"
				player_location.y = player_location.y+PLAYER_STATS._SPEED;
			}
			/*if 'D' key is down*/
			if (KEY_DATA._d_IS_PRESSED) {
				// move player "right"
				player_location.x = player_location.x+PLAYER_STATS._SPEED;
			}
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