/*
slice.js
*/

/*Slice object constructor*/
function Slice() {
	this.chunks = [];
	for (var i = 0; i < 11; i++) {
		this.chunks[i] = test_chunk();
	}
}
