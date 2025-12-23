-- Adminer 5.4.1 MariaDB 12.1.2-MariaDB-ubu2404 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `alumnos`;
CREATE TABLE `alumnos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `dni` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `domicilio` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

INSERT INTO `alumnos` (`id`, `nombre`, `apellidos`, `dni`, `email`, `telefono`, `domicilio`) VALUES
(2,	'pepe',	'pepillo',	'2356667M',	'pepe@gmail.com',	'35366566',	'calle'),
(4,	'luis',	'luisito2',	'2356667M',	'luis@gmail.com',	'35366566',	'calle2'),
(5,	'luis',	'pepillo',	'454456M',	'admin@example.com',	'35366566',	'calle azul');

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

INSERT INTO `usuarios` (`id`, `email`, `nombre`, `password`) VALUES
(2,	'admin@example.com',	'admin',	'1234'),
(3,	'pepe@gmail.com',	'pepe',	'123456');

-- 2025-12-16 18:55:23 UTC
