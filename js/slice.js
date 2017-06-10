/*
slice.js
*/

/*Slice object constructor*/
function Slice(c) {
	this.chunks = [];
	for (var i = 0; i < c; i++) {
		this.chunks[i] = empty_chunk();
	}
}
