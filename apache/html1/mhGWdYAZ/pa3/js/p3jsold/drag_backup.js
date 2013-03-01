
// main 
document.onmousedown = mouse_down;
document.onmouseup = mouse_up;

var drag_title = null;
var drag = null;
var dest = null;
var just_move = false;

document.onmouseover = function(e) {
  if (e.target.className == 'trash'&& drag_title.className == "drag_title") { 
    dest = e.target;
    console.log('onmouseover');
    console.log(dest);
    console.log(drag_title);
    just_move = true;  
} 
 // else {
   // dest = null;
//}
};


//register_dests();

// end of main


document.onmouseout = function(e) {
  if (e.target.className == 'trash') {
      console.log('onmouseout');
    if(!just_move) dest = null;
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

//
// drag is the element that is being dragged.
//

function register_dests() {
  var dests = document.getElementsByClassName('trash');

  for (var i = 0; i < dests.length; i++) {
    var el = dests[i];
    //el.onmouseover = function(e) {dest = e.target; console.log('over');};
    //el.onmouseout = function(e) {dest = null; console.log('out');};
    el.style.zIndex = 500;
  }
}

function mouse_down(e) {
  e = e || window.event;

  down_x = e.clientX;
  down_y = e.clientY;

  drag_title = e.target;

  if (drag_title.className != "drag_title") {
    // we are testing against drag_title because drag is hidden
    drag_title = null;
    return true;
  }
 
    

//  console.log(drag_title);  

  offset_width = drag_title.offsetWidth;

  drag = drag_title.nextSibling;

//  console.log(drag);

  offset_x = position_to_int(drag_title.style.left) - offset_width;
  offset_y = position_to_int(drag_title.style.top);

  drag.style.opacity = "0.8";
  drag.style.left = offset_x;
  drag.style.top = offset_y;

  drag_title.style.opacity = "0.2";

  old_zIndex = drag.style.zIndex;

  drag.style.pointerEvents = "none";
  // since drag element is always below the cursor
  // we have to disable pointerEvents for drag
  // otherwise the event will not happen on the destination
  drag.style.zIndex = 1;

  
  setTimeout(function() {drag.style.display = "inline";}, 100);

  document.onmousemove = mouse_move;
  document.body.focus();
  return false;
}

function mouse_move(e) {
  e = e || window.event;
//change made
//  if ((dest!=null)&&(drag_title!=null)){
  drag.style.left = (offset_x + e.clientX - down_x) + 'px';
  drag.style.top = (offset_y + e.clientY - down_y) + 'px';
//  }
}


function mouse_up(e) {
  e = e || window.event;
  if (drag != null) {
     console.log('mouseup');
     console.log(drag);
     console.log(dest);
    if (just_move) {  // test if a dest is under mouse
      // user dropped the element at
      // right location
      console.log(dest);
         
      reset();
      // trigger ajax to grand user access to album
      //
      // username = dest.value()
      // albumid = drag.value()
      // $.post('add.php', {albumid = albu, }, fucntion() {
      // 
      // })
    } else if(!just_move){
     
    //disable the mousemove fuction
//    document.onmousemove = null;
 
    //created a closure
    step = restore(e.clientX + offset_x - down_x, e.clientY + offset_y - down_y);

    // this will move the block back to origin pos
    // setInterval will stop by step() if it is done
    int_step = setInterval(step, 20);
   }
 }
}

// restore is more like a class
function restore(x, y) {

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
    } else {
      
       reset();
     }
  }

  // return the handle
  return move_back;
}



function reset (){
      
      console.log('reset');
      // done, restore drag attributes
      drag.style.zIndex = old_zIndex;
      drag.style.pointerEvents = null;
      drag.style.left = offset_x + 'px';
      drag.style.top = offset_y + 'px';
      document.onmousemove = null;
      drag.style.display = "none";
      drag.style.opacity = "0.0";
      drag_title.style.opacity = "1";
      drag = null; 
      dest = null;
      drag_title = null;
      just_move = false;
      // stop the interval loop
      clearInterval(int_step);
 

}
