-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.32-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para sistema_mentoria
CREATE DATABASE IF NOT EXISTS `sistema_mentoria` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `sistema_mentoria`;

-- Volcando estructura para tabla sistema_mentoria.administrador
CREATE TABLE IF NOT EXISTS `administrador` (
  `ID_ADMIN` int(11) NOT NULL AUTO_INCREMENT,
  `ID_USUARIO` int(11) DEFAULT NULL,
  `NIVEL_ACCESO` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_ADMIN`),
  KEY `ID_USUARIO` (`ID_USUARIO`),
  CONSTRAINT `administrador_ibfk_1` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.administrador: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sistema_mentoria.asistencia
CREATE TABLE IF NOT EXISTS `asistencia` (
  `ID_ESTUDIANTE` int(11) DEFAULT NULL,
  `ID_CLASE` int(11) DEFAULT NULL,
  `FECHA` datetime DEFAULT current_timestamp(),
  `ESTADO` tinyint(4) DEFAULT 1,
  KEY `ID_ESTUDIANTE` (`ID_ESTUDIANTE`),
  KEY `ID_CLASE` (`ID_CLASE`),
  CONSTRAINT `asistencia_ibfk_1` FOREIGN KEY (`ID_ESTUDIANTE`) REFERENCES `estudiante` (`ID_ESTUDIANTE`),
  CONSTRAINT `asistencia_ibfk_2` FOREIGN KEY (`ID_CLASE`) REFERENCES `clase` (`ID_CLASE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.asistencia: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sistema_mentoria.aula
CREATE TABLE IF NOT EXISTS `aula` (
  `ID_AULA` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE` varchar(100) DEFAULT NULL,
  `ESTADO` tinyint(1) DEFAULT NULL,
  `FECHA_REG` date DEFAULT NULL,
  PRIMARY KEY (`ID_AULA`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.aula: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sistema_mentoria.ciclo
CREATE TABLE IF NOT EXISTS `ciclo` (
  `ID_CICLO` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE` varchar(150) DEFAULT NULL,
  `ID_SEMESTRE` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_CICLO`),
  KEY `ID_SEMESTRE` (`ID_SEMESTRE`),
  CONSTRAINT `ciclo_ibfk_1` FOREIGN KEY (`ID_SEMESTRE`) REFERENCES `semestre_academico` (`ID_SEMESTRE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.ciclo: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sistema_mentoria.clase
CREATE TABLE IF NOT EXISTS `clase` (
  `ID_CLASE` int(11) NOT NULL AUTO_INCREMENT,
  `HORARIO` varchar(150) DEFAULT NULL,
  `ESTADO` tinyint(1) DEFAULT NULL,
  `FECHA_INICIO` datetime DEFAULT NULL,
  `FECHA_FIN` datetime DEFAULT NULL,
  `RAZON` varchar(100) DEFAULT NULL,
  `CAPACIDAD` int(11) DEFAULT NULL,
  `FECHA_REG` datetime DEFAULT current_timestamp(),
  `ID_AULA` int(11) DEFAULT NULL,
  `ID_CURSO` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_CLASE`),
  KEY `ID_AULA` (`ID_AULA`),
  KEY `ID_CURSO` (`ID_CURSO`),
  CONSTRAINT `clase_ibfk_1` FOREIGN KEY (`ID_AULA`) REFERENCES `aula` (`ID_AULA`),
  CONSTRAINT `clase_ibfk_2` FOREIGN KEY (`ID_CURSO`) REFERENCES `curso` (`ID_CURSO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.clase: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sistema_mentoria.comentario
CREATE TABLE IF NOT EXISTS `comentario` (
  `ID_COMENTARIO` int(11) NOT NULL AUTO_INCREMENT,
  `PUNTUACION` int(11) DEFAULT NULL,
  `FECHA_REG` datetime DEFAULT current_timestamp(),
  `ID_DOCENTE` int(11) DEFAULT NULL,
  `ID_ESTUDIANTE` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_COMENTARIO`),
  KEY `ID_DOCENTE` (`ID_DOCENTE`),
  KEY `ID_ESTUDIANTE` (`ID_ESTUDIANTE`),
  CONSTRAINT `comentario_ibfk_1` FOREIGN KEY (`ID_DOCENTE`) REFERENCES `docente` (`ID_DOCENTE`),
  CONSTRAINT `comentario_ibfk_2` FOREIGN KEY (`ID_ESTUDIANTE`) REFERENCES `estudiante` (`ID_ESTUDIANTE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.comentario: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sistema_mentoria.curso
CREATE TABLE IF NOT EXISTS `curso` (
  `ID_CURSO` int(11) NOT NULL AUTO_INCREMENT,
  `CODIGO` varchar(10) DEFAULT NULL,
  `NOMBRE` varchar(150) DEFAULT NULL,
  `ID_CICLO` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_CURSO`),
  KEY `ID_CICLO` (`ID_CICLO`),
  CONSTRAINT `curso_ibfk_1` FOREIGN KEY (`ID_CICLO`) REFERENCES `ciclo` (`ID_CICLO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.curso: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sistema_mentoria.docente
CREATE TABLE IF NOT EXISTS `docente` (
  `ID_DOCENTE` int(11) NOT NULL AUTO_INCREMENT,
  `ID_USUARIO` int(11) DEFAULT NULL,
  `ESTADO` tinyint(1) DEFAULT NULL,
  `FECHA_REG` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`ID_DOCENTE`),
  KEY `ID_USUARIO` (`ID_USUARIO`),
  CONSTRAINT `docente_ibfk_1` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.docente: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sistema_mentoria.estudiante
CREATE TABLE IF NOT EXISTS `estudiante` (
  `ID_ESTUDIANTE` int(11) NOT NULL AUTO_INCREMENT,
  `ID_USUARIO` int(11) DEFAULT NULL,
  `CODIGO` varchar(10) DEFAULT NULL,
  `EMAIL_CORPORATIVO` varchar(100) DEFAULT NULL,
  `FECHA_REG` datetime DEFAULT current_timestamp(),
  `CONDICION` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ID_ESTUDIANTE`),
  KEY `ID_USUARIO` (`ID_USUARIO`),
  CONSTRAINT `estudiante_ibfk_1` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.estudiante: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sistema_mentoria.inscripcion
CREATE TABLE IF NOT EXISTS `inscripcion` (
  `ID_CLASE` int(11) DEFAULT NULL,
  `ID_ESTUDIANTE` int(11) DEFAULT NULL,
  `FECHA_REG` datetime DEFAULT current_timestamp(),
  KEY `ID_CLASE` (`ID_CLASE`),
  KEY `ID_ESTUDIANTE` (`ID_ESTUDIANTE`),
  CONSTRAINT `inscripcion_ibfk_1` FOREIGN KEY (`ID_CLASE`) REFERENCES `clase` (`ID_CLASE`),
  CONSTRAINT `inscripcion_ibfk_2` FOREIGN KEY (`ID_ESTUDIANTE`) REFERENCES `estudiante` (`ID_ESTUDIANTE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.inscripcion: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sistema_mentoria.notas
CREATE TABLE IF NOT EXISTS `notas` (
  `ID_NOTAS` int(11) NOT NULL AUTO_INCREMENT,
  `ID_REGISTRO` int(11) NOT NULL,
  `ID_UNIDAD` int(11) NOT NULL,
  `TIPO_NOTA` varchar(100) NOT NULL,
  `CALIFICACION` decimal(5,2) NOT NULL CHECK (`CALIFICACION` between 0 and 20),
  `FECHA_REG` datetime DEFAULT current_timestamp(),
  `USUARIO_REGISTRADOR` int(11) DEFAULT NULL,
  `IP_REGISTRADOR` varchar(45) DEFAULT NULL,
  `OBSERVACION` text DEFAULT NULL,
  PRIMARY KEY (`ID_NOTAS`),
  KEY `ID_REGISTRO` (`ID_REGISTRO`),
  KEY `ID_UNIDAD` (`ID_UNIDAD`),
  KEY `USUARIO_REGISTRADOR` (`USUARIO_REGISTRADOR`),
  CONSTRAINT `notas_ibfk_1` FOREIGN KEY (`ID_REGISTRO`) REFERENCES `registro_academico` (`ID_REGISTRO`),
  CONSTRAINT `notas_ibfk_2` FOREIGN KEY (`ID_UNIDAD`) REFERENCES `unidad` (`ID_UNIDAD`),
  CONSTRAINT `notas_ibfk_3` FOREIGN KEY (`USUARIO_REGISTRADOR`) REFERENCES `usuario` (`ID_USUARIO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.notas: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sistema_mentoria.registro_academico
CREATE TABLE IF NOT EXISTS `registro_academico` (
  `ID_REGISTRO` int(11) NOT NULL AUTO_INCREMENT,
  `ID_DOCENTE` int(11) DEFAULT NULL,
  `ID_ESTUDIANTE` int(11) DEFAULT NULL,
  `ID_CLASE` int(11) DEFAULT NULL,
  `ID_UNIDAD` int(11) DEFAULT NULL,
  `FECHA_REG` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`ID_REGISTRO`),
  KEY `ID_DOCENTE` (`ID_DOCENTE`),
  KEY `ID_ESTUDIANTE` (`ID_ESTUDIANTE`),
  KEY `ID_CLASE` (`ID_CLASE`),
  KEY `ID_UNIDAD` (`ID_UNIDAD`),
  CONSTRAINT `registro_academico_ibfk_1` FOREIGN KEY (`ID_DOCENTE`) REFERENCES `docente` (`ID_DOCENTE`),
  CONSTRAINT `registro_academico_ibfk_2` FOREIGN KEY (`ID_ESTUDIANTE`) REFERENCES `estudiante` (`ID_ESTUDIANTE`),
  CONSTRAINT `registro_academico_ibfk_3` FOREIGN KEY (`ID_CLASE`) REFERENCES `clase` (`ID_CLASE`),
  CONSTRAINT `registro_academico_ibfk_4` FOREIGN KEY (`ID_UNIDAD`) REFERENCES `unidad` (`ID_UNIDAD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.registro_academico: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sistema_mentoria.rol
CREATE TABLE IF NOT EXISTS `rol` (
  `ID_ROL` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE` varchar(50) NOT NULL,
  PRIMARY KEY (`ID_ROL`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.rol: ~4 rows (aproximadamente)
INSERT INTO `rol` (`ID_ROL`, `NOMBRE`) VALUES
	(1, 'VISITANTE'),
	(2, 'ESTUDIANTE'),
	(3, 'DOCENTE'),
	(4, 'ADMINISTRADOR');

-- Volcando estructura para tabla sistema_mentoria.roles_asignados
CREATE TABLE IF NOT EXISTS `roles_asignados` (
  `ID_USUARIO` int(11) DEFAULT NULL,
  `ID_ROL` int(11) DEFAULT NULL,
  `FECHA_REG` datetime DEFAULT current_timestamp(),
  `ESTADO` tinyint(1) DEFAULT 1,
  KEY `ID_USUARIO` (`ID_USUARIO`),
  KEY `ID_ROL` (`ID_ROL`),
  CONSTRAINT `roles_asignados_ibfk_1` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`),
  CONSTRAINT `roles_asignados_ibfk_2` FOREIGN KEY (`ID_ROL`) REFERENCES `rol` (`ID_ROL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.roles_asignados: ~1 rows (aproximadamente)
INSERT INTO `roles_asignados` (`ID_USUARIO`, `ID_ROL`, `FECHA_REG`, `ESTADO`) VALUES
	(1, 2, '2025-05-20 13:46:43', 1),
	(13, 1, '2025-06-05 14:19:10', 1);

-- Volcando estructura para tabla sistema_mentoria.semestre_academico
CREATE TABLE IF NOT EXISTS `semestre_academico` (
  `ID_SEMESTRE` int(11) NOT NULL AUTO_INCREMENT,
  `CODIGO` varchar(100) DEFAULT NULL,
  `ID_ESTUDIANTE` int(11) DEFAULT NULL,
  `FECHA` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`ID_SEMESTRE`),
  KEY `ID_ESTUDIANTE` (`ID_ESTUDIANTE`),
  CONSTRAINT `semestre_academico_ibfk_1` FOREIGN KEY (`ID_ESTUDIANTE`) REFERENCES `estudiante` (`ID_ESTUDIANTE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.semestre_academico: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sistema_mentoria.unidad
CREATE TABLE IF NOT EXISTS `unidad` (
  `ID_UNIDAD` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ID_UNIDAD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.unidad: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sistema_mentoria.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `ID_USUARIO` int(11) NOT NULL AUTO_INCREMENT,
  `DNI` varchar(10) DEFAULT NULL,
  `NOMBRE` varchar(100) DEFAULT NULL,
  `APELLIDO` varchar(100) DEFAULT NULL,
  `EMAIL` varchar(100) DEFAULT NULL,
  `CELULAR` varchar(100) DEFAULT NULL,
  `PASSWORD` varchar(100) DEFAULT NULL,
  `FECHA_REG` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`ID_USUARIO`),
  UNIQUE KEY `DNI` (`DNI`),
  UNIQUE KEY `EMAIL` (`EMAIL`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla sistema_mentoria.usuario: ~2 rows (aproximadamente)
INSERT INTO `usuario` (`ID_USUARIO`, `DNI`, `NOMBRE`, `APELLIDO`, `EMAIL`, `CELULAR`, `PASSWORD`, `FECHA_REG`) VALUES
	(1, NULL, 'GREGORY BRANDON', 'HUANCA MERMA', 'gh2022073898@virtual.upt.pe', NULL, NULL, '2025-05-20 12:32:35'),
	(13, '77436156', 'GREGORY BRANDON', 'HUANCA MERMA', 'sefht7893@gmail.com', NULL, '$2y$10$TAOiROBuzhpx.ApY4eyWbeIx1dcieU1vPb0vjolKm3gK2hwR4Zr/.', '2025-06-05 14:19:10');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
