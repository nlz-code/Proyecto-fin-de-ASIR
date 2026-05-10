-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: localhost    Database: proyecto_db
-- ------------------------------------------------------
-- Server version	8.0.45

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `reservas`
--

DROP TABLE IF EXISTS `reservas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_usuario` varchar(50) NOT NULL,
  `numero_licencia` varchar(50) NOT NULL,
  `fecha_reserva` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_recogida` date NOT NULL,
  `hora_recogida` time NOT NULL,
  `direccion_recogida` varchar(255) NOT NULL,
  `estado` enum('pendiente','confirmada','completada','cancelada') DEFAULT 'pendiente',
  PRIMARY KEY (`id`),
  KEY `idx_usuario` (`nombre_usuario`),
  KEY `idx_taxista` (`numero_licencia`),
  KEY `idx_fecha` (`fecha_recogida`),
  CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`nombre_usuario`) REFERENCES `usuarios` (`nombre_usuario`) ON DELETE CASCADE,
  CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`numero_licencia`) REFERENCES `taxistas` (`numero_licencia`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservas`
--

LOCK TABLES `reservas` WRITE;
/*!40000 ALTER TABLE `reservas` DISABLE KEYS */;
INSERT INTO `reservas` VALUES (2,'nlopzay2502','TAX010','2026-04-12 15:29:54','2026-04-13','09:00:00','Calle San José, 2, torre del mar','completada');
/*!40000 ALTER TABLE `reservas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taxistas`
--

DROP TABLE IF EXISTS `taxistas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `taxistas` (
  `numero_licencia` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `horario` varchar(50) NOT NULL,
  PRIMARY KEY (`numero_licencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taxistas`
--

LOCK TABLES `taxistas` WRITE;
/*!40000 ALTER TABLE `taxistas` DISABLE KEYS */;
INSERT INTO `taxistas` VALUES ('TAX001','Juan','Prez Garca','600123001','08:00-16:00'),('TAX002','Mara','Lopez Fernandez','600123002','10:00-18:00'),('TAX003','Pedro','Gmez Snchez','600123003','10:00-18:00'),('TAX004','Ana','Martnez Ruiz','600123004','07:00-15:00'),('TAX005','Luis','Hernndez Daz','600123005','12:00-20:00'),('TAX006','Sofa','Ramrez Torres','600123006','08:00-16:00'),('TAX007','Carlos','Morales Castillo','600123007','09:00-17:00'),('TAX008','Luca','Vega Navarro','600123008','10:00-18:00'),('TAX009','Jorge','Ros Ortega','600123009','07:00-15:00'),('TAX010','Elena','Mendoza Salas','600123010','12:00-20:00'),('TAX011','Diego','Cruz Herrera','600123011','08:00-16:00'),('TAX012','Paula','Jimnez Molina','600123012','09:00-17:00'),('TAX013','Ivn','Garca Len','600123013','10:00-18:00'),('TAX014','Clara','Santos Paredes','600123014','07:00-15:00'),('TAX015','Ral','Domnguez Ramos','600123015','12:00-20:00'),('TAX016','Marta','Flores Delgado','600123016','08:00-16:00'),('TAX017','Alberto','Cordero Pea','600123017','09:00-17:00'),('TAX018','Isabel','Ortiz Cabrera','600123018','10:00-18:00'),('TAX019','Fernando','Salazar Gil','600123019','07:00-15:00'),('TAX020','Patricia','Romero Luna','600123020','12:00-20:00'),('TAX021','Hugo','Castillo Vega','600123021','08:00-16:00'),('TAX022','Valeria','Mora Santiago','600123022','09:00-17:00'),('TAX023','Sebastin','Fuentes Molina','600123023','10:00-18:00'),('TAX024','Camila','Rojas Castro','600123024','07:00-15:00'),('TAX025','Mateo','Surez Len','600123025','12:00-20:00');
/*!40000 ALTER TABLE `taxistas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `nombre_usuario` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) DEFAULT NULL,
  `domicilio` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo_electronico` varchar(150) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `verificado` tinyint(1) DEFAULT '0',
  `token_verificacion` varchar(255) DEFAULT NULL,
  `rol` enum('usuario','admin') DEFAULT 'usuario',
  PRIMARY KEY (`nombre_usuario`),
  UNIQUE KEY `correo_electronico` (`correo_electronico`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES ('admin','Administrador','Sistema',NULL,NULL,'admin@example.com','$2y$10$nuFj/BnMFDT5XDW7GLHTn.i2K4LiqLv/awPdDUNj.db0R4yFh6n6a',1,NULL,'admin'),('nlopzay2502','Noelia','López','Calle San José, nº2, 1ºB','654781239','noelialz2502@gmail.com','$2y$10$IApgg.FhpmW/dX4lflGcge/5vuX0RWckJBsG4mAXM3hME1kogWK.S',0,NULL,'usuario');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mensajes_contacto`
--

DROP TABLE IF EXISTS `mensajes_contacto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mensajes_contacto` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_usuario` varchar(50) NOT NULL,
  `opinion` enum('me_gusta','no_me_gusta') NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_mensajes_usuario` (`nombre_usuario`),
  KEY `idx_mensajes_fecha` (`fecha_creacion`),
  CONSTRAINT `mensajes_contacto_ibfk_1` FOREIGN KEY (`nombre_usuario`) REFERENCES `usuarios` (`nombre_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mensajes_contacto`
--

LOCK TABLES `mensajes_contacto` WRITE;
/*!40000 ALTER TABLE `mensajes_contacto` DISABLE KEYS */;
/*!40000 ALTER TABLE `mensajes_contacto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favoritos`
--

DROP TABLE IF EXISTS `favoritos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favoritos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_usuario` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `distancia` decimal(10,2) NOT NULL,
  `tiempo` int NOT NULL,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_favoritos_usuario` (`nombre_usuario`),
  CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`nombre_usuario`) REFERENCES `usuarios` (`nombre_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favoritos`
--

LOCK TABLES `favoritos` WRITE;
/*!40000 ALTER TABLE `favoritos` DISABLE KEYS */;
/*!40000 ALTER TABLE `favoritos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-12 16:05:56
