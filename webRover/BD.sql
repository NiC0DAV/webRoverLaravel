CREATE DATABASE roverAgricultor;
USE roverAgricultor;

/*
SELECT DATE_FORMAT(fecha, '%Y-%m-%d') DATEONLY, 
DATE_FORMAT(fecha,'%H:%i:%s') TIMEONLY
*/
CREATE TABLE users(
	id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR (100) NOT NULL,
    surname VARCHAR (50),
    role VARCHAR (50),
    identification BIGINT NOT NULL,
    contactNumber BIGINT NOT NULL,
    email VARCHAR(100),
    password VARCHAR(255),
    created_at DATETIME,
    updated_at DATETIME,
    remember_token VARCHAR(255),
    CONSTRAINT PK_usersTable PRIMARY KEY (id)
);

CREATE TABLE greenhouses(
	id INT NOT NULL AUTO_INCREMENT,
    id_user INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL DEFAULT 'Bogota D.C',
    city VARCHAR(100) NOT NULL DEFAULT 'Bogota D.C',
	address VARCHAR(100),
	weatherType VARCHAR(100) NOT NULL DEFAULT 'Clima Frio',
    created_at DATETIME,
    updated_at DATETIME,
    CONSTRAINT PK_invernaderoTable PRIMARY KEY (id),
    CONSTRAINT FK_invernaderoUsersTable FOREIGN KEY (id_user) REFERENCES users(id)
);

CREATE TABLE crops(
	id INT NOT NULL AUTO_INCREMENT,
    id_invernadero INT NOT NULL,
    name VARCHAR(100) NOT NULL,
	descripcion TEXT NOT NULL,
    created_at DATETIME,
    updated_at DATETIME,
    CONSTRAINT PK_cultivosTable PRIMARY KEY (id),
    CONSTRAINT FK_cultivosInvernadero_table FOREIGN KEY (id_invernadero) REFERENCES greenhouses(id)
);

CREATE TABLE temperatures(
	id INT NOT NULL AUTO_INCREMENT,
    id_cultivo INT NOT NULL,
    date DATETIME,
    celsiusDegrees VARCHAR (100),
    created_at DATETIME,
    updated_at DATETIME,
    CONSTRAINT PK_temperaturaTable PRIMARY KEY (id),
    CONSTRAINT FK_temperaturaCultivo_tables FOREIGN KEY (id_cultivo) REFERENCES crops(id)
);

CREATE TABLE brightness(
	id INT NOT NULL AUTO_INCREMENT,
    id_cultivo INT NOT NULL,
    date DATETIME,
    photons VARCHAR (100),
    created_at DATETIME,
    updated_at DATETIME,
    CONSTRAINT PK_luminosidad PRIMARY KEY (id),
    CONSTRAINT FK_luminosidadCultivo FOREIGN KEY (id_cultivo) REFERENCES crops(id)
);

CREATE TABLE dampness(
	id INT NOT NULL AUTO_INCREMENT,
    id_cultivo INT NOT NULL,
    date DATETIME,
    siemens VARCHAR(100),
    created_at DATETIME,
    updated_at DATETIME,
	CONSTRAINT PK_humedad PRIMARY KEY (id),
    CONSTRAINT FK_humedadCultivo FOREIGN KEY (id_cultivo) REFERENCES crops(id)
);
