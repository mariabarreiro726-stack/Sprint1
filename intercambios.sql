CREATE TABLE `intercambios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,

  `publicacion_id` int(11) NOT NULL,
  `solicitante_id` int(11) NOT NULL,

  `estado` enum('pendiente','aceptado','rechazado') DEFAULT 'pendiente',

  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),

  /* PRODUCTO QUE OFRECE EL USUARIO */
  `producto_nombre` varchar(100) NOT NULL,
  `producto_descripcion` text NOT NULL,
  `producto_imagen` varchar(255) NOT NULL,
  `producto_vencimiento` date NOT NULL,

  PRIMARY KEY (`id`),

  KEY `publicacion_id` (`publicacion_id`),
  KEY `solicitante_id` (`solicitante_id`),

  CONSTRAINT `intercambios_ibfk_1`
    FOREIGN KEY (`publicacion_id`) REFERENCES `publicaciones` (`id`)
    ON DELETE CASCADE,

  CONSTRAINT `intercambios_ibfk_2`
    FOREIGN KEY (`solicitante_id`) REFERENCES `usuarios` (`id`)
    ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;