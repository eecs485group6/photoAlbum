# Extra Credit 3: Create table with image url, image type, image size and image bytes
DROP TABLE Data;
CREATE TABLE Data (
    url VARCHAR(255) NOT NULL,
	mime_type VARCHAR(255) NOT NULL,
	file_size INT NOT NULL,
	image_data MEDIUMBLOB NOT NULL,	
    PRIMARY KEY(url),
	INDEX(url));



#show the created table

show tables;
describe Data;

