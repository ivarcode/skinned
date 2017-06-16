/*
level.js
*/

/*Level object constructor*/
function Level(s,c) {
	this.slices = [];
	for (var i = 0; i < s; i++) {
		this.slices[i] = new Slice(c);
	}
	this.lighting = get_dark_array(this.slices.length*11,this.slices[0].chunks.length*11);
	this.glow_color = get_black_array(this.slices.length*11,this.slices[0].chunks.length*11);
	this.items = [];
}


function add_noise(arr,severity) {
	for (var i = 0; i < arr.length; i++) {
		if (Math.random()*10 < 1) {
			arr[i] = arr[i]+(parseInt(Math.random()*severity*2)-severity);
		}
	}
}

function get_dark_array(x,y) {
	var a = []
	for (var i = 0; i < x; i++) {
		var b = [];
		for (var j = 0; j < x; j++) {
			b.push(0);
		}
		a.push(b);
	}
	return a;
}

function get_black_array(x,y) {
	var a = []
	for (var i = 0; i < x; i++) {
		var b = [];
		for (var j = 0; j < x; j++) {
			b.push(null);
		}
		a.push(b);
	}
	return a;
}

function print_lighting(lighting) {
	var c = "";
	for (var i = 0; i < lighting.length; i++) {
		for (var j = 0; j < lighting[0].length; j++) {
			if (lighting[i][j] != 0) {
				c += lighting[i][j];
			}
		}
	}
	console.log("lighting != 0   ::   "+c);
}


function generate_level() {
	var level = new Level(100,50);
	var sea_lvl = 330;
	var wide_pts_of_elevation = [];
	for (var i = 0; i < 50; i++) {
		wide_pts_of_elevation.push(parseInt(Math.random()*20)-10);
	}
	var pts_of_elevation = [];
	for (var j = 0; j < 49; j++) {
		for (var k = 0; k < 22; k++) {
			pts_of_elevation.push(wide_pts_of_elevation[j]-parseInt(k*(wide_pts_of_elevation[j]-wide_pts_of_elevation[j+1])/22));
		}
	}
	// add_noise(pts_of_elevation,1);
	console.log(pts_of_elevation);
	for (var x = 0; x < 1100; x++) {
		for (var y = 0; y < 550; y++) {
			if (y > sea_lvl + pts_of_elevation[x]) {
				edit_data(level,x,y,2);
			} else if (y > sea_lvl + pts_of_elevation[x] - 3) {
				edit_data(level,x,y,1);
			} else if (y > sea_lvl + pts_of_elevation[x] - 4) {
				edit_data(level,x,y,3);
			} else {
				edit_data(level,x,y,0);
			}
		}
	}
	return level;
}


function generate_level_gym() {
	var level = new Level(40,40);
	for (var x = 0; x < 440; x++) {
		for (var y = 0; y < 440; y++) {
			if (x < 110 || x > 330 || y < 110 || y > 330) {
				edit_data(level,x,y,2);
			}
		}
	}
	place_item_at(level,get_torch(),1000,2500);
	place_item_at(level,get_torch(),2000,2500);
	place_item_at(level,get_torch(),3000,2500);
	return level;
}


function get_data_from_coordinates(level,x,y) {
	return level.slices[parseInt(x/99)].chunks[parseInt(y/99)].data[parseInt((y%99)/11)][parseInt((x%99)/11)];
}

function get_data(level,x,y) {
	return level.slices[parseInt(x/11)].chunks[parseInt(y/11)].data[y%11][x%11];
}

function edit_data(level,x,y,id) {
	// console.log(x,y,id);
	level.slices[parseInt(x/11)].chunks[parseInt(y/11)].data[y%11][x%11] = new Data(id);
}

function set_light_level(level,x,y,light_level) {
	// if (light_level > level.lighting[x][y]) {
		level.lighting[x][y]+=light_level;
	// }
}

function set_lighting(level) {
	// sets the light levels of the data in level
	console.log("set_lighting start");
	// SUN
	
	// ITEMS
	for (var i = 0; i < level.items.length; i++) {
		if (level.items[i].light_level == null) {
			// if no light emitted from, do nothing
		} else {
			console.log("meme");
				set_light_radius(level,
					parseInt(level.items[i].coordinates.x/9),
					parseInt(level.items[i].coordinates.y/9),
					level.items[i].light_level,
					level.items[i].light_level,
					level.items[i].glow_style
				);
		}
	}
	console.log("set_lighting end");

}

function set_light_radius(level,x,y,radius,light_level,glow_color) {
	console.log("set_light_radius(",level,x,y,radius,light_level);
	for (var xx = x-radius; xx < x+radius; xx++) {
		for (var yy = y-radius; yy < y+radius; yy++) {
			if (xx < 0 || yy < 0 || xx > level.slices.length*11 || yy > level.slices[0].chunks.length*11) {
				// nope
			} else {
				var meme = (Math.pow(Math.abs(x-xx),2)+Math.pow(Math.abs(y-yy),2));
				if (meme < Math.pow(radius,2)) {
					// console.log(level,xx,yy,light_level);
					set_light_level(level,xx,yy,light_level-parseInt(Math.sqrt(meme)));
					// set_light_level(level,xx,yy,light_level-3*parseInt((parseInt(Math.sqrt(meme))/3)));
					if (glow_color != null) {
						level.glow_color[xx][yy] = glow_color;
					}
				}
			}
		}
	}




	// for (var xx = x; xx < x+(radius*2); xx++) {
	// 	for (var yy = y; yy < y+(radius*2); yy++) {
	// 		if ((xx*xx)+(yy*yy) <= radius*radius) {
	// 			console.log("ayuydfasdf");
	// 			set_light_level(level,xx,yy,light_level);
	// 		}
	// 	}
	// }
}

