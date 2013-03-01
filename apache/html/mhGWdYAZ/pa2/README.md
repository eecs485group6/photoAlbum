485-Project
===========

Initialization
--------------

This project is easy to initialize. Enter sql folder, and do `./initialization.sh`. It will drop all the table, and recreate all of them and insert all the data in the database. All images will be included in both urls and real image (in mediumblob).


Page Structure
--------------

Index.php
---------

Index page. provide links to different user's album list.

viewalbumlist.php
-----------------

Albumlist page for selected user. Can also direct to here by clicking AlbumList link on the navigation bar. In this case, all the albums will be displayed, but no link to editalbumlist.php will be provided. 

editalbumlist.php
-----------------

Edit the album list. Possible funtions are:

0. Link to edit each album

1. Delete any album

2. Create new album

viewalbum.php
-------------

Display all picture thumbnails in the album. 

A link to viewalbumwithbytes.php is provided to navigate to the counterpart of viewalbum.php, which display the images stored in the database, instead of using urls. 

An edit button is provided to edit the album.

Click on the thumbnails will direct the user to the actual picture page.

viewalbumwithbytes.php
----------------------

display the images stored in the database assocaited with this album. Also has a button to edit the album.

editalbum.php
-------------

Here are possible functions:

0. Change album access

1. Upload a new photo to the album.
  * if existed, will use the photo already in the table instead.

2. Delete a photo from album.
  * if this photo no longer exist in any album, remove the photo from database.

viewpicture.php
---------------

view the full size picture. Currently funtions provided are:

0. A link to viewpicturewithbytes.php, which display the image by loading from database.

1. Email the picture to a given email address.

2. Comment on the picture.

3. Navigation to the previous or next pictures of the album.

4. Back to this album page.

viewpicturewithbytes.php
------------------------

view the full size picture loaded from database instead of urls.

showimage.php (helper function)
-------------------------------

display the image by reading it from the database. URL is currently the key to get the image data.

emailpicture.php (helper function)
----------------------------------

a php page provided functionality to send image to a given email address.

ajax.js (helper function)
-------------------------

provide basic button listen and ajax delivery to make the communication between javascript and emailpicture.php in viewpicture.php.

lib.php (helper function)
-------------------------

a reusable function to connect to mysql server. TODO: change the hardcode password to be encrypted.



