ALTER TABLE intercambios
ADD producto_nombre VARCHAR(100) NOT NULL,
ADD producto_descripcion TEXT NOT NULL,
ADD producto_imagen VARCHAR(255) NOT NULL,
ADD producto_vencimiento DATE NOT NULL;