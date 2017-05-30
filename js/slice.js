/*
slice.js
*/

/*Slice object constructor*/
function Slice() {
	this.chunks = [];
	for (var i = 0; i < 50; i++) {
		if (i < 30) {
			this.chunks[i] = empty_chunk();
		} else {
			this.chunks[i] = solid_chunk();
		}
	}
}
