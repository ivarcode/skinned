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
		var MAP = {_0:new Image(),_1:new Image()};
		var CHAR = {_player:new Image(),_enemy:new Image()};
		var GAME_STATS = {_SPEED:30,_CLOCK:null,_PAUSED:true};
		var KEY_DATA = {_w_IS_PRESSED:false,_a_IS_PRESSED:false,_s_IS_PRESSED:false,_d_IS_PRESSED:false,_p_IS_PRESSED:false};

		/*MAP IMGS*/
		MAP._0.src = "./img/map/map_0.png";
		MAP._1.src = "./img/map/map_1.png";
		
		/*CHAR IMGS*/
		CHAR._player.src = "./img/player.png";
		CHAR._enemy.src = "./img/enemy.png";

		/*LOCATION DATA*/
		var current_chunk = null;
		var camera_chunk = null;
		var camera_origin = {x:100,y:100};
		var player = new Entity({x:550,y:550},10,100,60,60,CHAR._player,"player");

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

			drawMenu();
			

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
				/*KEYPRESSED == 'P'*/
				if (keycode == 80) {
					KEY_DATA._p_IS_PRESSED = true;
					// pause/unpause
					GAME_STATS._PAUSED = !GAME_STATS._PAUSED;
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
				/*KEYPRESSED == 'P'*/
				if (keycode == 80) {
					KEY_DATA._p_IS_PRESSED = false;
				}
			});

			// start timing function at end of setup
			GAME_START_TIME = new Date();
			console.log("Game begins "+GAME_START_TIME+".");
			tick();
		}

		/*timing functions*/
		function tick() {
			// console.log("tick");

			setTimeout(tick,GAME_STATS._SPEED);
			// console.log("tock");

			if (!GAME_STATS._PAUSED) {
				// print_coordinates(current_chunk.coordinates)
				// print_coordinates(player.coordinates);
				movePlayer();
				generateNecessaryChunks();
				enemyChoices();
				setCamera();
				setClock();
				spawnEnemies();


				draw();
			} else {
				// TODO have pause timer run / stop and save the ongoing timer
				drawMenu();
			}
			
		}

		/*function responsible for all choices involving enemy AI (moving, shooting, etc) in the surrounding and current chunk (9 total chunks)*/
		function enemyChoices() {
			var active_chunks = [];
			active_chunks[active_chunks.length] = current_chunk;
			active_chunks[active_chunks.length] = current_chunk.neighbors._N;
			active_chunks[active_chunks.length] = current_chunk.neighbors._S;
			active_chunks[active_chunks.length] = current_chunk.neighbors._W;
			active_chunks[active_chunks.length] = current_chunk.neighbors._E;
			active_chunks[active_chunks.length] = current_chunk.neighbors._N.neighbors._W;
			active_chunks[active_chunks.length] = current_chunk.neighbors._N.neighbors._E;
			active_chunks[active_chunks.length] = current_chunk.neighbors._S.neighbors._W;
			active_chunks[active_chunks.length] = current_chunk.neighbors._S.neighbors._E;
			// loop through each chunk
			for (var i = 0; i < active_chunks.length; i++) {
				// loop through each entity in chunk
				for (var j = 0; j < active_chunks[i].entities.length; j++) {
					if (active_chunks[i].entities[j].type == "enemy") {
						// TODO
					}
				}
			}
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
			// checking X lower bound
			var temp = player.get_X()-camera_origin.x;
			if (temp < 0) {
				if (temp+1100 < 200) {
					camera_origin = {x:camera_origin.x-(200-(temp+1100)),y:camera_origin.y};
				}
			} else if (temp < 200) {
				camera_origin = {x:camera_origin.x-(200-temp),y:camera_origin.y};
			}
			// checking X upper bound
			temp = player.get_X()-camera_origin.x;
			if (temp < 0) {
				if (temp+1100 >= 700) {
					camera_origin = {x:camera_origin.x+((temp+1100)-700),y:camera_origin.y};
				}
			} else if (temp >= 700) {
				camera_origin = {x:camera_origin.x+(temp-700),y:camera_origin.y};
			}
			// checking Y lower bound
			temp = player.get_Y()-camera_origin.y;
			if (temp < 0) {
				if (temp+1100 < 200) {
					camera_origin = {x:camera_origin.x,y:camera_origin.y-(200-(temp+1100))};
				}
			} else if (temp < 200) {
				camera_origin = {x:camera_origin.x,y:camera_origin.y-(200-temp)};
			}
			// checking Y upper bound
			temp = player.get_Y()-camera_origin.y;
			if (temp < 0) {
				if (temp+1100 >= 700) {
					camera_origin = {x:camera_origin.x,y:camera_origin.y+((temp+1100)-700)};
				}
			} else if (temp >= 700) {
				camera_origin = {x:camera_origin.x,y:camera_origin.y+(temp-700)};
			}
			// chunk switching when necessary
			if (camera_origin.x < 0) {
				camera_chunk = camera_chunk.neighbors._W;
				camera_origin.x += 1100;
			}
			if (camera_origin.x >= 1100) {
				camera_chunk = camera_chunk.neighbors._E;
				camera_origin.x -= 1100;
			}
			if (camera_origin.y < 0) {
				camera_chunk = camera_chunk.neighbors._N;
				camera_origin.y += 1100;
			}
			if (camera_origin.y >= 1100) {
				camera_chunk = camera_chunk.neighbors._S;
				camera_origin.y -= 1100;
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

		/*function responsible for creating enemies in the game*/
		function spawnEnemies() {
			var neighbor_chunks = [];
			neighbor_chunks[neighbor_chunks.length] = current_chunk.neighbors._N;
			neighbor_chunks[neighbor_chunks.length] = current_chunk.neighbors._S;
			neighbor_chunks[neighbor_chunks.length] = current_chunk.neighbors._W;
			neighbor_chunks[neighbor_chunks.length] = current_chunk.neighbors._E;
			neighbor_chunks[neighbor_chunks.length] = current_chunk.neighbors._N.neighbors._E;
			neighbor_chunks[neighbor_chunks.length] = current_chunk.neighbors._N.neighbors._W;
			neighbor_chunks[neighbor_chunks.length] = current_chunk.neighbors._S.neighbors._E;
			neighbor_chunks[neighbor_chunks.length] = current_chunk.neighbors._S.neighbors._W;
			// loop through and generate spawns
			for (var i = 0; i < neighbor_chunks.length; i++) {
				var rand = Math.floor(Math.random()*100);
				// console.log(rand);
				if (rand == 0) {
					var x = Math.floor(Math.random()*1100);
					var y = Math.floor(Math.random()*1100);
					neighbor_chunks[i].entities[neighbor_chunks[i].entities.length] = new Entity({x:x,y:y},3,20,60,60,CHAR._enemy,"enemy");
				}
			}
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
						MAP_ID = camera_chunk.data[i][j];
						coordinates = camera_chunk.coordinates.x+","+camera_chunk.coordinates.y;
					} else {
						if (i < 11) {
							MAP_ID = camera_chunk.neighbors._S.data[i][j-11];
							coordinates = camera_chunk.neighbors._S.coordinates.x+","+camera_chunk.neighbors._S.coordinates.y;
						} else if (j < 11) {
							MAP_ID = camera_chunk.neighbors._E.data[i-11][j];
							coordinates = camera_chunk.neighbors._E.coordinates.x+","+camera_chunk.neighbors._E.coordinates.y;
						} else {
							MAP_ID = camera_chunk.neighbors._S.neighbors._E.data[i-11][j-11];
							coordinates = camera_chunk.neighbors._S.neighbors._E.coordinates.x+","+camera_chunk.neighbors._S.neighbors._E.coordinates.y;
						}
					}
					/*setting image to the proper map image from game.map*/
					switch (MAP_ID) {
						case 0: image = MAP._0;break;
						case 1: image = MAP._1;break;
						default:break;
					}
					/*calculating coordinates to draw the img to based on the camera_origin*/
					var draw_x = (i*100)-(camera_origin.x);
					var draw_y = (j*100)-(camera_origin.y);
					// console.log(draw_x+" "+draw_y);
					// console.log(image);
					context.drawImage(image,draw_x,draw_y);
					// draw coordinates (used for debugging only)
					// context.fillText(coordinates,draw_x+35,draw_y+60);
				}
			}
			/*draws entities*/
			var active_chunks = [];
			active_chunks[active_chunks.length] = current_chunk;
			active_chunks[active_chunks.length] = current_chunk.neighbors._N;
			active_chunks[active_chunks.length] = current_chunk.neighbors._S;
			active_chunks[active_chunks.length] = current_chunk.neighbors._W;
			active_chunks[active_chunks.length] = current_chunk.neighbors._E;
			active_chunks[active_chunks.length] = current_chunk.neighbors._N.neighbors._W;
			active_chunks[active_chunks.length] = current_chunk.neighbors._N.neighbors._E;
			active_chunks[active_chunks.length] = current_chunk.neighbors._S.neighbors._W;
			active_chunks[active_chunks.length] = current_chunk.neighbors._S.neighbors._E;
			// console.log(active_chunks);
			for (var i = 0; i < active_chunks.length; i++) {
				if (active_chunks[i].coordinates.x == camera_chunk.coordinates.x && active_chunks[i].coordinates.y == camera_chunk.coordinates.y) {
					// same chunk as camera chunk, draw without displacing
					for (var j = 0; j < active_chunks[i].entities.length; j++) {
						var x = active_chunks[i].entities[j].get_X()-((active_chunks[i].entities[j].width/2)+camera_origin.x);
						var y = active_chunks[i].entities[j].get_Y()-((active_chunks[i].entities[j].height/2)+camera_origin.y);
						context.drawImage(active_chunks[i].entities[j].img,x,y);
					}
				} else {
					// different chunk, calculate displacement, then draw
					for (var j = 0; j < active_chunks[i].entities.length; j++) {
						var x = active_chunks[i].entities[j].get_X()-((active_chunks[i].entities[j].width/2)+camera_origin.x);
						var y = active_chunks[i].entities[j].get_Y()-((active_chunks[i].entities[j].height/2)+camera_origin.y);
						// adjust x and y for chunk difference
						x += ((active_chunks[i].coordinates.x-camera_chunk.coordinates.x)*1100);
						y += ((active_chunks[i].coordinates.y-camera_chunk.coordinates.y)*1100);
						context.drawImage(active_chunks[i].entities[j].img,x,y);
					}
				}
			}
			// context.drawImage(CHAR._player,player.get_X()-(camera_origin.x+30),player.get_Y()-(camera_origin.y+30));
			/*drawing clock*/
			context.fillStyle = "#900101";
			context.fillRect(30,36,player.health_points,15);
			context.fillStyle = "#FFFFFF";
			context.font = "20px lucida console";
			context.fillText(player.health_points+" HP",40+player.health_points,50);
			context.fillText(GAME_STATS._CLOCK,730,50);
			// context.fillText(camera_origin.x+","+camera_origin.y,0,30);
		}

		/*function responsible for drawing the menu when the game is paused*/
		function drawMenu() {
			context.fillStyle = "#FFFFFF";
			context.font = "20px lucida console";
			context.fillText("press 'P' to play / pause",300,500);
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