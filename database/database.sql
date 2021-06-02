CREATE DATABASE IF NOT EXISTS reportingtasks;
USE reportingtasks;

CREATE TABLE IF NOT EXISTS usuarios(
    id int(255) auto_increment not null,
    nombre varchar(255) not null,
    usuario varchar(255) not null,
    password varchar(255) not null,
    rol varchar(255) null,
    estado varchar(255) null,
    created_at datetime,
    updated_at datetime,
    rememberToken  varchar(255) null,
    CONSTRAINT pk_users PRIMARY KEY (id),
    UNIQUE (id, nombre_responsable)

)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS tareas(
    id int(255) auto_increment not null,
    nombre_informe varchar(255) not null,
    servicio varchar(255) not null,
    subservicio varchar(255) not null,
    sede varchar(255) not null,
    tipo varchar(255) not null,
    marca varchar(255) not null,
    tiempo_completar float(24) not null,
    envio_cliente varchar(255),
    frecuencia varchar(255) not null,
    dia varchar(255) not null,
    hora_limite time not null,
    responsableId int(255) not null,
    estado varchar(255) not null,
    CONSTRAINT pk_task PRIMARY KEY (id),
    CONSTRAINT fk_responsable_usuarios FOREIGN KEY (responsableId) REFERENCES usuarios(id)

)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS actividad(
    id int(255) auto_increment not null,
    responsableId int(255) not null,
    informe_id int(255) not null,
    informe varchar(255) not null,
    fecha date not null,
    entregado varchar(255) not null,
    retraso varchar(255),
    incidencia varchar(255),
    comentario_incidencia varchar(255),
    error varchar(255),
    procede varchar(255),
    nivel_error varchar(255),
    CONSTRAINT pk_activity PRIMARY KEY (id),
    CONSTRAINT fk_responsable2_usuarios FOREIGN KEY (responsableId) REFERENCES usuarios(id),
    CONSTRAINT fk_idInforme_tareas FOREIGN KEY (informe_id) REFERENCES tareas(id)

)ENGINE=InnoDB;

/* Drops */
drop table usuarios;
drop table tareas;
drop table actividad;

/* Users */
INSERT INTO usuarios VALUES (null, 'Hector Esquerdo Valverde', 'hesquerdov', 'Abril2021', 'admin', 'activo', CURTIME(), CURTIME(), null);
INSERT INTO usuarios VALUES (null, 'Abigail Sanchez Molina', 'asanchezm', 'Abril2021', 'admin', 'activo', CURTIME(),  CURTIME(), null);
INSERT INTO usuarios VALUES (null, 'Marcelo Fernandez Da Silva', 'mfernandezds', 'Abril2021', 'usuario', 'activo', CURTIME(),  CURTIME(), null);

/* Activity */
INSERT INTO actividad VALUES (null, 3, 3, 'Productivo Inbound', CURTIME(), 'SI', 'NO', 'NO', '', 'NO', '', '');

