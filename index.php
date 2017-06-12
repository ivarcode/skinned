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
	<script type="text/javascript" src="./js/data.js"></script>
	<script type="text/javascript" src="./js/item.js"></script>
	
	<script type="text/javascript">


		/*LOCATION DATA*/
		var camera = {x:null,y:null};
		var mouse_over_canvas = false;
		var current_mouse_pos = {x:null,y:null};

		var GAME_START_TIME = null;

		var PIN_IMG = new Image();
		var BG_IMG = new Image();
		var MAP = {
			dirt:new Image(),
			stone:new Image(),
			grass:new Image()
		};
		var CHAR = {
			_player:new Image(),
			_enemy:new Image()
		};

		/*DATA*/
		var GAME_STATS = {
			_LEVEL:0,
			_SPEED:30/*should be 30*/,
			_CLOCK:null,
			_TOTAL_GAME_TIME_PAUSED:0,
			_PAUSE_CLOCK:null,
			_PAUSED:false/*game currently in dev so this var can change based on whether i want the game to start right away or wait for me to press 'p'*/
		};
		var KEY_DATA = {
			_w_IS_PRESSED:false,
			_a_IS_PRESSED:false,
			_s_IS_PRESSED:false,
			_d_IS_PRESSED:false,
			_p_IS_PRESSED:false,
			_space_IS_PRESSED:false
		};

		BG_IMG.src = "./img/8b8d7fb.jpg";
		PIN_IMG.src = "./img/map-pin.png";

		/*MAP IMGS*/
		MAP.dirt.src = "./img/map/dirt.png";
		MAP.stone.src = "./img/map/stone.png";
		MAP.grass.src = "./img/map/grass.png";
		
		/*CHAR IMGS*/
		CHAR._player.src = "./img/player.png";
		CHAR._enemy.src = "./img/enemy.png";

		/*animations*/
		var player_running_animation = [];
		player_running_animation.push(new Image());
		player_running_animation[0].src = "./img/player_running/0.png"
		player_running_animation.push(new Image());
		player_running_animation[1].src = "./img/player_running/1.png"
		player_running_animation.push(new Image());
		player_running_animation[2].src = "./img/player_running/2.png"

		var player = new Entity({x:1300,y:2000},10,100,150,50,CHAR._player,"player");
		var enemies = [];
		enemies.push(new Entity({x:1200,y:2000},10,100,120,50,CHAR._enemy,"enemy"));


		console.log("generating level...");
		var levels = [];
		levels[0] = generate_level_gym();
		
		console.log(get_torch());
		place_item_at(levels[0],get_torch(),1010,2850);

		set_lighting(levels[0]);
		// set_lighting_to(levels[0],1);

		// print_lighting(levels[0].lighting);


		camera.x = 0;
		camera.y = 0;

		var pins = [];


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
					player.face = 'l';
				}
				/*KEYPRESSED == 'S'*/
				if (keycode == 83) {
					KEY_DATA._s_IS_PRESSED = true;
				}
				/*KEYPRESSED == 'D'*/
				if (keycode == 68) {
					KEY_DATA._d_IS_PRESSED = true;
					player.face = 'r';
				}
				/*KEYPRESSED == 'P'*/
				if (keycode == 80) {
					KEY_DATA._p_IS_PRESSED = true;
					// pause/unpause
					GAME_STATS._PAUSED = !GAME_STATS._PAUSED;
					GAME_STATS._PAUSE_CLOCK = new Date();
				}
				/*KEYPRESSED == '(space)'*/
				if (keycode == 32) {
					KEY_DATA._space_IS_PRESSED = true;
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
				/*KEYPRESSED == '(space)'*/
				if (keycode == 32) {
					KEY_DATA._space_IS_PRESSED = false;
				}
			});
			/*window eventlisteners for mousedown and mouseup*/
			game_canvas.addEventListener('mousedown',function(events) {
				if (events.button === 0 /*left mousebutton pressed*/) {
					if (current_mouse_pos.x > player.coordinates.x-camera.x && player.face == 'l') {
						player.face = 'r';
					} else if (current_mouse_pos.x < player.coordinates.x-camera.x && player.face == 'r') {
						player.face = 'l';
					} else {
						// do nothing
					}
					player.strike();
				} else if (events.button === 2 /*right mousebutton pressed*/) {
					
				}
				mousedown = true;
			});

			game_canvas.addEventListener('mouseenter',function(events){
				mouse_over_canvas = true;
			});
			game_canvas.addEventListener('mouseleave',function(events){
				mouse_over_canvas = false;
			});
			game_canvas.addEventListener('mousemove',function(events){
				if (mouse_over_canvas) {
					current_mouse_pos = get_mouse_pos(game_canvas,events);
				}
			});

			// start timing function at end of setup
			GAME_START_TIME = new Date();
			console.log("Game begins "+GAME_START_TIME+".");
			tick();

		}

		function get_mouse_pos(canvas,events) {
			/*returns an object {x,y} that contain the mousePos data from events on the canvas*/
			var obj = canvas;
			var top = 0, left = 0;
			var mX = 0, mY = 0;
			while (obj && obj.tagName != 'BODY') {
				top += obj.offsetTop;
				left += obj.offsetLeft;
				obj = obj.offsetParent;
			}
			mX = events.clientX - left + window.pageXOffset;
			mY = events.clientY - top + window.pageYOffset;
			return { x: mX, y: mY };
		}

		/*function responsible for causing entity actions during gameplay*/
		function actions() {
			if (player.current_action != null) {
				switch (player.current_action) {
					// STRIKE
					case "strike": if (player.face == 'r') {
						for (var i = 0; i < enemies.length; i++) {
							if (contacts_hitbox(enemies[i],player.coordinates.x+(player.width/2),player.coordinates.y)) {
								enemies[i].momentum_horizontal += 3;
								console.log("strike!");
							}
						}
					} else if (player.face == 'l') {
						for (var i = 0; i < enemies.length; i++) {
							if (contacts_hitbox(enemies[i],player.coordinates.x-(player.width/2),player.coordinates.y)) {
								enemies[i].momentum_horizontal -= 3;
								console.log("strike!");
							}
						}
					} else {
						throw "ya gotta face somewhere";
					}
					// add_pin("strike pin",player.coordinates.x+(player.width/2),player.coordinates.y);
					break;
				}
				player.current_action = null;
			}
			
		}

		/*function adds a new pin to the pins array*/
		function add_pin(name,x,y) {
			pins.push({name:name,x:x,y:y});
		}

		/*function responsible for drawing the game after each tick*/
		function draw() {
			context.globalAlpha = 1;
			context.restore();

			var level = levels[GAME_STATS._LEVEL];
			// console.log(level);

			var map_width = 99*level.slices.length;
			var map_height = 99*level.slices[0].chunks.length;
			context.drawImage(BG_IMG,-(camera.x/map_width)*900,-(camera.y/map_height)*900,1800,1800);

			for (var i = 0; i < 101; i++) {
				for (var j = 0; j < 101; j++) {
					var sl = parseInt((camera.x+(i*9))/99);
					var ch = parseInt((camera.y+(j*9))/99);
					var x = parseInt(((camera.x+(i*9))%99)/9);
					var y = parseInt(((camera.y+(j*9))%99)/9);
					// console.log(i,j,sl,ch,x,y,camera);
					var data_id = null;
					try {
						data_id = level.slices[sl].chunks[ch].data[y][x].id;
						// console.log(data_id);
					} catch(e) {
						throw "unable to get data_id :: level.slices["+sl+"].chunks["+ch+"].data["+y+"]["+x+"]\n"+e;
					}
					var image = null;
					// console.log(sl+" "+ch+" "+i+" "+j+" "+x+" "+y);
					switch (data_id) {
						case 0: image = null;break;
						case 1: image = MAP.dirt;break;
						case 2: image = MAP.stone;break;
						case 3: image = MAP.grass;break;
						default: throw "no valid case for mapid "+data_id;break;
					}
					if (image != null) {
						context.drawImage(image,i*9-(camera.x%9),j*9-(camera.y%9),9,9);
					}
					// if (Number.isFinite(level)) {
					// 	context.globalAlpha = 1-(level.slices[sl].chunks[ch].data[y][x].light_level*0.1);
					// 	if (context.globalAlpha != 0) {
					// 		
					// 		context.fillRect(i*9-(camera.x%9),j*9-(camera.y%9),9,9);
					// 	}
					// }
					// context.globalAlpha = 1;
					// if (x == 0 && j == 0) {
					// 	// console.log("whats takin so long");
					// 	context.rect(i*9-camera.x,0,0,800);
						
					// }
				}
			}



			// reference chunk
			// context.fillStyle = "#FFFFFF";
			// var rc = ""+(1+parseInt(camera.x/99))+","+(1+parseInt(camera.y/99));
			// context.fillRect((99-camera.x%99)+parseInt(camera.x/99),(99-camera.x%99)+parseInt(camera.x/99),99,99);
			// context.stroke();





				// RIP SHADOW DATA
			// draw shadow
			// var shadow_data = context.createImageData(100,100);
			// for (var i = 0; i < shadow_data.data.length; i+=4) {
			// 	shadow_data[i] = 0;
			// 	shadow_data[i+1] = 0;
			// 	shadow_data[i+2] = 0;
			// 	shadow_data[i+3] = 1;
			// }


			// for (var x = 0; x < 900; x++) {
			// 	for (var y = 0; y < 900; y++) {
			// 		if (level.lighting[x+camera.x][y+camera.y] != 0) {
			// 			// context.globalAlpha = 1-(level.lighting[x+camera.x][y+camera.y]*0.01);
			// 			// context.rect(x,y,1,1);
			// 		}
			// 	}
			// }

			// context.stroke();
			// for (var i = camera.slice_index; i < camera.slice_index+10; i++) {
			// 	for (var j = camera.chunk_index; j < camera.chunk_index+10; j++) {
			// 		var coordinates = (i+1)+","+j;
			// 		context.fillText(coordinates,(i-camera.slice_index)*99,(j-camera.chunk_index)*99+15);
			// 	}
			// }

			// console.log(player.coordinates);
			var ix = player.coordinates.x-camera.x;
			var iy = player.coordinates.y-camera.y;
			ix -= player.width/2;
			iy -= player.height/2;
			// console.log(ix+" "+iy);
			// context.rect(ix-5,iy-5,10,10);
			// context.rect(ix-player.width/2,iy-player.height/4,0,player.height/2);
			// context.rect(ix+player.width/2,iy-player.height/4,0,player.height/2);
			// context.rect(ix-player.width/4,iy-player.height/2,player.width/2,0);
			// context.rect(ix-player.width/4,iy+player.height/2,player.width/2,0);
			// context.stroke();
			
			if (player.face == 'r') {
				context.drawImage(player.img,ix,iy,player.width,player.height);
			} else if (player.face == 'l') {
				context.translate(player.width,0);
				context.scale(-1,1);
				context.drawImage(player.img,ix,iy,player.width,player.height);
				context.translate(-player.width,0);
				context.scale(1,1);
			} else {
				throw "ya gotta have a face";
			}

			for (var i = 0; i < enemies.length; i++) {
				context.drawImage(enemies[i].img,(enemies[i].coordinates.x-enemies[i].width/2)-camera.x,(enemies[i].coordinates.y-enemies[i].height/2)-camera.y,enemies[i].width,enemies[i].height);
			}

			// draw items
			for (var i = 0; i < level.items.length; i++) {
				var it = level.items[i];
				context.drawImage(it.img,(it.coordinates.x-it.width/2)-camera.x,(it.coordinates.y-it.height/2)-camera.y,it.width,it.height);
			}

			// context.fillRect(player.coordinates.x-5,0,10,900);

			// draw pins
			for (var i = 0; i < pins.length; i++) {
				// console.log("pin @ "+pins[i].x,+","+pins[i].y);
				context.drawImage(PIN_IMG,pins[i].x-(10+camera.x),pins[i].y-(20+camera.y),20,20);
			}

			


			// lighting code
			context.fillStyle = "#000000";
			for (var i = parseInt(camera.x/9); i < level.slices.length*11; i++) {
				for (var j = parseInt(camera.y/9); j < level.slices[0].chunks.length*11; j++) {
					if (level.lighting[i][j] != 100) {
						context.globalAlpha = 1-(0.01*level.lighting[i][j]);
						context.fillRect((i-parseInt(camera.x/9))*9-(9-camera.x%9),(j-parseInt(camera.y/9))*9-(9-camera.y%9),9,9);
					}
				}
			}
			context.globalAlpha = 1;





			context.fillStyle = "#000000";
			context.fillText(GAME_STATS._CLOCK,730,50);
			context.font = "20px lucida console";
			context.fillStyle = "#FFFFFF";
			context.fillText("camera",50,850);
			context.fillText(camera.x+" "+camera.y,150,850);
			context.fillText("player",50,870);
			context.fillText(player.coordinates.x+" "+player.coordinates.y,150,870);
			context.fillText("momentum h/v",300,870);
			context.fillText(player.momentum_horizontal+" "+player.momentum_vertical,470,870);
			context.fillText(player.face,550,870);
			context.fillText("mouse "+current_mouse_pos.x+","+current_mouse_pos.y,700,870);
			// context.fillText(player.health_points+" HP",40+player.health_points,50);
			
		}
		/*function responsible for drawing the menu when the game is paused*/
		function draw_menu() {
			context.fillStyle = "#FFFFFF";
			context.font = "20px lucida console";
			context.fillText("press 'P' to play / pause",300,500);
		}

		/*function responsible for gravity*/
		function gravity(entity) {
			// there are about 30 ticks per second
			// 1ft = 20px
			entity.momentum_vertical-=1;

		}

		/*function calls move_entity(for each entity)*/
		function move_entities() {
			move_entity(player);
			for (var i = 0; i < enemies.length; i++) {
				move_entity(enemies[i]);
			}
		}

		/*function responsible for moving a single entity*/
		function move_entity(entity) {
			// move entity
			if (entity.momentum_vertical > 0) {
				var dist = (20*(Math.pow(((entity.momentum_vertical+1)/30),2)*32))-(20*(Math.pow(((entity.momentum_vertical)/30),2)*32));
				// console.log(entity.momentum_vertical);
				// console.log(dist);
				for (var d = dist; d > 0; d--) {
					if (top_is_clear(entity,levels[GAME_STATS._LEVEL])) {
						entity.move_north(1);
					} else {
						entity.momentum_vertical = 0;
						break;
					}
				}
			} else if (entity.momentum_vertical < 0) {
				var dist = (20*(Math.pow(((Math.abs(entity.momentum_vertical)+1)/30),2)*32))-(20*(Math.pow(((Math.abs(entity.momentum_vertical))/30),2)*32));
				// console.log(entity.momentum_vertical);
				// console.log(dist);
				for (var d = dist; d > 0; d--) {
					if (base_is_clear(entity,levels[GAME_STATS._LEVEL])) {
						entity.move_south(1);
					} else {
						entity.momentum_vertical = 0;
						// console.log((entity.height/2));
						// console.log("break");
						break;
					}
				}
			}
			if (entity.momentum_horizontal > 0) {
				var dist = (20*(Math.pow(((entity.momentum_horizontal+1)/30),2)*32))-(20*(Math.pow(((entity.momentum_horizontal)/30),2)*32));
				// console.log(dist);
				for (var d = dist; d > 0; d--) {
					if (right_is_clear(entity,levels[GAME_STATS._LEVEL])) {
						if (feet_right_is_clear(entity,levels[GAME_STATS._LEVEL],4) && feet_right_is_clear(entity,levels[GAME_STATS._LEVEL],3) && feet_right_is_clear(entity,levels[GAME_STATS._LEVEL],2) && feet_right_is_clear(entity,levels[GAME_STATS._LEVEL],1)) {
							entity.move_east(1);
						} else {
							if (!feet_right_is_clear(entity,levels[GAME_STATS._LEVEL],1) && feet_right_is_clear(entity,levels[GAME_STATS._LEVEL],2)) {
								entity.move_east(1);
								entity.move_north(9);
							} else {
								entity.momentum_horizontal = 0;
								break;
							}
						}
					} else {
						entity.momentum_horizontal = 0;
						break;
					}
				}
			} else if (entity.momentum_horizontal < 0) {
				var dist = (20*(Math.pow(((Math.abs(entity.momentum_horizontal)+1)/30),2)*32))-(20*(Math.pow(((Math.abs(entity.momentum_horizontal))/30),2)*32));
				// to fix things? (still a slight inaccuracy on the equality of the movement speeds 14.93 to 14.86 with a default speed of 10)
				dist *= 1.9;
				// console.log(dist);
				for (var d = dist; d > 0; d--) {
					if (left_is_clear(entity,levels[GAME_STATS._LEVEL])) {
						if (feet_left_is_clear(entity,levels[GAME_STATS._LEVEL],4) && feet_left_is_clear(entity,levels[GAME_STATS._LEVEL],3) && feet_left_is_clear(entity,levels[GAME_STATS._LEVEL],2) && feet_left_is_clear(entity,levels[GAME_STATS._LEVEL],1)) {
							entity.move_west(1);
						} else {
							if (!feet_left_is_clear(entity,levels[GAME_STATS._LEVEL],1) && feet_left_is_clear(entity,levels[GAME_STATS._LEVEL],2)) {
								entity.move_west(1);
								entity.move_north(9);
							} else {
								entity.momentum_horizontal = 0;
								break;
							}
						}
					} else {
						entity.momentum_horizontal = 0;
						break;
					}
				}
			}

		}

		/*function responsible for enemy decisions*/
		function enemy_ai() {
			for (var i = 0; i < enemies.length; i++) {
				gravity(enemies[i]);

				if (enemies[i].momentum_horizontal != 0) {
					enemies[i].momentum_horizontal = parseInt(enemies[i].momentum_horizontal/2);
				}

			}
		}

		/*function responsible for handling player movement*/
		function control_player() {

			// console.log(player.momentum_vertical);
			// console.log(player.momentum_horizontal);

			if (KEY_DATA._w_IS_PRESSED) {
				// do nothing, yet
			}
			if (KEY_DATA._a_IS_PRESSED) {
				player.momentum_horizontal = -player.get_speed();
				// if (false && player.momentum_horizontal > -player.get_speed()) {
				// 	player.momentum_horizontal -= 1;
				// } else {
				// 	// player.momentum_horizontal = -player.get_speed();
				// 	// do nothing
				// }

				if (player.state == "standing") {
					player.img = player_running_animation[0];
					player.state = "run_0";
				} else if (player.state == "run_0") {
					player.img = player_running_animation[1];
					player.state = "run_1";
				} else if (player.state == "run_1") {
					player.img = player_running_animation[2];
					player.state = "run_2";
				} else if (player.state == "run_2") {
					player.img = player_running_animation[0];
					player.state = "run_0";
				}

			} else {
				player.momentum_horizontal = parseInt(player.momentum_horizontal/2);
			}
			if (KEY_DATA._s_IS_PRESSED) {
				// do nothin, yet
			}
			if (KEY_DATA._d_IS_PRESSED) {
				player.momentum_horizontal = player.get_speed();
				// if (player.momentum_horizontal < player.get_speed()) {
				// 	player.momentum_horizontal += 1;
				// }
				if (player.state == "standing") {
					player.img = player_running_animation[0];
					player.state = "run_0";
				} else if (player.state == "run_0") {
					player.img = player_running_animation[1];
					player.state = "run_1";
				} else if (player.state == "run_1") {
					player.img = player_running_animation[2];
					player.state = "run_2";
				} else if (player.state == "run_2") {
					player.img = player_running_animation[0];
					player.state = "run_0";
				}
			} else {
				player.momentum_horizontal = parseInt(player.momentum_horizontal/2);
			}
			if (KEY_DATA._space_IS_PRESSED) {
				if (!base_is_clear(player,levels[GAME_STATS._LEVEL])) {
					player.momentum_vertical = 15;
				}
			}
			// apply gravity
			gravity(player);


			
		}
		

		/*function responsible for adjusting the camera when the player is out of range*/
		function set_camera() {

			var center_x = camera.x+450;
			var center_y = camera.y+450;

			if (player.coordinates.x - center_x > 150) {
				move_camera_right(player.coordinates.x-(center_x+150));
			}
			if (player.coordinates.x - center_x < -150) {
				move_camera_left(Math.abs(player.coordinates.x-(center_x-150)));
			}
			if (player.coordinates.y - center_y > 150) {
				move_camera_down(player.coordinates.y-(center_y+150));
			}
			if (player.coordinates.y - center_y < -150) {
				move_camera_up(Math.abs(player.coordinates.y-(center_y-150)));
			}

			// var center_slice = camera.slice_index+4;
			// var center_chunk = camera.chunk_index+4;

			// if (player.coordinates.slice-center_slice > 1) {
			// 	if (player.coordinates.slice-center_slice > 2) {
			// 		move_camera_right(15);
			// 	}
			// 	move_camera_right(15);
			// }
			// if (player.coordinates.slice-center_slice < -1) {
			// 	if (player.coordinates.slice-center_slice < -2) {
			// 		move_camera_left(15);
			// 	}
			// 	move_camera_left(15);
			// }

		}

		function move_camera_right(dist) {
			camera.x+=dist;
		}
		function move_camera_left(dist) {
			camera.x-=dist;
		}
		function move_camera_down(dist) {
			camera.y+=dist;
		}
		function move_camera_up(dist) {
			camera.y-=dist;
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

		function TEST_CODE() {
			// solely a test function don't get excited
			// console.log(get_data_in_relation_to_player(0,player.height/2,player,levels[GAME_STATS._LEVEL]));
		}

		/*function responsible for controlling the game timer and tempo*/
		function tick(speed) {
			console.log("tick");
			setTimeout(tick,GAME_STATS._SPEED);
			// console.log(GAME_STATS._CLOCK);

			if (!GAME_STATS._PAUSED) {
				TEST_CODE();
				set_camera();
				enemy_ai();
				control_player();
				actions();
				move_entities();
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