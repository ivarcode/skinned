/*
chunk.js
*/

/*Chunk object constructor*/
function Chunk() {
	this.data = [];
	for (var i = 0; i < 11; i++) {
		this.data[i] = [];
		for (var j = 0; j < 11; j++) {
			this.data[i][j] = 0;
		}
	}
}

/*chunk generations*/
function test_chunk() {
	var c = new Chunk();
	var new_data = [
	[1,0,0,0,0,0,0,0,0,0,0],
	[0,1,0,0,0,0,0,0,0,0,0],
	[0,0,1,0,0,0,0,0,0,0,0],
	[0,0,0,1,0,0,0,0,0,0,0],
	[0,0,0,0,1,0,0,0,0,0,0],
	[0,0,0,0,0,1,0,0,0,0,0],
	[0,0,0,0,0,0,1,0,0,0,0],
	[0,0,0,0,0,0,0,1,0,0,0],
	[0,0,0,0,0,0,0,0,1,0,0],
	[0,0,0,0,0,0,0,0,0,1,0],
	[0,0,0,0,0,0,0,0,0,0,1],
	];
	c.data = new_data;
	return c;
}