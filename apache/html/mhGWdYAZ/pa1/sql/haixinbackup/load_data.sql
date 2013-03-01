

# part 3b: Loading data into tables
# the data should be loaded in the order according to the foreign key contraints

INSERT INTO User 
VALUES
 ('sportslover','Paul','Walker','123456','sportslover@hotmail.com'),
 ('traveler','Rebecca','Travolta','123456','rebt@explorer.org'),
 ('spacejunkie','Bob','Spacey','123456','bspace@spacejunkies.net');

INSERT INTO Album
VALUES 
 ('1','I love sports','2013-01-19','2013-01-19','public','sportslover'),
 ('2','I love football','2013-01-19','2013-01-19','public','sportslover'),
 ('3','Around The World','2013-01-19','2013-01-19','public','traveler'),
 ('4','Cool Space Shots','2013-01-19','2013-01-19','private','spacejunkie');


INSERT INTO Photo
VALUES
 ('pa1_images/images/sports_s1.jpg','JPG','2013-01-19'),
 ('pa1_images/images/sports_s2.jpg','JPG','2013-01-19'),
 ('pa1_images/images/sports_s3.jpg','JPG','2013-01-19'),
 ('pa1_images/images/sports_s4.jpg','JPG','2013-01-19'),
 ('pa1_images/images/sports_s5.jpg','JPG','2013-01-19'),
 ('pa1_images/images/sports_s6.jpg','JPG','2013-01-19'),
 ('pa1_images/images/sports_s7.jpg','JPG','2013-01-19'),
 ('pa1_images/images/sports_s8.jpg','JPG','2013-01-19'),

('pa1_images/images/football_s1.jpg','JPG','2013-01-19'),
('pa1_images/images/football_s2.jpg','JPG','2013-01-19'),
('pa1_images/images/football_s3.jpg','JPG','2013-01-19'),
('pa1_images/images/football_s4.jpg','JPG','2013-01-19'),

 ('pa1_images/images/world_EiffelTower.jpg','JPG','2013-01-19'),
 ('pa1_images/images/world_firenze.jpg','JPG','2013-01-19'),
 ('pa1_images/images/world_GreatWall.jpg','JPG','2013-01-19'),
 ('pa1_images/images/world_Isfahan.jpg','JPG','2013-01-19'),
 ('pa1_images/images/world_Istanbul.jpg','JPG','2013-01-19'),
 ('pa1_images/images/world_Persepolis.jpg','JPG','2013-01-19'),
 ('pa1_images/images/world_Reykjavik.jpg','JPG','2013-01-19'),
 ('pa1_images/images/world_Seoul.jpg','JPG','2013-01-19'),
 ('pa1_images/images/world_Stonehenge.jpg','JPG','2013-01-19'),
 ('pa1_images/images/world_TajMahal.jpg','JPG','2013-01-19'),
 ('pa1_images/images/world_TelAviv.jpg','JPG','2013-01-19'),
 ('pa1_images/images/world_tokyo.jpg','JPG','2013-01-19'),
 ('pa1_images/images/world_WashingtonDC.jpg','JPG','2013-01-19'),

 ('pa1_images/images/space_EagleNebula.jpg','JPG','2013-01-19'),
 ('pa1_images/images/space_GalaxyCollision.jpg','JPG','2013-01-19'),
 ('pa1_images/images/space_HelixNebula.jpg','JPG','2013-01-19'),
 ('pa1_images/images/space_MilkyWay.jpg','JPG','2013-01-19'),
 ('pa1_images/images/space_OrionNebula.jpg','JPG','2013-01-19');



INSERT INTO Contain
VALUES 
 ('1','pa1_images/images/sports_s1.jpg','sports_s1','1'),
 ('1','pa1_images/images/sports_s2.jpg','sports_s2','2'),
 ('1','pa1_images/images/sports_s3.jpg','sports_s3','3'),
 ('1','pa1_images/images/sports_s4.jpg','sports_s4','4'),
 ('1','pa1_images/images/sports_s5.jpg','sports_s5','5'),
 ('1','pa1_images/images/sports_s6.jpg','sports_s6','6'),
 ('1','pa1_images/images/sports_s7.jpg','sports_s7','7'),
 ('1','pa1_images/images/sports_s8.jpg','sports_s8','8'),

('2','pa1_images/images/football_s1.jpg','football_s1','1'),
('2','pa1_images/images/football_s2.jpg','football_s2','2'),
('2','pa1_images/images/football_s3.jpg','football_s3','3'),
('2','pa1_images/images/football_s4.jpg','football_s4','4'),


 ('3','pa1_images/images/world_EiffelTower.jpg','world_EiffelTower','1'),
 ('3','pa1_images/images/world_firenze.jpg','world_firenze','2'),
 ('3','pa1_images/images/world_GreatWall.jpg','world_GreatWall','3'),
 ('3','pa1_images/images/world_Isfahan.jpg','world_Isfahan','4'),
 ('3','pa1_images/images/world_Istanbul.jpg','world_Istanbul','5'),
 ('3','pa1_images/images/world_Persepolis.jpg','world_Persepolis','6'),
 ('3','pa1_images/images/world_Reykjavik.jpg','world_Reykjavik','7'),
 ('3','pa1_images/images/world_Seoul.jpg','world_Seoul','8'),
 ('3','pa1_images/images/world_Stonehenge.jpg','world_Istanbul','9'),
 ('3','pa1_images/images/world_TajMahal.jpg','world_TajMahal','10'),
 ('3','pa1_images/images/world_TelAviv.jpg','world_TelAviv','11'),
 ('3','pa1_images/images/world_tokyo.jpg','world_tokyo','12'),
 ('3','pa1_images/images/world_WashingtonDC.jpg','world_WashingtonDC','13'),

 ('4','pa1_images/images/space_EagleNebula.jpg','space_EagleNebula','1'),
 ('4','pa1_images/images/space_GalaxyCollision.jpg','space_GalaxyCollision','2'),
 ('4','pa1_images/images/space_HelixNebula.jpg','space_HelixNebula','3'),
 ('4','pa1_images/images/space_MilkyWay.jpg','space_MilkyWay','4'),
 ('4','pa1_images/images/space_OrionNebula.jpg','space_OrionNebula','5');
 


INSERT INTO AlbumAccess
VALUES 
 ('1','sportslover'),
 ('2','sportslover'),
 ('3','traveler'),
 ('4','spacejunkie');


# show the content of the tables

select * from User;
select * from Album;
select * from Contain;
select * from Photo;
select * from AlbumAccess;
