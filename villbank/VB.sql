CREATE TABLE user (
	userID INT(10) AUTO_INCREMENT PRIMARY KEY,
    fullName VARCHAR(100) NOT NULL,
    nrc VARCHAR(12) NOT NULL,
    phoneNumber VARCHAR(10) NOT NULL,
    gender VARCHAR(6) NOT NULL,
    password VARCHAR(100)
);