CREATE DATABASE pokemon;

USE pokemon;

CREATE TABLE users (
	id varchar(100) NOT NULL,
    name varchar(16) NULL,
	email varchar(100) NULL,
    password varchar(512) NULL,
    CONSTRAINT users_pk PRIMARY KEY (id)
)	
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE favorito (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id VARCHAR(255),
  pokemon_id INT,
  pokemon_name VARCHAR(255),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


SELECT * FROM pokemon.users;


