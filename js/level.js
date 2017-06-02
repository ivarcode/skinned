/*
level.js
*/

/*Level object constructor*/
function Level() {
	this.slices = [];
	for (var i = 0; i < 100; i++) {
		this.slices[i] = new Slice();
	}
}


function add_noise(arr,severity) {
	for (var i = 0; i < arr.length; i++) {
		if (Math.random()*10 < 1) {
			arr[i] = arr[i]+(parseInt(Math.random()*severity*2)-severity);
		}
	}
}


function generate_level() {
	var level = new Level();
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
	add_noise(pts_of_elevation,1);
	console.log(pts_of_elevation);
	for (var x = 0; x < 1100; x++) {
		for (var y = 0; y < 550; y++) {
			if (y > sea_lvl + pts_of_elevation[x]) {
				edit_data(level,x,y,2);
			} else {
				edit_data(level,x,y,0);
			}
		}
	}
	return level;
}



function edit_data(level,x,y,id) {
	// console.log(x,y,id);
	level.slices[parseInt(x/11)].chunks[parseInt(y/11)].data[y%11][x%11] = id;
}