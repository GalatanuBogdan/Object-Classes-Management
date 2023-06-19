CREATE DATABASE IF NOT EXISTS t_web;

CREATE TABLE IF NOT EXISTS t_web.accounts
(
    id         SERIAL PRIMARY KEY,
    username   varchar(50)  NOT NULL,
    password   varchar(128) NOT NULL,
    first_name varchar(50)  NOT NULL,
    last_name  varchar(50)  NOT NULL
);

CREATE TABLE IF NOT EXISTS t_web.categories
(
    id   SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL CHECK (name <> ''),
    CONSTRAINT uc_name UNIQUE (name)
);

-- the password is: Password123!
INSERT INTO t_web.accounts (username, password, first_name, last_name)
VALUES ('john@gmail.com', '$2y$10$PJyhCc02yEAiP7E4PKTEF.zqubVv0xtwft4MaVMndW7aLO2HVLNZ.', 'John', 'Smith'),
       ('jane@gmail.com', '$2y$10$PJyhCc02yEAiP7E4PKTEF.zqubVv0xtwft4MaVMndW7aLO2HVLNZ.', 'Jane', 'Jones'),
       ('bob@gmail.com', '$2y$10$PJyhCc02yEAiP7E4PKTEF.zqubVv0xtwft4MaVMndW7aLO2HVLNZ.', 'Bob', 'Williams');

INSERT INTO t_web.categories (name)
VALUES ('Agentii Imobiliare'),
       ('Magazine Online'),
       ('Farmacii');

CREATE TABLE IF NOT EXISTS t_web.object_of_class
(
    id          SERIAL PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    ownerID     INT          NOT NULL,
    categoryID  INT          NOT NULL,
    pathImagine VARCHAR(256) NOT NULL
);

CREATE TABLE IF NOT EXISTS t_web.product_of_class
(
    id          SERIAL PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    utilizationContext VARCHAR(100) NOT NULL,
    trasaturaIndezirabila VARCHAR(100) NOT NULL,
    trasaturaDezirabila VARCHAR(100) NOT NULL,
    price INT,
    objectClassID INT NOT NULL,
    pathImagine VARCHAR(256) NOT NULL
);

CREATE TABLE IF NOT EXISTS t_web.product_reviews_likes
(
    id SERIAL PRIMARY KEY,
    ipAddress VARCHAR(256) NOT NULL,
    productID INT NOT NULL,
    UNIQUE KEY unique_review (ipAddress, productID)
);

CREATE TABLE IF NOT EXISTS t_web.product_reviews_dislikes
(
    id SERIAL PRIMARY KEY,
    ipAddress VARCHAR(256) NOT NULL,
    productID INT NOT NULL,
    UNIQUE KEY unique_review (ipAddress, productID)
);


CREATE TABLE IF NOT EXISTS t_web.product_reviews_likes
(
    id SERIAL PRIMARY KEY,
    ipAddress VARCHAR(256) NOT NULL,
    productID INT NOT NULL,
    UNIQUE KEY unique_review (ipAddress, productID)
);
