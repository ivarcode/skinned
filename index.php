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
	<script type="text/javascript" src="./js/chunk.js"></script>
	<script type="text/javascript" src="./js/entity.js"></script>
	
	<script type="text/javascript">

		/*CONSTANTS*/
		var GAME_START_TIME = null;

		/*DATA*/
		var MAP = {_0:new Image()};
		var CHAR = {_player:new Image()};
		var GAME_STATS = {_SPEED:10,_CLOCK:null};
		var KEY_DATA = {_w_IS_PRESSED:false,_a_IS_PRESSED:false,_s_IS_PRESSED:false,_d_IS_PRESSED:false};

		/*MAP IMGS*/
		MAP._0.src = "./img/map/map_0.png";

		/*CHAR IMGS*/
		CHAR._player.src = "./img/player.png";

		/*LOCATION DATA*/
		var current_chunk = null;
		var camera_chunk = null;
		var camera_origin = {x:100,y:100};
		var player = new Entity({x:550,y:550},5);

		var game = new Game();
		current_chunk = game.chunks[0];
		camera_chunk = current_chunk;
		current_chunk.add_entity(player);
		console.log(game);

		function setup() {
			game_canvas = document.getElementById("game");
			// disables default context menu on rightclick on game_canvas
			game_canvas.oncontextmenu = function(events) {
    			events.preventDefault();
    		};
			context = game_canvas.getContext("2d");

			// draw();

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
			// movement control
			/*if 'W' key is down*/
			if (KEY_DATA._w_IS_PRESSED) {
				// move player "up"
				player.move_north(player.get_speed());
			}
			/*if 'A' key is down*/
			if (KEY_DATA._a_IS_PRESSED) {
				// move player "left"
				player.move_west(player.get_speed());
			}
			/*if 'S' key is down*/
			if (KEY_DATA._s_IS_PRESSED) {
				// move player "down"
				player.move_south(player.get_speed());
			}
			/*if 'D' key is down*/
			if (KEY_DATA._d_IS_PRESSED) {
				// move player "right"
				player.move_east(player.get_speed());
			}
			// chunk switching when necessary
			if (player.get_X() < 0) {
				// switch to west chunk, move player object to proper chunk, and set coordinate properly
				current_chunk.remove_entity(player);
				current_chunk = current_chunk.neighbors._W;
				current_chunk.add_entity(player);
				player.coordinates.x = player.get_X()+1100;
			}
			if (player.get_X() >= 1100) {
				// switch to east chunk, move player object to proper chunk, and set coordinate properly
				current_chunk.remove_entity(player);
				current_chunk = current_chunk.neighbors._E;
				current_chunk.add_entity(player);
				player.coordinates.x = player.get_X()-1100;
			}
			if (player.get_Y() < 0) {
				// switch to north chunk, move player object to proper chunk, and set coordinate properly
				current_chunk.remove_entity(player);
				current_chunk = current_chunk.neighbors._N;
				current_chunk.add_entity(player);
				player.coordinates.y = player.get_Y()+1100;
			}
			if (player.get_Y() >= 1100) {
				// switch to south chunk, move player object to proper chunk, and set coordinate properly
				current_chunk.remove_entity(player);
				current_chunk = current_chunk.neighbors._S;
				current_chunk.add_entity(player);
				player.coordinates.y = player.get_Y()-1100;
			}
		}

		/*function responsible for adjusting the camera when the player is out of range*/
		function setCamera() {
			
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