document.onmousedown = mouse_down;
document.onmouseup = mouse_up;


var albumid=null;
var adduserid=null;
var deleteuserid=null;

var drag_title = null;
var drag = null;
var dest = null;
var is_dest = false;
var int_step = null;

function mouse_over(e) {
 
 if(e.target.className == 'usertoadd') { e.target.style.backgroundColor = "yellow"; };

 
if(drag_title!=null){
 if ((e.target.className == 'usertoadd'&& drag_title.className == "drag_title")||(e.target.className == 'trash'&& drag_title.className == "drag_user")) { 
    dest = e.target;
   console.log('onmouseover');
    console.log('dest');
    console.log(dest);
    console.log('drag_title');
    console.log(drag_title);
    is_dest = true;  
}
} 
 // else {
   // dest = null;
//}
};


//register_dests();

// end of main

document.onmouseout = function(e) {
  if (e.target.className == 'usertoadd'||e.target.className == 'trash') {
      console.log('onmouseout');
//      is_dest = false;
//      dest = null;
     e.target.style.backgroundColor = null; 
   //console.log('out');
  }
};




// helper function
function position_to_int(pos) {
  // use this to strip px out
  var i = parseInt(pos.replace('px', ''));
  if (i) {
    return i;
  } else {
    return 0;
  }
}


function mouse_down(e) {
  e = e || window.event;

  down_x = e.clientX;
  down_y = e.clientY;

  drag_title = e.target;

  if ((drag_title.className != "drag_title") && (drag_title.className != "drag_user")) {
    drag_title = null;
   console.log('xxxx');  
  return true;
  }
     
 console.log('mousedown');
 console.log('drag_title');  
 console.log(drag_title);  
 
 offset_width = drag_title.offsetWidth;


 if (drag_title.nextSibling.className == "dragremove"|| drag_title.nextSibling.className == "drag") {
    drag = drag_title.nextSibling;
    console.log(drag_title.nextSibling);
  } else if (drag_title.nextSibling.nextSibling.className == "dragremove"||drag_title.nextSibling.className == "drag") {
    drag = drag_title.nextSibling.nextSibling;
    console.log(drag_title.nextSibling.nextSibling);
  } else {
    alert('error');
    return;
  }
//  console.log(drag);

  offset_x = position_to_int(drag_title.style.left) - offset_width;
  offset_y = position_to_int(drag_title.style.top);

  drag.style.opacity = "0.8";
  drag.style.left = offset_x + "px";
  drag.style.top = offset_y + "px";

  drag_title.style.opacity = "0.2";

  old_zIndex = drag.style.zIndex;

  drag.style.pointerEvents = "none";
  drag.style.zIndex = 1;

  setTimeout(function() {if(drag){drag.style.display = "inline";}}, 100);
  
  document.onmouseover = mouse_over;
  document.onmousemove = mouse_move;
  document.body.focus();
  return false;
}


function mouse_move(e) {
  e = e || window.event;
  drag.style.left = (offset_x + e.clientX - down_x) + 'px';
  drag.style.top = (offset_y + e.clientY - down_y) + 'px';

}


function mouse_up(e) {
  e = e || window.event;
  console.log('moveup');
  console.log('drag');
  console.log(drag);
  console.log('dest');
  console.log(dest);

  if (drag != null) {

    if (dest!=null) {  // test if a dest is under mouse

      //update the permission
        if(dest.className == 'usertoadd') {
         albumid = drag.getAttribute("albumid");
         adduserid = dest.getAttribute("usertoadd");
         updatePermission(albumid,adduserid,deleteuserid);
         reset();

       } else if (dest.className == 'trash') {
         albumid = drag.getAttribute("albumid");
         deleteuserid = drag.getAttribute("usertodelete");
         updatePermission(albumid,adduserid,deleteuserid);
         reset();

        }
 
      } else { 

              if (Math.abs(e.clientX - down_x)<6) {console.log('math');reset();
              } else { console.log('retore');
                   step = restore(e.clientX + offset_x - down_x, e.clientY + offset_y - down_y);
                   int_step = setInterval(step, 20);
                 }
           }
 
    }  
}


function restore(x, y) {
  
  document.onmousemove = null;

  if (drag == null) {
    return;
  }

  this.period = 30.0; // how many steps
  this.cur_x = x;
  this.cur_y = y;
  this.unit_x = (x - offset_x) / this.period; 
  this.unit_y = (y - offset_y) / this.period; 
  this.count = 0

  var move_back = function () {
    if (count < this.period) {
      this.cur_x -= this.unit_x; 
      this.cur_y -= this.unit_y; 
      drag.style.left = this.cur_x + 'px';
      drag.style.top = this.cur_y + 'px';
      this.count++;
    }else{
     if (drag == null){
        clearInterval(int_step);
        return;
      } 
    console.log('mark2');
    reset();  
      
    }
  } 
  // return the handle

  return move_back;
  
}


function reset (){
      
      console.log('reset');
      // done, restore drag attributes
    if(drag){
      console.log('drag')
      drag.style.zIndex = old_zIndex;
      drag.style.pointerEvents = null;
      drag.style.left = offset_x + 'px';
      drag.style.top = offset_y + 'px';
      drag.style.display = "none";
      drag.style.opacity = "0.0";
      drag_title.style.opacity = "1";
      drag = null; 
     }
      document.onmouseover = null;
      document.onmousemove = null;
      dest = null;
      drag_title = null;
      is_dest = false;
      albumid=null;
      adduserid=null;
      deleteuserid=null;
    
     if(!int_step){
      // stop the interval loop
      clearInterval(int_step);
 }

}
