/*
chunk.js
*/

/*Chunk object constructor*/
function Chunk(coord) {
	this.coordinates = coord;
	this.entities = [];
	this.data = [];
	for (var i = 0; i < 11; i++) {
		this.data[i] = [];
		for (var j = 0; j < 11; j++) {
			this.data[i][j] = 0;
		}
	}
	this.neighbors = {_N:null,_S:null,_E:null,_W:null};
}

Chunk.prototype.add_entity = function(entity) {
	// adds an entity to the list of entities in Chunk obj
	this.entities[this.entities.length] = entity;
};