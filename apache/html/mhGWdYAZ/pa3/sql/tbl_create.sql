# 3a: Create tables
# the data should be created in the order according to the foreign key contraints
# Please drop the tables before recreating them




CREATE TABLE User (
    username VARCHAR(20) COLLATE latin1_general_cs, 
    firstname VARCHAR(20) COLLATE latin1_general_cs, 
    lastname VARCHAR(20) COLLATE latin1_general_cs, 
    password VARCHAR(64), 
    email VARCHAR(40) COLLATE latin1_general_cs,
    PRIMARY KEY (username))ENGINE=INNODB;


CREATE TABLE Album (
    albumid INT, 
    title VARCHAR(50) COLLATE latin1_general_cs, 
    created DATE, 
    lastupdated DATE, 
    access VARCHAR(10), 
    username VARCHAR(20) COLLATE latin1_general_cs,
    PRIMARY KEY (albumid),
    FOREIGN KEY (username) REFERENCES User(username)
    ON DELETE CASCADE ON UPDATE CASCADE)ENGINE=INNODB;

CREATE TABLE Photo (
    url VARCHAR(255),
    format CHAR(3),
    date DATE,
    PRIMARY KEY(url))ENGINE=INNODB;


CREATE TABLE Contain (
    albumid INT, 
    url VARCHAR(255), 
    caption VARCHAR(255), 
    sequencenum INT,
    PRIMARY KEY(albumid,url),
    FOREIGN KEY (albumid) REFERENCES Album(albumid)
    ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (url) REFERENCES Photo(url)
    ON DELETE CASCADE ON UPDATE CASCADE)ENGINE=INNODB;


CREATE TABLE AlbumAccess (
    albumid INT, 
    username VARCHAR(20) COLLATE latin1_general_cs,
    PRIMARY KEY(albumid,username),
    FOREIGN KEY (username) REFERENCES User(username)
    ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (albumid) REFERENCES Album(albumid)
    ON DELETE CASCADE ON UPDATE CASCADE)ENGINE=INNODB;


CREATE TABLE Comments (
    commentid INT,
    commentuser VARCHAR(20) COLLATE latin1_general_cs,
    url VARCHAR(255),
    comments VARCHAR(255) COLLATE latin1_general_cs,
    PRIMARY KEY(commentid,url),
    FOREIGN KEY (url) REFERENCES Photo(url)
    ON DELETE CASCADE ON UPDATE CASCADE)ENGINE=INNODB;

CREATE TABLE Data (
    url VARCHAR(255) NOT NULL,
    mime_type VARCHAR(255) NOT NULL,
    file_size INT NOT NULL,
    image_data MEDIUMBLOB NOT NULL,
    PRIMARY KEY(url),
    INDEX(url),
    FOREIGN KEY (url) REFERENCES Photo(url)
    ON DELETE CASCADE ON UPDATE CASCADE)ENGINE=INNODB;

CREATE TABLE Admin (
    username VARCHAR(20) COLLATE latin1_general_cs,
    PRIMARY KEY (username),
    FOREIGN KEY (username) REFERENCES User(username)
    ON DELETE CASCADE ON UPDATE CASCADE)ENGINE=INNODB;



#show the created tables

show tables;

describe User;
describe Album;
describe Contain;
describe Photo;
describe AlbumAccess;
describe Comments;
describe Data;
describe Admin;
