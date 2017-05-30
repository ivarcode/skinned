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