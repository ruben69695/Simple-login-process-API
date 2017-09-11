CREATE DATABASE android_api;
USE android_api;
CREATE TABLE users (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    unique_id VARCHAR(23) NOT NULL UNIQUE,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    encrypted_password VARCHAR(80) NOT NULL,
    salt VARCHAR(10) NOT NULL,
    created_at DATETIME,
    updated_at DATETIME NULL
)  ENGINE=INNODB;