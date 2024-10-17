# prueba_tecnica_desarrollador_php

1) descargar el repositorio y moverlo a xamp o wampserver para iniciar el proceso de configuracion

2) en proyecto estara un archivo .sql que podran importar en su gestor de base de datos o simplemente copia este codigo sql

CREATE DATABASE bd_nubeico;
USE bd_nubeico;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES (1, 'Cristihan Lobo', 'cristihan.cl98@gmail.com', 'cris123456', '2024-10-17 11:56:23', '2024-10-17 11:56:24');
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    priority ENUM('baja', 'media', 'alta') NOT NULL,
    status SMALLINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE task_user (
    user_id INT NOT NULL,
    task_id INT NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, task_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE
);

3) luego de importar o ejecutar el codigo sql ingresan a la ruta http://localhost/prueba_tecnica_desarrollador_php/
4) inician sesion con el user: cristihan.cl98@gmail.com y password: cris123456
5) ya pueden iniciar con proceso de validacion de funcionamiento
#cualquier inquietud al numero de whatsapp 3232346794

