/*
entity.js
*/

/*Entity object constructor*/
function Entity(coord,speed,hp,height,width,img,type) {
	this.coordinates = coord;
	this.speed = speed;
	this.health_points = hp;
	this.height = height;
	this.width = width;
	this.img = img;
	this.type = type;
	this.momentum_vertical = 0;
	this.momentum_horizontal = 0;
	this.current_action = null;
	this.current_item = null;
	this.face = 'r';
}

/*Entity prototype functions*/
Entity.prototype.move_east = function(dist) {
	// moves entity east dist pixels
	this.coordinates.x+=dist;
};
Entity.prototype.move_west = function(dist) {
	// moves entity east dist pixels
	this.coordinates.x-=dist;
};
Entity.prototype.move_north = function(dist) {
	// moves entity east dist pixels
	this.coordinates.y-=dist;
};
Entity.prototype.move_south = function(dist) {
	// moves entity east dist pixels
	this.coordinates.y+=dist;
};

/*getters*/
Entity.prototype.get_speed = function() {
	// returns speed entity
	return this.speed;
};
Entity.prototype.get_X = function() {
	// returns x value of this.coordinates
	return this.coordinates.x;
};
Entity.prototype.get_Y = function() {
	// returns y value of this.coordinates
	return this.coordinates.y;
};


/*action setters*/
Entity.prototype.strike = function() {
	// sets this.current_action for one armed strike
	this.current_action = "strike";
};


/*return true if the entity's hitbox contains x,y   returns false otherwise*/
function contacts_hitbox(entity,x,y) {
	if (x < entity.coordinates.x-(entity.width/2)) {
		return false;
	}
	if (x > entity.coordinates.x+(entity.width/2)) {
		return false;
	}
	if (y < entity.coordinates.y-(entity.height/2)) {
		return false;
	}
	if (y > entity.coordinates.y+(entity.height/2)) {
		return false;
	}
	return true;
}


/*map getters*/
function get_data_in_relation_to_player(x,y,player,level) {
	// returns the data in the block x / y away from player
	// console.log(x,y);
	var sl = parseInt((player.coordinates.x+x)/99);
	var ch = parseInt((player.coordinates.y+y)/99);
	var xx = parseInt(((player.coordinates.x+x)%99)/9);
	var yy = parseInt(((player.coordinates.y+y)%99)/9);
	
	// console.log(sl,ch,xx,yy);
	// console.log(level.slices[sl].chunks[ch].data[parseInt(xx/9)][parseInt(yy/9)]);
	return level.slices[sl].chunks[ch].data[yy][xx].id;
}



/*checks if the base of the player is clear*/
function base_is_clear(player,level) {
	for (var i = 0; i < player.width/2; i++) {
		if (get_data_in_relation_to_player(i-(player.width/4),parseInt(player.height/2),player,level) != 0) {
			return false;
		}
	}
	return true;
}
/*checks if the top of the player is clear*/
function top_is_clear(player,level) {
	for (var i = 0; i < player.width/2; i++) {
		if (get_data_in_relation_to_player(i-(player.width/4),-parseInt(player.height/2),player,level) != 0) {
			return false;
		}
	}
	return true;
}
/*checks if the right of the player is clear*/
function right_is_clear(player,level) {
	for (var i = 0; i < player.height/2; i++) {
		if (get_data_in_relation_to_player(parseInt(player.width/2),i-(player.height/4),player,level) != 0) {
			return false;
		}
	}
	return true;
}
/*checks if the left of the player is clear*/
function left_is_clear(player,level) {
	for (var i = 0; i < player.height/2; i++) {
		if (get_data_in_relation_to_player(-parseInt(player.width/2),i-(player.height/4),player,level) != 0) {
			return false;
		}
	}
	return true;
}
/*checks if the space to the right of the feet of the player is clear to how many blocks 'x'*/
function feet_right_is_clear(player,level,x) {
	if (get_data_in_relation_to_player(parseInt(player.width/4),(player.height/2)-((x-1)*9)-1,player,level) != 0) {
		return false;
	}
	return true;
}
/*checks if the space to the left of the feet of the player is clear to how many blocks 'x'*/
function feet_left_is_clear(player,level,x) {
	if (get_data_in_relation_to_player(-parseInt(player.width/4),(player.height/2)-((x-1)*9)-1,player,level) != 0) {
		return false;
	}
	return true;
}