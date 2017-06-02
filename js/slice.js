/*
slice.js
*/

/*Slice object constructor*/
function Slice() {
	this.chunks = [];
	for (var i = 0; i < 50; i++) {
		this.chunks[i] = empty_chunk();
	}
}
