/*
level.js
*/

/*Level object constructor*/
function Level(s,c) {
	this.slices = [];
	for (var i = 0; i < s; i++) {
		this.slices[i] = new Slice(c);
	}
	// set_lighting(this);
	this.items = [];
}


function add_noise(arr,severity) {
	for (var i = 0; i < arr.length; i++) {
		if (Math.random()*10 < 1) {
			arr[i] = arr[i]+(parseInt(Math.random()*severity*2)-severity);
		}
	}
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
	level.slices[parseInt(x/11)].chunks[parseInt(y/11)].data[y%11][x%11].light_level = light_level;
}

function set_lighting(level) {
	// sets the light levels of the data in level
	console.log("set_lighting start");
	var bool = false;
	for (var x = 0; x < level.slices.length*11; x++) {
		for (var y = 0; y < level.slices[0].chunks.length*11; y++) {
			// console.log(get_data(level,x,y).id);
			if (get_data(level,x,y).id == 0) {
				if (y != 0) {
					if (Number.isFinite(get_data(level,x,y-1).light_level)) {
						set_light_level(level,x,y,get_data(level,x,y-1).light_level-1);
					} else {
						set_light_level(level,x,y,Infinity);
					}
				} else {
					set_light_level(level,x,y,Infinity);
				}
			} else {
				bool = true;
				if (y != 0) {
					if (Number.isFinite(get_data(level,x,y-1).light_level)) {
						set_light_level(level,x,y,get_data(level,x,y-1).light_level/2);
					} else {
						set_light_level(level,x,y,10);
					}
				} else {
					set_light_level(level,x,y,Infinity);
				}
			}
		}
	}
	for (var i = 0; i < level.items.length; i++) {
		if (level.items[i].light_level == null) {
			// if no light emitted from, do nothing
		} else {
			console.log("meme");
			set_light_radius(level,level.items[i].coordinates.x,level.items[i].coordinates.y,100,level.items[i].light_level);
		}
	}
	console.log(bool);
	console.log("set_lighting end");

}

function set_light_radius(level,x,y,radius,light_level) {
	for (var xx = x; xx < x+(radius*2); xx++) {
		for (var yy = y; yy < y+(radius*2); yy++) {
			if ((xx*xx)+(yy*yy) <= radius*radius) {
				set_light_level(level,xx,yy,light_level);
			}
		}
	}
}


function set_lighting_to(level,override_value) {
	for (x = 0; x < level.slices.length*11; x++) {
		for (y = 0; y < level.slices[0].chunks.length*11; y++) {
			set_light_level(level,x,y,override_value);
		}
	}
}