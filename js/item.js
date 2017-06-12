/*
item.js
*/

function Item(name,coordinates,img,height,width,light_level) {
	this.name = name;
	this.coordinates = coordinates;
	this.img = img;
	this.height = height;
	this.width = width;
	this.light_level = light_level;
}

function place_item_at(level,item,x,y) {
	item.coordinates.x = x;
	item.coordinates.y = y;
	level.items.push(item);
}





/*ITEM IMGS*/
var IMG = {
	torch:new Image()
};
IMG.torch.src = "./img/items/torch.jpg";

/*getters*/
function get_torch() {
	return new Item("torch",{x:null,y:null},IMG.torch,20,10,80);
}
