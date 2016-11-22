/*
game.js
*/

/*Game object constructor*/
function Game() {
	this.chunks = [];
	this.chunks[0] = new Chunk({x:0,y:0});
}

/*Chunk object constructor*/
function Chunk(coord) {
	this.coordinates = coord;
	this.data = [];
	for (var i = 0; i < 11; i++) {
		this.data[i] = [];
		for (var j = 0; j < 11; j++) {
			this.data[i][j] = 0;
		}
	}
	this.neighbors = {_N:null,_S:null,_E:null,_W:null};
}

/*helper functions*/
function add_chunk(game,chunk) {
	// function responsible for adding a chunk to the game
	var north_chunk = game.get_chunk({x:chunk.coordinates.x,y:chunk.coordinates.y-1});
	var south_chunk = game.get_chunk({x:chunk.coordinates.x,y:chunk.coordinates.y+1});
	var west_chunk = game.get_chunk({x:chunk.coordinates.x-1,y:chunk.coordinates.y});
	var east_chunk = game.get_chunk({x:chunk.coordinates.x+1,y:chunk.coordinates.y});
	if (north_chunk != null) {
		north_chunk.neighbors._S = chunk;
		chunk.neighbors._N = north_chunk;
	}
	if (south_chunk != null) {
		south_chunk.neighbors._N = chunk;
		chunk.neighbors._S = south_chunk;
	}
	if (west_chunk != null) {
		west_chunk.neighbors._E = chunk;
		chunk.neighbors._W = west_chunk;
	}
	if (east_chunk != null) {
		east_chunk.neighbors._W = chunk;
		chunk.neighbors._E = east_chunk;
	}
	game.chunks[game.chunks.length]	= chunk;
}

/*Game prototype functions*/
Game.prototype.get_chunk = function(coord) {
	// returns chunk at coord in game, if no chunk exists, returns null
	for (var i = 0; i < this.chunks.length; i++) {
		if (this.chunks[i].coordinates.x == coord.x && this.chunks[i].coordinates.y == coord.y) {
			return this.chunks[i];
		}
	}
	return null;
};