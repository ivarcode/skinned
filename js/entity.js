/*
entity.js
*/

/*Entity object constructor*/
function Entity(coord,speed) {
	this.coordinates = coord;
	this.speed = speed;
}

/*Entity prototype functions*/
Entity.prototype.move_east = function(dist) {
	// moves entity east dist pixels
	this.coordinates = {x:this.coordinates.x+dist,y:this.coordinates.y};
};
Entity.prototype.move_west = function(dist) {
	// moves entity east dist pixels
	this.coordinates = {x:this.coordinates.x-dist,y:this.coordinates.y};
};
Entity.prototype.move_north = function(dist) {
	// moves entity east dist pixels
	this.coordinates = {x:this.coordinates.x,y:this.coordinates.y-dist};
};
Entity.prototype.move_south = function(dist) {
	// moves entity east dist pixels
	this.coordinates = {x:this.coordinates.x,y:this.coordinates.y+dist};
};

Entity.prototype.get_speed = function() {
	// returns speed entity
	return this.speed;
};