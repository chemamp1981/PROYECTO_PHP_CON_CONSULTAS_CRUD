-- Creación de base de datos

CREATE DATABASE IF NOT EXISTS noticias_odontologia CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;

-- Selección de base de datos

USE noticias_odontologia;

-- Creacción de entidades

CREATE TABLE IF NOT EXISTS USERS_data (
idUser INT NOT NULL AUTO_INCREMENT,
nombre VARCHAR(50) NOT NULL,
apellidos VARCHAR(100) NOT NULL,
email VARCHAR(50) NOT NULL UNIQUE,
telefono TEXT(15) NOT NULL,
fecha_nacimiento DATE NOT NULL,
direccion TEXT(50),
sexo TEXT(100),
PRIMARY KEY (idUser)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS users_login (
idLogin INT NOT NULL AUTO_INCREMENT,
idUser INT NOT NULL UNIQUE,
usuario VARCHAR(50) NOT NULL UNIQUE, # Colocar usuario de tipo TEXT me da error
password VARCHAR(255) NOT NULL,
rol ENUM ('admin','user') DEFAULT 'user',
PRIMARY KEY (idLogin),
CONSTRAINT fk_idUser_login
FOREIGN KEY(idUser) REFERENCES USERS_data(idUser)
ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS citas(
idCita INT NOT NULL AUTO_INCREMENT,
idUser INT NOT NULL,
fecha_cita DATE NOT NULL,
motivo_cita TEXT,
PRIMARY KEY (idCita),
CONSTRAINT fk_idUser_citas
FOREIGN KEY (idUser) REFERENCES USERS_data(idUser)
ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS noticias (
idNoticia INT NOT NULL AUTO_INCREMENT,
titulo VARCHAR(255) NOT NULL UNIQUE, # Colocar titulo de tipo TEXT me da error
imagen LONGBLOB NOT NULL,
texto LONGTEXT NOT NULL,
fecha DATE NOT NULL,
idUser INT NOT NULL,
PRIMARY KEY(idNoticia),
CONSTRAINT fk_idUser_noticia
FOREIGN KEY (idUser) REFERENCES USERS_data(idUser)
ON UPDATE CASCADE
)ENGINE=InnoDB;