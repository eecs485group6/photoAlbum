var photoindex;
var albumid;
var numPhotos;
var startClientX = 0;
var oDragItem = null;
var iClickOffsetX = 0;
var objectOffsetX = 500;
var displayWindowX = 500;
var scrollMargin = 250;
var scrollX;
var y = 0;

function OnLoad(){
	SetupDragDrop();
}

function SetupDragDrop(tot, index, aid){
	numPhotos = tot;
	photoindex = index;
	albumid = aid;
	scrollX = displayWindowX * numPhotos;
	var oList = document.getElementsByTagName("div");
	for(var i=0; i<oList.length; i++){
		var o = oList[i];
		if (o.className == "Dragable"){
			MakeDragable(o);
		}
	}
}

function MakeDragable(oBox){
	oBox.onmousemove= function(e){DragMove(oBox,e)};
	oBox.onmouseup=function(e){DragStop(oBox,e)};
	oBox.onmousedown=function(e){DragStart(oBox,e);return false};
}

function DragStart(o,e){
	if(!e) var e = window.event;
	oDragItem = o;
	var oPos = GetObjPos(o);
	iClickOffsetX = e.clientX - oPos.x + objectOffsetX;
	startClientX = e.clientX;
	//photoindex = 0 - Math.round((oPos.x + scrollMargin) / displayWindowX);
	//iClickOffsetY = e.clientY - oPos.y;
		
	if (o.setCapture){
		o.setCapture();
	}else{
		window.addEventListener ("mousemove", DragMove2, true);
		window.addEventListener ("mouseup",   DragStop2, true);
	}
}

function DragMove2(e){
	DragMove(oDragItem,e);
}

function DragStop2(e){
	DragStop(oDragItem,e);
}

function DragMove(o,e){
	if (oDragItem==null) return;

	if(!e) var e = window.event;
	//var x = e.clientX + document.body.scrollLeft - document.body.clientLeft - iClickOffsetX;
	var x = e.clientX - iClickOffsetX;
	if (e.clientX - startClientX > displayWindowX ) x = startClientX + displayWindowX - iClickOffsetX;
	if (e.clientX - startClientX < 0 - displayWindowX) x = startClientX - displayWindowX - iClickOffsetX;
/* 	if (x < 0 && x > (0 - (photoindex - 1) * displayWindowX - scrollMargin)){
		x = 0 - (photoindex - 1) * displayWindowX - scrollMargin;
	} else if (x > 0 - scrollX && x < (0 - (photoindex + 1) * displayWindowX - scrollMargin)){
		x = 0 - (photoindex + 1) * displayWindowX - scrollMargin;
	} */
	if (x >= 0) {
		x = -1;
	} else if (x <= 0 - scrollX) {
		x = 0 - scrollX + 1;
	} 
	//var y = e.clientY + document.body.scrollTop  - document.body.clientTop - iClickOffsetY
	HandleDragMove(x,y);
	return false;
}

function HandleDragMove(x,y){
	// with(oDragItem.style){
		// position="absolute";
		// left=x;
		// top=y;
	// }
	if (oDragItem==null) return;
	oDragItem.style.position = "absolute";
	oDragItem.style.left = x + 'px';
	oDragItem.style.position = "relative";
}

function DragStop(o,e){
	if (o.releaseCapture){
		o.releaseCapture();
	}else if (oDragItem){
		window.removeEventListener ("mousemove", DragMove2, true);
		window.removeEventListener ("mouseup",   DragStop2, true);
	}
	var oPos = GetObjPos(o);
	for (var i = 0; i < numPhotos; i++) {
		if (oPos.x >= 0-(i+0.5)*displayWindowX+scrollMargin && oPos.x < 0-(i-0.5)*displayWindowX+scrollMargin) x=0-(i+1)*displayWindowX+scrollMargin; 
	}
	HandleDragMove(x,y);
	if (x - (0 - photoindex*displayWindowX - scrollMargin) > 400) {
		if (photoindex > 0) {
			photoindex--;
			if (photoindex - 1 >= 0) {
			loadimage(albumid, photoindex-1);
			}
			if (photoindex + 2 < numPhotos) {
			deleteimage(photoindex+2);
			}
		}
	}
	if (x - (0 - photoindex*displayWindowX - scrollMargin) < -400) {
		if (photoindex + 1 < numPhotos) {
			photoindex++;
			if (photoindex + 1 < numPhotos) {
			loadimage(albumid, photoindex+1);
			}
			if (photoindex - 2 >= 0) {
			deleteimage(photoindex-2);
			}
		}
	}
	HandleDragStop();
}

function HandleDragStop(){
	if (oDragItem==null) return;
	oDragItem.style.zIndex = 1;
	oDragItem = null;
}

function $(s){
	return document.getElementById(s);
}

function GetObjPos(obj){
	var x = 0;
	var y = 0;
	var o = obj;
	
	var w = obj.offsetWidth;
	var h = obj.offsetHeight;
	if (obj.offsetParent) {
		x = obj.offsetLeft
		y = obj.offsetTop
		while (obj = obj.offsetParent){
			x += obj.offsetLeft;
			y += obj.offsetTop;
		}
	}
	return {x:x, y:y, w:w, h:h, o:o};
}


