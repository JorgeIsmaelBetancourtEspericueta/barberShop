/* Crear base de datos */
CREATE DATABASE barberia;

/* Poner en uso la base de datos */
USE barberia;

/* Tabla para barberos */
CREATE TABLE barbero (
  idBarbero INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(256) NOT NULL,
  telefono VARCHAR(13) NOT NULL,
  PRIMARY KEY (idBarbero)
);

/* Tabla para usuarios */
CREATE TABLE usuarios (
  idUsuario INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(256) NOT NULL,
  password VARCHAR(255) NOT NULL,
  telefono VARCHAR(13) NOT NULL,
  es_admin TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (idUsuario)
);

/* Tabla para citas */
CREATE TABLE citas (
  idCita INT NOT NULL AUTO_INCREMENT,
  fecha DATE NOT NULL,
  hora TIME NOT NULL,
  servicio VARCHAR(100) NOT NULL,
  idUsuario INT NOT NULL,
  idBarbero INT NOT NULL,
  PRIMARY KEY (idCita),
  KEY fk_citas_usuario (idUsuario),
  KEY fk_citas_barbero (idBarbero),
  CONSTRAINT fk_citas_barbero FOREIGN KEY (idBarbero) REFERENCES barbero (idBarbero),
  CONSTRAINT fk_citas_usuario FOREIGN KEY (idUsuario) REFERENCES usuarios (idUsuario)
);
