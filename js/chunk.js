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
function empty_chunk() {
	var c = new Chunk();
	var new_data = [
	[new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0)],
	[new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0)],
	[new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0)],
	[new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0)],
	[new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0)],
	[new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0)],
	[new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0)],
	[new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0)],
	[new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0)],
	[new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0)],
	[new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0),new Data(0)],
	];
	c.data = new_data;
	return c;
}
function solid_chunk() {
	var c = new Chunk();
	var new_data = [
	[new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1)],
	[new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1)],
	[new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1)],
	[new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1)],
	[new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1)],
	[new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1)],
	[new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1)],
	[new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1)],
	[new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1)],
	[new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1)],
	[new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),new Data(1),1],
	];
	c.data = new_data;
	return c;
}

