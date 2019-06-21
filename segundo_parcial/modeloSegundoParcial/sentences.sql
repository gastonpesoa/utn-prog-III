DROP TABLE IF EXISTS `Usuario`;
CREATE TABLE `Usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `clave` varchar(250) NOT NULL,  
  `sexo` varchar(50) NOT NULL,
  `perfil` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
)DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

DROP TABLE IF EXISTS `Compra`;
CREATE TABLE `utn_prog_III`.`Compra` ( 
  `id` INT(11) NOT NULL AUTO_INCREMENT , 
  `idUsuario` INT(11) NOT NULL , 
  `articulo` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL , 
  `fecha` DATETIME(6) NOT NULL , 
  `precio` DOUBLE(11,2) NOT NULL , 
  PRIMARY KEY (`id`)) ENGINE = InnoDB;