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

Chunk.prototype.remove_entity = function(entity) {
	// finds entity in Chunk.entities and removes it
	for (var i = 0; i < this.entities.length; i++) {
		// if entity is the same as the entity at entities[i]
		if (Object.is(this.entities[i],entity)) {
			// remove from the array
			this.entities.splice(i,1);
			break;
		}
	}
};

/*helper functions to generate chunk types*/
function base_chunk(coord) {
	// base chunk designed to be the players home / safehaven until another base is acquired
	var chunk = new Chunk(coord);
	var new_data = [
	[0,0,0,0,0,0,0,0,0,0,0],
	[0,0,0,0,0,0,0,0,0,0,0],
	[0,0,1,1,1,0,1,1,1,0,0],
	[0,0,1,0,0,0,0,0,1,0,0],
	[0,0,1,0,0,0,0,0,1,0,0],
	[0,0,1,0,0,0,0,0,1,0,0],
	[0,0,1,0,0,0,0,0,1,0,0],
	[0,0,1,0,0,0,0,0,1,0,0],
	[0,0,1,1,1,0,1,1,1,0,0],
	[0,0,0,0,0,0,0,0,0,0,0],
	[0,0,0,0,0,0,0,0,0,0,0]
	];
	chunk.data = new_data;
	return chunk;
}