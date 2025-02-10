<?php

defined('BASEPATH') or exit('No direct script access allowed');


// ------cleaning ONLY DEVELOPMENT-----------
$CI->db->query('
  DROP TABLE IF EXISTS ' . db_prefix() . 'firma_electronica_wti
');

$CI->db->query('
  DROP TABLE IF EXISTS ' . db_prefix() . 'secretaria_traslado_wti
');

$CI->db->query('
  DROP TABLE IF EXISTS ' . db_prefix() . 'secretaria_promocion_wti
');

$CI->db->query('
  DROP TABLE IF EXISTS ' . db_prefix() . 'pagos_wti
');

$CI->db->query('
  DROP TABLE IF EXISTS ' . db_prefix() . 'persona_retira_wti
');

$CI->db->query('
  DROP TABLE IF EXISTS ' . db_prefix() . 'retiros_wti
');

$CI->db->query('
  DROP TABLE IF EXISTS ' . db_prefix() . 'escuela_wti
');

// -----------------


$CI->db->query('
  INSERT INTO tblroles(name) 
  VALUES
  ("Director Área Scuola Nido"),
  ("Director Área Scuola Materna"),
  ("Director Área Elementare"),
  ("Director Área Media Inferior"),
  ("Director Área Media Superior"),
  ("Secretaría Académica"),
  ("Administración"),
  ("Secretaría Scolastica"),
  ("Rectoría"),
  ("Supervisor")
');


$CI->db->query('
  CREATE TABLE IF NOT EXISTS `' . db_prefix() . "escuela_wti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(192) NOT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';'
);

$CI->db->query('
  INSERT INTO tblescuela_wti(nombre) 
  VALUES
  ("Scuola Nido"),
  ("Scuola Materna"),
  ("Elementare"),
  ("Media Inferior"),
  ("Media Superior")
');
  
// aqui el status se cambia a varchar después
$CI->db->query('
  CREATE TABLE IF NOT EXISTS `' . db_prefix() . "retiros_wti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rut_estudiante` varchar(192) NOT NULL,
  `fecha_aviso` datetime NOT NULL,
  `fecha_retiro` datetime NOT NULL,
  `contador_fechas_establecidas` int(11) NOT NULL,
  `año_academico` enum('actual' , 'próximo') NOT NULL,
  `tipo` enum('permanente' , 'temporal') NOT NULL,
  `colegio_paritario` boolean,
  `promocion_anticipada` boolean,
  `curso` varchar(192) NOT NULL,
  `motivo_retiro` varchar(192),
  `nombre_documentacion` varchar(192),
  `observaciones` text,
  `observaciones_director_area` text,
  `estatus` enum('abierto' , 'pendiente' , 'pendiente_ratificado' , 'anulado' , 'anulado_en_revision' , 'posible_reapertura' , 'por_firmar_NULLA', 'formalizado' , 'cerrado') NOT NULL,
  `fecha_ultima_actualizacion_estatus` datetime NOT NULL,
  `motivo_anulacion` text,
  `motivo_posible_reapertura` text,
  `fk_escuela` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`fk_escuela`) REFERENCES " . db_prefix() . "escuela_wti(id) ON DELETE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';'
);


$CI->db->query('
  CREATE TABLE IF NOT EXISTS `' . db_prefix() . "persona_retira_wti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `es_apoderado` boolean,
  `nombre` varchar(192),
  `correo` varchar(192),
  `telefono` varchar(192),
  `rut` varchar(192),
  `fk_retiro` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`fk_retiro`) REFERENCES " . db_prefix() . "retiros_wti(id) ON DELETE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';'
);


$CI->db->query('
  CREATE TABLE IF NOT EXISTS `' . db_prefix() . "adjuntos_retiro_wti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(192) NOT NULL,
  `url` varchar(255) NOT NULL,
  `tipo` varchar(192),
  `fk_retiro` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`fk_retiro`) REFERENCES " . db_prefix() . "retiros_wti(id) ON DELETE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';'
);


$CI->db->query('
  CREATE TABLE IF NOT EXISTS `' . db_prefix() . "pagos_wti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `forma_pago` enum('anticipado' , 'mensual') NOT NULL,
  `calculo_proporcoinal` boolean NOT NULL,
  `nro_folio` varchar(192),
  `nro_nota_credito` varchar(192),
  `nro_egreso_devolucion` varchar(192),
  `id_rebaja_sistema` varchar(192),
  `observaciones` text,
  `finalizado` boolean NOT NULL,
  `fk_retiro` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`fk_retiro`) REFERENCES " . db_prefix() . "retiros_wti(id) ON DELETE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';'
);




$CI->db->query('
  CREATE TABLE IF NOT EXISTS `' . db_prefix() . "secretaria_promocion_wti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_emision_certificado_anual` datetime,
  `fecha_firma_certificado_anual` datetime,
  `fecha_modifica_estado` datetime,
  `img_notifica_estado` varchar(192),
  `observaciones` text,
  `finalizado` boolean NOT NULL,
  `fk_retiro` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`fk_retiro`) REFERENCES " . db_prefix() . "retiros_wti(id) ON DELETE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';'
);



$CI->db->query('
  CREATE TABLE IF NOT EXISTS `' . db_prefix() . "secretaria_traslado_wti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_emision_certificado_pagella` datetime,
  `fecha_firma_certificado_pagella` datetime,
  `fecha_modifica_estado` datetime,
  `img_notifica_estado` varchar(192),
  `observaciones` text,
  `pdf_nulla_osta` varchar(192),
  `finalizado` boolean NOT NULL,
  `fk_retiro` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`fk_retiro`) REFERENCES " . db_prefix() . "retiros_wti(id) ON DELETE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';'
);



$CI->db->query('
  CREATE TABLE IF NOT EXISTS `' . db_prefix() . "firma_electronica_wti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quien_firma` enum('persona' , 'director' , 'rector') NOT NULL,
  `fecha` datetime,
  `fk_retiro` int(11),
  `fk_secretaria_traslado` int(11),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`fk_retiro`) REFERENCES " . db_prefix() . "retiros_wti(id) ON DELETE CASCADE,
  FOREIGN KEY (`fk_secretaria_traslado`) REFERENCES " . db_prefix() . "secretaria_traslado_wti(id) ON DELETE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';'
);

    



      

  












