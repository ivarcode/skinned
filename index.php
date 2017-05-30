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

	<script type="text/javascript" src="./js/level.js"></script>
	<script type="text/javascript" src="./js/slice.js"></script>
	<script type="text/javascript" src="./js/chunk.js"></script>
	<script type="text/javascript" src="./js/entity.js"></script>
	
	<script type="text/javascript">


		/*LOCATION DATA*/
		var camera = {slice_index:null,chunk_index:null,x:null,y:null};

		var GAME_START_TIME = null;

		var BG_IMG = new Image();
		var MAP = {_0:new Image(),_1:new Image()};
		var CHAR = {_player:new Image(),_enemy:new Image()};

		/*DATA*/
		var GAME_STATS = {_LEVEL:0,_SPEED:3000,_CLOCK:null,_TOTAL_GAME_TIME_PAUSED:0,_PAUSE_CLOCK:null,_PAUSED:false/*game currently in dev so this var can change based on whether i want the game to start right away or wait for me to press 'p'*/};
		var KEY_DATA = {_w_IS_PRESSED:false,_a_IS_PRESSED:false,_s_IS_PRESSED:false,_d_IS_PRESSED:false,_p_IS_PRESSED:false};

		BG_IMG.src = "./img/8b8d7fb.jpg";

		/*MAP IMGS*/
		MAP._0.src = "./img/map/map_0_9.png";
		MAP._1.src = "./img/map/map_1_9.png";
		
		/*CHAR IMGS*/
		CHAR._player.src = "./img/player.png";
		CHAR._enemy.src = "./img/enemy.png";

		var player = new Entity({slice:51,chunk:27,x:50,y:50},10,100,90,50,CHAR._player,"player");



		var levels = [];
		levels[0] = new Level();

		camera.slice_index = 50;
		camera.chunk_index = 24;
		camera.x = 0;
		camera.y = 0;


		function setup() {
			// initialize game
			game_canvas = document.getElementById("game");
			// disables default context menu on rightclick on game_canvas
			game_canvas.oncontextmenu = function(events) {
    			events.preventDefault();
    		};
			context = game_canvas.getContext("2d");

			draw_menu();

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
					GAME_STATS._PAUSE_CLOCK = new Date();
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

		/*function responsible for drawing the game after each tick*/
		function draw() {
			context.globalAlpha = 1;
			context.restore();

			var level = levels[GAME_STATS._LEVEL];

			context.drawImage(BG_IMG,0,0,900,900);

			for (var i = 0; i < 120; i++) {
				for (var j = 0; j < 120; j++) {
					var sl = parseInt(i/11);
					var ch = parseInt(j/11);
					var x = (i%11);
					var y = (j%11);
					var data = level.slices[camera.slice_index+sl].chunks[camera.chunk_index+ch].data[y][x];
					var image = null;
					// console.log(sl+" "+ch+" "+i+" "+j+" "+x+" "+y);
					switch (data) {
						case 0: image = null;break;
						case 1: image = MAP._1;break;
						default: throw "no valid case for mapid "+data;break;
					}
					if (image != null) {
						context.drawImage(image,i*9-camera.x,j*9-camera.y,9,9);
					}
				}
			}
			// for (var i = camera.slice_index; i < camera.slice_index+10; i++) {
			// 	for (var j = camera.chunk_index; j < camera.chunk_index+10; j++) {
			// 		var coordinates = i+","+j;
			// 		context.fillText(coordinates,(i-camera.slice_index)*99,(j-camera.chunk_index)*99+15);
			// 	}
			// }

			console.log(player.coordinates);
			var ix = ((player.coordinates.slice-camera.slice_index)*99+player.coordinates.x)-camera.x;
			var iy = ((player.coordinates.chunk-camera.chunk_index)*99+player.coordinates.y)-camera.y;
			ix -= player.width/2;
			iy -= player.height/2;
			console.log(ix+" "+iy);
			context.drawImage(player.img,ix,iy,player.width,player.height);


			


			context.fillStyle = "#FFFFFF";
			context.font = "20px lucida console";
			// context.fillText(player.health_points+" HP",40+player.health_points,50);
			context.fillText(GAME_STATS._CLOCK,730,50);
		}
		/*function responsible for drawing the menu when the game is paused*/
		function draw_menu() {
			context.fillStyle = "#FFFFFF";
			context.font = "20px lucida console";
			context.fillText("press 'P' to play / pause",300,500);
		}
		

		/*function responsible for adjusting the camera when the player is out of range*/
		function set_camera() {





			
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
		function set_clock() {
			var rn = new Date();
			// a is the total milliseconds of the current day time
			var a = rn.getHours()*3600000;
			a += rn.getMinutes()*60000;
			a += rn.getSeconds()*1000;
			a += rn.getMilliseconds();
			// b is the total milliseconds of the GAME_START_TIME
			var b = GAME_START_TIME.getHours()*3600000;
			b += GAME_START_TIME.getMinutes()*60000;
			b += GAME_START_TIME.getSeconds()*1000;
			b += GAME_START_TIME.getMilliseconds();
			// c is the difference
			var c = a-b;
			// if c is negative, add total milliseconds in 24hrs
			if (c < 0) {
				c += 86400000;
			}
			c -= GAME_STATS._TOTAL_GAME_TIME_PAUSED;
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

		/*function responsible for controlling the game timer and tempo*/
		function tick(speed) {
			console.log("tick");
			setTimeout(tick,GAME_STATS._SPEED);
			// console.log(GAME_STATS._CLOCK);

			if (!GAME_STATS._PAUSED) {
				// set_camera();
				set_clock();
				draw();
			} else {
				// add right now in millisec - pauseclock in millisec
				var rn = new Date();
				// a is the total milliseconds of the current day time
				var a = rn.getHours()*3600000;
				a += rn.getMinutes()*60000;
				a += rn.getSeconds()*1000;
				a += rn.getMilliseconds();
				// b is the total milliseconds of the GAME_STATS._PAUSE_CLOCK
				var b = GAME_STATS._PAUSE_CLOCK.getHours()*3600000;
				b += GAME_STATS._PAUSE_CLOCK.getMinutes()*60000;
				b += GAME_STATS._PAUSE_CLOCK.getSeconds()*1000;
				b += GAME_STATS._PAUSE_CLOCK.getMilliseconds();
				// c is the difference
				var c = a-b;
				GAME_STATS._TOTAL_GAME_TIME_PAUSED += c;


				// set pauseclock to last pause tick
				GAME_STATS._PAUSE_CLOCK = new Date();
				draw_menu();
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