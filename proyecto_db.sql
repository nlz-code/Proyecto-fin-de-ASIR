-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-05-2026 a las 16:02:58
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecto_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritos`
--

CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `distancia` decimal(10,2) DEFAULT NULL,
  `tiempo` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `favoritos`
--

INSERT INTO `favoritos` (`id`, `nombre_usuario`, `nombre`, `distancia`, `tiempo`, `fecha_creacion`) VALUES
(2, 'nlopzay2502', 'Casa de mi novio', 9.86, 13, '2026-05-10 17:48:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes_contacto`
--

CREATE TABLE `mensajes_contacto` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `opinion` enum('me_gusta','no_me_gusta') NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensajes_contacto`
--

INSERT INTO `mensajes_contacto` (`id`, `nombre_usuario`, `opinion`, `mensaje`, `fecha_creacion`) VALUES
(2, 'nlopzay2502', 'me_gusta', 'Me encanta es perfecta', '2026-05-10 18:22:51'),
(3, 'DAYVA', 'me_gusta', 'es buena para buscar rápido.', '2026-05-10 19:56:54'),
(4, 'nlopzay2502', 'me_gusta', 'Es muy intuitiva y fácil de manejar', '2026-05-21 19:24:15'),
(5, 'nlopzay2502', 'me_gusta', 'muy buena', '2026-05-21 19:31:49'),
(6, 'nlopzay2502', 'me_gusta', 'we', '2026-05-21 19:31:55'),
(7, 'nlopzay2502', 'me_gusta', 'we', '2026-05-21 19:31:58'),
(8, 'nlopzay2502', 'me_gusta', 'we', '2026-05-21 19:32:01'),
(9, 'nlopzay2502', 'me_gusta', 'we', '2026-05-21 19:32:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `numero_licencia` varchar(50) NOT NULL,
  `fecha_reserva` datetime DEFAULT current_timestamp(),
  `fecha_recogida` date NOT NULL,
  `hora_recogida` time NOT NULL,
  `direccion_recogida` varchar(255) NOT NULL,
  `estado` enum('pendiente','confirmada','completada','cancelada') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `nombre_usuario`, `numero_licencia`, `fecha_reserva`, `fecha_recogida`, `hora_recogida`, `direccion_recogida`, `estado`) VALUES
(2, 'nlopzay2502', 'TAX010', '2026-04-12 15:29:54', '2026-04-13', '09:00:00', 'Calle San José, 2, torre del mar', 'completada'),
(3, 'nlopzay2502', 'TAX043', '2026-05-10 21:57:52', '2026-05-12', '10:00:00', 'Calle Escribano 39, Benajarafe, Málaga', 'completada'),
(5, 'nlopzay2502', 'TAX017', '2026-05-15 15:31:58', '2026-05-20', '07:00:00', 'Calle San José 2 29740 Torre del Mar', 'completada'),
(6, 'nlopzay2502', 'TAX005', '2026-05-21 19:41:13', '2026-05-21', '23:00:00', 'Calle hipeta, 32', 'pendiente'),
(7, 'nlopzay2502', 'TAX025', '2026-05-21 19:41:14', '2026-05-21', '12:45:00', 'Calle San Juan, 2', 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `taxistas`
--

CREATE TABLE `taxistas` (
  `numero_licencia` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `horario` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `taxistas`
--

INSERT INTO `taxistas` (`numero_licencia`, `nombre`, `apellidos`, `telefono`, `horario`) VALUES
('TAX001', 'Juan', 'Pérez García', '600123001', '08:00-16:00'),
('TAX002', 'Mara', 'Lopez Fernandez', '600123002', '10:00-18:00'),
('TAX003', 'Pedro', 'Gómez Sánchez', '600123003', '10:00-18:00'),
('TAX004', 'Ana', 'Martinez Ruiz', '600123004', '07:00-15:00'),
('TAX005', 'Luis', 'Hernández Daz', '600123005', '12:00-20:00'),
('TAX006', 'Sofa', 'Ramírez Torres', '600123006', '08:00-16:00'),
('TAX007', 'Carlos', 'Morales Castillo', '600123007', '09:00-17:00'),
('TAX008', 'Luca', 'Vega Navarro', '600123008', '10:00-18:00'),
('TAX009', 'Jorge', 'Ros Ortega', '600123009', '07:00-15:00'),
('TAX010', 'Elena', 'Mendoza Salas', '600123010', '12:00-20:00'),
('TAX011', 'Diego', 'Cruz Herrera', '600123011', '08:00-16:00'),
('TAX012', 'Paula', 'Jimenez Molina', '600123012', '09:00-17:00'),
('TAX013', 'Iván', 'García Len', '600123013', '10:00-18:00'),
('TAX014', 'Clara', 'Santos Paredes', '600123014', '07:00-15:00'),
('TAX015', 'Raúl', 'Domnguez Ramos', '600123015', '12:00-20:00'),
('TAX016', 'Marta', 'Flores Delgado', '600123016', '08:00-16:00'),
('TAX017', 'Alberto', 'Cordero Pea', '600123017', '09:00-17:00'),
('TAX018', 'Isabel', 'Ortiz Cabrera', '600123018', '10:00-18:00'),
('TAX019', 'Fernando', 'Salazar Gil', '600123019', '07:00-15:00'),
('TAX020', 'Patricia', 'Romero Luna', '600123020', '12:00-20:00'),
('TAX021', 'Hugo', 'Castillo Vega', '600123021', '08:00-16:00'),
('TAX022', 'Valeria', 'Mora Santiago', '600123022', '09:00-17:00'),
('TAX023', 'Sebastián', 'Fuentes Molina', '600123023', '10:00-18:00'),
('TAX024', 'Camila', 'Rojas Castro', '600123024', '07:00-15:00'),
('TAX025', 'Mateo', 'Surez Len', '600123025', '12:00-20:00'),
('TAX026', 'Marina', 'López Ruiz', '600123026', '22:00-06:00'),
('TAX027', 'Javier', 'Fernández Soto', '600123027', '23:00-07:00'),
('TAX028', 'Lucía', 'Martín Castro', '600123028', '21:00-05:00'),
('TAX029', 'Raúl', 'Jiménez Vega', '600123029', '22:00-06:00'),
('TAX030', 'Carmen', 'Navarro Díaz', '600123030', '23:00-07:00'),
('TAX031', 'Sergio', 'Morales León', '600123031', '21:00-05:00'),
('TAX032', 'Elena', 'Torres Gil', '600123032', '22:00-06:00'),
('TAX033', 'David', 'Ortega Cano', '600123033', '23:00-07:00'),
('TAX034', 'Paula', 'Romero Cruz', '600123034', '20:00-05:00'),
('TAX035', 'Iván', 'Sánchez Molina', '600123035', '22:00-06:00'),
('TAX036', 'Natalia', 'Delgado Ramos', '600123036', '23:00-07:00'),
('TAX037', 'Rubén', 'Herrera Peña', '600123037', '21:00-05:00'),
('TAX038', 'Sara', 'Medina Flores', '600123038', '22:00-06:00'),
('TAX039', 'Álvaro', 'Castro Núñez', '600123039', '23:00-07:00'),
('TAX040', 'Marta', 'Vargas Serrano', '600123040', '21:00-05:00'),
('TAX041', 'Adrián', 'Reyes Lozano', '600123041', '22:00-06:00'),
('TAX042', 'Claudia', 'Iglesias Pardo', '600123042', '23:00-07:00'),
('TAX043', 'Hugo', 'Cabrera Vidal', '600123043', '20:00-04:00'),
('TAX044', 'Julia', 'Mendoza Prieto', '600123044', '22:00-06:00'),
('TAX045', 'Mario', 'Fuentes Rivas', '600123045', '23:00-07:00'),
('TAX046', 'Andrea', 'Peña Salas', '600123046', '21:00-05:00'),
('TAX047', 'Diego', 'Campos Ortiz', '600123047', '22:00-06:00'),
('TAX048', 'Valeria', 'Suárez Méndez', '600123048', '23:00-07:00'),
('TAX049', 'Pablo', 'Gil Herrera', '600123049', '21:00-05:00'),
('TAX050', 'Noelia', 'Velasco Romero', '600123050', '22:00-06:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `nombre_usuario` varchar(50) NOT NULL,
  `nombre` char(10) NOT NULL,
  `apellidos` char(100) DEFAULT NULL,
  `domicilio` varchar(255) DEFAULT NULL,
  `telefono` int(9) DEFAULT NULL,
  `correo_electronico` varchar(150) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `rol` enum('usuario','admin') DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`nombre_usuario`, `nombre`, `apellidos`, `domicilio`, `telefono`, `correo_electronico`, `clave`, `rol`) VALUES
('admin', 'Administra', 'Sistema', NULL, NULL, 'admin@example.com', '$2y$10$nuFj/BnMFDT5XDW7GLHTn.i2K4LiqLv/awPdDUNj.db0R4yFh6n6a', 'admin'),
('DAYVA', 'ANA VANESA', 'ZAYAS LOPEZ', 'C/ SAN JOSE Nº2 1º B', 664659869, 'vanesa0104@gmail.com', '$2y$10$P4Lek20LKViIGhNWEIkRDevuKiu.WH0Znx3HZniDF.cF809S5FKcW', 'usuario'),
('ElTrabucazo32', 'ElTrabucaz', '32', 'C/Villapolla 51', 666666666, 'eltrabucazo32@gmail.com', '$2y$10$9Q/GQ5/gihaEJ4TWO.TlX.5jfNxdXj8WLgnoibI6AJhhpK06l3kTW', 'usuario'),
('juanmorales_25', 'Juan', 'Morales Castro', 'calle hipeta, 38', 642137600, 'juanmorales@example.com', '$2y$10$CvQY9Pa2PXc0XFItj/N1CeQ4uSnGJB2zM1LOQP41nMuYe/uyW9E9S', 'usuario'),
('nlopzay2502', 'Noelia', 'López', 'Calle San José, nº2, 1ºB', 642137600, 'noelialz2502@gmail.com', '$2y$10$IApgg.FhpmW/dX4lflGcge/5vuX0RWckJBsG4mAXM3hME1kogWK.S', 'usuario'),
('noelialz2502', 'Noelia', 'López Zayas', 'Calle San José, 2, 1ºB', 123456789, 'nlopzay2502@g.educaand.es', '$2y$10$E79iJpw5Lbqr0O2pWG.JYOs0s5jYN/7rbdw0D7xdK3JECunJQLqxi', 'usuario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nombre_usuario` (`nombre_usuario`);

--
-- Indices de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mensajes_usuario` (`nombre_usuario`),
  ADD KEY `idx_mensajes_fecha` (`fecha_creacion`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usuario` (`nombre_usuario`),
  ADD KEY `idx_taxista` (`numero_licencia`),
  ADD KEY `idx_fecha` (`fecha_recogida`);

--
-- Indices de la tabla `taxistas`
--
ALTER TABLE `taxistas`
  ADD PRIMARY KEY (`numero_licencia`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`nombre_usuario`),
  ADD UNIQUE KEY `correo_electronico` (`correo_electronico`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`nombre_usuario`) REFERENCES `usuarios` (`nombre_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  ADD CONSTRAINT `mensajes_contacto_ibfk_1` FOREIGN KEY (`nombre_usuario`) REFERENCES `usuarios` (`nombre_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`nombre_usuario`) REFERENCES `usuarios` (`nombre_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`numero_licencia`) REFERENCES `taxistas` (`numero_licencia`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
