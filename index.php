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

		/*CONSTANTS*/
		var GAME_START_TIME = null;

		/*DATA*/
		var MAP = {_0:new Image()};
		var CHAR = {_player:new Image()};
		var PLAYER_STATS = {_SPEED:5};
		var GAME_STATS = {_SPEED:10,_CLOCK:null};
		var KEY_DATA = {_w_IS_PRESSED:false,_a_IS_PRESSED:false,_s_IS_PRESSED:false,_d_IS_PRESSED:false};

		/*MAP IMGS*/
		MAP._0.src = "./img/map/map_0.png";

		/*CHAR IMGS*/
		CHAR._player.src = "./img/player.png";

		/*LOCATION DATA*/
		var current_chunk = null;
		var camera_origin = {x:100,y:100};
		var player_location = {x:550,y:550};

		var game = new Game();
		current_chunk = game.chunks[0];
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
			GAME_START_TIME = new Date();
			console.log("Game begins "+GAME_START_TIME+".");
			tick();
		}

		/*timing function*/
		function tick() {
			// console.log("tick");
			movePlayer();
			setCamera();
			setClock();
			generateNecessaryChunks();

			// print_coordinates(player_location);

			draw();
			var t = setTimeout(tick,GAME_STATS._SPEED);
		}

		/*function responsible for generating the chunks adj to current_chunk if they do not already exist*/
		function generateNecessaryChunks() {
			// this function generates all chunks within one x and y coordinate away, so all 8 'squares' around the current_chunk
			var coord = current_chunk.coordinates;
			// print_coordinates(coord);
			if (current_chunk.neighbors._N == null) {
				add_chunk(game,new Chunk({x:coord.x,y:coord.y-1}));
			}
			if (current_chunk.neighbors._S == null) {
				add_chunk(game,new Chunk({x:coord.x,y:coord.y+1}));
			}
			if (current_chunk.neighbors._W == null) {
				add_chunk(game,new Chunk({x:coord.x-1,y:coord.y}));
			}
			if (current_chunk.neighbors._E == null) {
				add_chunk(game,new Chunk({x:coord.x+1,y:coord.y}));
			}
			if (current_chunk.neighbors._N.neighbors._E == null) {
				add_chunk(game,new Chunk({x:coord.x+1,y:coord.y-1}));
			}
			if (current_chunk.neighbors._N.neighbors._W == null) {
				add_chunk(game,new Chunk({x:coord.x-1,y:coord.y-1}));
			}
			if (current_chunk.neighbors._S.neighbors._E == null) {
				add_chunk(game,new Chunk({x:coord.x+1,y:coord.y+1}));
			}
			if (current_chunk.neighbors._S.neighbors._W == null) {
				add_chunk(game,new Chunk({x:coord.x-1,y:coord.y+1}));
			}
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
			if (player_location.x >= 1100) {
				player_location.x -= 1100;
			}
			if (player_location.x < 0) {
				player_location.x += 1100;
			}
			if (player_location.y >= 1100) {
				player_location.y -= 1100;
			}
			if (player_location.y < 0) {
				player_location.y += 1100;
			}
		}

		/*function responsible for adjusting the camera when the player is out of range*/
		function setCamera() {
			// print_coordinates(player_location);
			print_coordinates(camera_origin);
			var player_max = 700;
			var player_min = 200;
			// canv_loc is the distance from the corner of the canvas that the player is being drawn
			var canv_loc = {x:player_location.x-camera_origin.x,y:player_location.y-camera_origin.y};
			// conditions regarding player location being close to the edge
			if (canv_loc.x > player_max) {
				var diff = canv_loc.x-player_max;
				camera_origin.x = camera_origin.x + diff;
			}
			if (canv_loc.x < player_min) {
				var diff = canv_loc.x-player_min;
				camera_origin.x = camera_origin.x + diff;
			}
			if (canv_loc.y > player_max) {
				var diff = canv_loc.y-player_max;
				camera_origin.y = camera_origin.y + diff;
			}
			if (canv_loc.y < player_min) {
				var diff = canv_loc.y-player_min;
				camera_origin.y = camera_origin.y + diff;
			}
			if (camera_origin.x >= 1100) {
				camera_origin.x -= 1100;
				current_chunk = current_chunk.neighbors._E;
			}
			if (camera_origin.x < 0) {
				camera_origin.x += 1100;
				current_chunk = current_chunk.neighbors._W;
			}
		}

		/*function responsible for setting the game clock after each tick*/
		function setClock() {
			var today = new Date();
			// a is the total milliseconds of the current day time
			var a = today.getHours()*3600000;
			a += today.getMinutes()*60000;
			a += today.getSeconds()*1000;
			a += today.getMilliseconds();
			// b is the total milliseconds of the GAME_START_TIME
			var b = GAME_START_TIME.getHours()*3600000;
			b += GAME_START_TIME.getMinutes()*60000;
			b += GAME_START_TIME.getSeconds()*1000;
			b += GAME_START_TIME.getMilliseconds();
			// c is the difference
			c = a-b;
			// if c is negative, add total milliseconds in 24hrs
			if (c < 0) {
				c += 86400000;
			}
			// parsing the data to display by h, m, s, and ms
			var h = parseInt(c/3600000);
			var m = parseInt((c%3600000)/60000);
			var s = parseInt(((c%3600000)%60000)/1000);
			var ms = ((c%3600000)%60000)%1000;
			var time = "";
			time += h+":";
			// adding a 0 if m < 10
			if (m < 10) {
				time += "0";
			}
			time += m+":";
			// adding a 0 if s < 10
			if (s < 10) {
				time += "0";
			}
			time += s+":";
			// adding one 0 if ms < 10, two if ms < 100
			if (ms < 100) {
				time += "0";
			}
			if (ms < 10) {
				time += "0";
			}
			time += ms;
			GAME_STATS._CLOCK = time;
		}

		/*function responsible for drawing the game after each tick*/
		function draw() {
			context.globalAlpha = 1;
			context.restore();
			/*for loop to draw the map*/
			for (var i = parseInt(camera_origin.x/100); i < parseInt((camera_origin.x/100)+10); i++) {
				for (var j = parseInt(camera_origin.y/100); j < parseInt((camera_origin.y/100)+10); j++) {
					var image = null;
					var coordinates = null;
					var MAP_ID = null;
					// setting the MAP_ID temp var for use in img selection
					if (i < 11 && j < 11) {
						MAP_ID = current_chunk.data[i][j];
						coordinates = current_chunk.coordinates.x+","+current_chunk.coordinates.y;
					} else {
						if (i < 11) {
							MAP_ID = current_chunk.neighbors._S.data[i][j-11];
							coordinates = current_chunk.neighbors._S.coordinates.x+","+current_chunk.neighbors._S.coordinates.y;
						} else if (j < 11) {
							MAP_ID = current_chunk.neighbors._E.data[i-11][j];
							coordinates = current_chunk.neighbors._E.coordinates.x+","+current_chunk.neighbors._E.coordinates.y;
						} else {
							MAP_ID = current_chunk.neighbors._S.neighbors._E.data[i-11][j-11];
							coordinates = current_chunk.neighbors._S.neighbors._E.coordinates.x+","+current_chunk.neighbors._S.neighbors._E.coordinates.y;
						}
					}
					/*setting image to the proper map image from game.map*/
					switch (MAP_ID) {
						case 0: image = MAP._0;break;
						default:break;
					}
					/*calculating coordinates to draw the img to based on the camera_origin*/
					var draw_x = (i*100)-(camera_origin.x);
					var draw_y = (j*100)-(camera_origin.y);
					// console.log(draw_x+" "+draw_y);
					// console.log(image);
					context.drawImage(image,draw_x,draw_y);
					// draw coordinates (used for debugging only)
					context.fillText(coordinates,draw_x+35,draw_y+60);
				}
			}
			/*draws player in at player_location*/
			// print_coordinates(player_location);
			context.drawImage(CHAR._player,player_location.x-(camera_origin.x+30),player_location.y-(camera_origin.y+30));
			/*drawing clock*/
			context.fillStyle = "#FFFFFF";
			context.font = "20px lucida console";
			context.fillText(GAME_STATS._CLOCK,730,50);
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