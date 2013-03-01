EECS485-Project
================
Program Assignment 3

Updated: Feb 25 2013

Notice: For simplicity, the image will now only display with url. The real image approach is now disabled (it is initailly an extra credit feature in PA1). Viewphoto.php is also not accessible in this assignment.

Initialization
==============

This project is easy to initialize. Enter i`sql` folder, and do `./initialization.sh`. It will drop all the table, and recreate all of them and insert all the data in the database. All images will be included in both urls and real image (in mediumblob).

PA3 Assignment
==============

Part 3: Access Control by Drag and Drop (DONE)
----------------------------------------------

This part has been implemented in editalbum.php, which can be accessed through MyAlbum link in logged in user page. The user can:
  * Grant access to other users by easily moving the Album Title to the desired user in "Other User" block.
  * Delete access by moving the targetted user from the Album list to Trash.

Part 4: Scrollable Photo Viewer (DONE)
--------------------------------------

This part has been implemented in viewalbum.php. The user can click on photo thumbnails and browse into the Scrollable Photo Viewer(SPV). In SPV, the user can move the photo to either left or right to view adjunct photos.

Extra Credit
============

scrollable photo captions (DONE)
--------------------------------

Now the photo caption will also show under the photo.

Instant Filtering (DONE)
------------------------

A search textfield and button has been provided in viewalbum.php. User can put the keywords in textfield and press button to display only photos with caption that contains the keyword. More than that, when the user click on one photo, only photos that meets the critiria can be viewed in SPV. The user can go back to the original album list by searching empty string or click on back button of the browser.

Specification
=============

* js/draghandler.js

The drag hander for SPV. Setup Drag and drop function for SPV, and handle the moving displays.

* url.php

return the url of the photo in the keyword sequence with position 'index'.

* caption.php

return the caption of the photo in the keyword sequence with position 'index'.

* search.php

Query the database, return a list of sequence of photos with caption contains the keyword. Return to the request in a photo table format to allow page switch content with ease.



Presented by: Group 6 Team Member
=================================
Qi Liao
Haixin Li
Yan Wang
