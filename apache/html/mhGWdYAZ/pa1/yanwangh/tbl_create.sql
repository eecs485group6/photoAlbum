# 3a: Create tables

# firstly drop the existing tables
# drop table User, Album, AlbumAccess, Contain, Photo;

CREATE TABLE User (
    username VARCHAR(20), 
    firstname VARCHAR(20), 
    lastname VARCHAR(20), 
    password VARCHAR(20), 
    email VARCHAR(40),
    PRIMARY KEY (username));


CREATE TABLE Album (
    albumid INT, 
    title VARCHAR(50), 
    created DATE, 
    lastupdated DATE, 
    access VARCHAR(10), 
    username VARCHAR(20),
    PRIMARY KEY (albumid),
    FOREIGN KEY (username) REFERENCES User(username)
    ON DELETE CASCADE ON UPDATE CASCADE);


CREATE TABLE Contain (
    albumid INT, 
    url VARCHAR(255), 
    caption VARCHAR(255), 
    sequencenum INT,
    PRIMARY KEY(albumid,url),
    FOREIGN KEY (albumid) REFERENCES Album(albumid)
    ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (url) REFERENCES Photo(url)
    ON DELETE CASCADE ON UPDATE CASCADE);


CREATE TABLE Photo (
    url VARCHAR(255), 
    format CHAR(3), 
    date DATE,
    PRIMARY KEY(url));


CREATE TABLE AlbumAccess (
    albumid INT, 
    username VARCHAR(20),
    PRIMARY KEY(albumid,username),
    FOREIGN KEY (username) REFERENCES User(username)
    ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (albumid) REFERENCES Album(albumid)
    ON DELETE CASCADE ON UPDATE CASCADE);


#show the created tables

show tables;

describe User;
describe Album;
describe Contain;
describe Photo;
describe AlbumAccess;
