/*
game.js
*/

/*Game object constructor*/
function Game() {
	this.map = [11];
	for (var i = 0; i < 11; i++) {
		this.map[i] = [];
		for (var j = 0; j < 11; j++) {
			this.map[i][j] = 0;
		}
	}
	this.map[3][4] = 1;
}