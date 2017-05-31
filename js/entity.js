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
}

/*Entity prototype functions*/
Entity.prototype.move_east = function(dist) {
	// moves entity east dist pixels
	if (this.coordinates.x+dist > 99) {
		var s = parseInt((this.coordinates.x+dist)/99);
		this.coordinates = {slice:this.coordinates.slice+s,chunk:this.coordinates.chunk,x:this.coordinates.x+dist-(99*s),y:this.coordinates.y};
	} else {
		this.coordinates = {slice:this.coordinates.slice,chunk:this.coordinates.chunk,x:this.coordinates.x+dist,y:this.coordinates.y};
	}
};
Entity.prototype.move_west = function(dist) {
	// moves entity east dist pixels
	if (this.coordinates.x-dist < 0) {
		var s = parseInt(-(this.coordinates.x-dist)/99)+1;
		this.coordinates = {slice:this.coordinates.slice-s,chunk:this.coordinates.chunk,x:this.coordinates.x-dist+(99*s),y:this.coordinates.y};
	} else {
		this.coordinates = {slice:this.coordinates.slice,chunk:this.coordinates.chunk,x:this.coordinates.x-dist,y:this.coordinates.y};
	}
};
Entity.prototype.move_north = function(dist) {
	// moves entity east dist pixels
	if (this.coordinates.y-dist < 0) {
		var s = parseInt(-(this.coordinates.y-dist)/99)+1;
		this.coordinates = {slice:this.coordinates.slice,chunk:this.coordinates.chunk-s,x:this.coordinates.x,y:this.coordinates.y-dist+(99*s)};
	} else {
		this.coordinates = {slice:this.coordinates.slice,chunk:this.coordinates.chunk,x:this.coordinates.x,y:this.coordinates.y-dist};
	}
};
Entity.prototype.move_south = function(dist) {
	// moves entity east dist pixels
	if (this.coordinates.y+dist > 99) {
		var s = parseInt((this.coordinates.y+dist)/99);
		this.coordinates = {slice:this.coordinates.slice,chunk:this.coordinates.chunk+s,x:this.coordinates.x,y:this.coordinates.y+dist-(99*s)};
	} else {
		this.coordinates = {slice:this.coordinates.slice,chunk:this.coordinates.chunk,x:this.coordinates.x,y:this.coordinates.y+dist};
	}
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



/*map getters*/
function get_data_in_relation_to_player(x,y,player,level) {
	// returns the data in the block x / y away from player
	// console.log(x,y);
	var sl = null;
	var ch = null;
	var xx = null;
	var yy = null;
	if (x+player.coordinates.x > 98) {
		var t = (x+player.coordinates.x)/99;
		sl = player.coordinates.slice+parseInt(t);
		xx = (x+player.coordinates.x)%99;
	} else if (x+player.coordinates.x < 0) {
		var t = (-(x+player.coordinates.x)/99)+1;
		sl = player.coordinates.slice-parseInt(t);
		xx = (99-(x+player.coordinates.x))%99;
	} else {
		xx = parseInt((player.coordinates.x+x));
		sl = player.coordinates.slice;
	}
	if (y+player.coordinates.y > 98) {
		var t = (y+player.coordinates.y)/99;
		ch = player.coordinates.chunk+parseInt(t);
		yy = (y+player.coordinates.y)%99;
	} else if (y+player.coordinates.y < 0) {
		var t = (-(y+player.coordinates.y)/99)+1;
		ch = player.coordinates.chunk-parseInt(t);
		yy = (99-(y+player.coordinates.y))%99;
	} else {
		yy = parseInt((player.coordinates.y+y));
		ch = player.coordinates.chunk;
	}
	// console.log(sl,ch,xx,yy);
	// console.log(level.slices[sl].chunks[ch].data[parseInt(xx/9)][parseInt(yy/9)]);
	return level.slices[sl].chunks[ch].data[parseInt(xx/9)][parseInt(yy/9)];
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