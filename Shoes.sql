CREATE TABLE USUARIOS(
	CC BIGINT PRIMARY KEY,
    NOMBRE VARCHAR(50) NOT NULL,
    APELLIDO VARCHAR(50)NOT NULL,
    EMAIL VARCHAR(100)NOT NULL UNIQUE,
    PASSWRD VARCHAR(100)NOT NULL,
    ROL ENUM('ADMINISTRADOR', 'EMPLEADO') DEFAULT 'EMPLEADO',
    FOTO LONGBLOB,
    FECHA_CREACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FECHA_ACTUALIZACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP  
);


CREATE TABLE CATEGORIAS (
	ID BIGINT PRIMARY KEY AUTO_INCREMENT,
    NOMBRE VARCHAR(20) NOT NULL,
    DESCRIPCION TEXT, 
    ESTADO ENUM('ACTIVO', 'INACTIVO') DEFAULT 'ACTIVO',
    IMAGEN LONGBLOB,
    FECHA_CREACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FECHA_ACTUALIZACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP  
);

CREATE TABLE PRODUCTOS(
	ID BIGINT PRIMARY KEY AUTO_INCREMENT,
    NOMBRE VARCHAR(50) NOT NULL, 
    DESCRIPCION TEXT,
    SKU VARCHAR(50) NULL, 
    PRECIO DECIMAL(10, 2) NOT NULL,
    DESCUENTO INT NOT NULL, 
    PRECIO_FINAL DECIMAL(10, 2) GENERATED ALWAYS AS (PRECIO - (PRECIO * (DESCUENTO / 100))) STORED,
    ID_CATEGORIA BIGINT, 
    FECHA_CREACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FECHA_ACTUALIZACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_CATEGORIA) REFERENCES categorias(ID)
);

CREATE TABLE TALLAS_PRODUCTO(
	ID BIGINT PRIMARY KEY AUTO_INCREMENT,
    TALLA VARCHAR(10) NOT NULL,
    FECHA_CREACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FECHA_ACTUALIZACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE COLORES_PRODUCTO(
	ID BIGINT PRIMARY KEY AUTO_INCREMENT,
    COLOR VARCHAR(10) NOT NULL,
    FECHA_CREACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FECHA_ACTUALIZACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IMAGENES_PRODUCTO(
	ID BIGINT PRIMARY KEY AUTO_INCREMENT,
    ID_PRODUCTO BIGINT NOT NULL,
    ID_COLOR BIGINT NOT NULL,
    IMAGEN_URL VARCHAR(255) NOT NULL,
    FECHA_CREACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FECHA_ACTUALIZACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_PRODUCTO) REFERENCES PRODUCTOS(ID),
    FOREIGN KEY (ID_COLOR) REFERENCES COLORES_PRODUCTO(ID)
);

CREATE TABLE STOCK_PRODUCTO(
	ID BIGINT PRIMARY KEY AUTO_INCREMENT,
    ID_PRODUCTO BIGINT NOT NULL,
    ID_COLOR BIGINT NOT NULL,
    ID_TALLA BIGINT NOT NULL,
    CANTIDAD_INICIAL BIGINT NOT NULL, 
    CANTIDAD_ACTUAL BIGINT NOT NULL,
    FECHA_CREACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FECHA_ACTUALIZACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_PRODUCTO) REFERENCES PRODUCTOS(ID),
    FOREIGN KEY (ID_COLOR) REFERENCES COLORES_PRODUCTO(ID),
    FOREIGN KEY (ID_TALLA) REFERENCES TALLAS_PRODUCTO(ID), 
    UNIQUE(ID_PRODUCTO, ID_COLOR, ID_TALLA)
);

CREATE TABLE EMPRESA (
    ID BIGINT PRIMARY KEY AUTO_INCREMENT,
    LOGO VARCHAR(255) NULL,
    NOMBRE VARCHAR(100) NOT NULL,
    PAIS VARCHAR(50) NOT NULL,
    DEPARTAMENTO VARCHAR(50) NULL,
    CIUDAD VARCHAR(50) NOT NULL,
    TELEFONO VARCHAR(20) NULL,
    EMAIL VARCHAR(100) NULL,
    DIRECCION TEXT NULL,
    NIT VARCHAR(20) NULL,
    WHATSAPP VARCHAR(20) NULL,
    FACEBOOK VARCHAR(255) NULL,
    TIKTOK VARCHAR(255) NULL,
    INSTAGRAM VARCHAR(255) NULL,
    TARIFA DECIMAL(10, 2) NULL,
    FECHA_CREACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FECHA_ACTUALIZACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP  
);

CREATE TABLE RESENA (
    ID BIGINT PRIMARY KEY AUTO_INCREMENT,
    NOMBRE VARCHAR(100) NOT NULL,
    APELLIDO VARCHAR(100) NOT NULL,
    DESCRIPCION TEXT NOT NULL,
    PUNTUACION INT NOT NULL CHECK (PUNTUACION BETWEEN 1 AND 5),
    ESTADO ENUM('PUBLICO', 'PRIVADO') NOT NULL DEFAULT 'PRIVADO',
    FECHA_CREACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FECHA_ACTUALIZACION TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP  
);



-- EJEMPLO DE COMO INSERTAR -----------------------------------------------------------------

-- Primero, insertemos una categoría (suponiendo que la tabla `categorias` ya existe)
INSERT INTO categorias (ID, nombre) VALUES (1, 'Zapatos deportivos');

-- Insertamos un producto
INSERT INTO PRODUCTOS (NOMBRE, DESCRIPCION, SKU, PRECIO, DESCUENTO, ID_CATEGORIA) 
VALUES ('Zapatillas Ultra 2024', 'Zapatillas deportivas ligeras y cómodas', 'ZU2024', 7999.99, 10, 1);

-- Insertamos tallas disponibles
INSERT INTO TALLAS_PRODUCTO (TALLA) VALUES ('S'), ('M'), ('L'), ('XL');

-- Insertamos colores disponibles
INSERT INTO COLORES_PRODUCTO (COLOR) VALUES ('Rojo'), ('Azul'), ('Negro');

-- Insertamos imágenes del producto
INSERT INTO IMAGENES_PRODUCTO (ID_PRODUCTO, ID_COLOR, IMAGEN_URL) 
VALUES 
(1, 1, 'imagenes/zapatillas_rojo.png'),
(1, 2, 'imagenes/zapatillas_azul.png'),
(1, 3, 'imagenes/zapatillas_negro.png');

-- Insertamos stock para cada combinación de color y talla
INSERT INTO STOCK_PRODUCTO (ID_PRODUCTO, ID_COLOR, ID_TALLA, CANTIDAD_INICIAL, CANTIDAD_ACTUAL)
VALUES 
(1, 1, 1, 50, 50),  -- Zapatillas Ultra 2024, Rojo, Talla S
(1, 1, 2, 30, 30),  -- Zapatillas Ultra 2024, Rojo, Talla M
(1, 2, 1, 40, 40),  -- Zapatillas Ultra 2024, Azul, Talla S
(1, 2, 3, 20, 20),  -- Zapatillas Ultra 2024, Azul, Talla L
(1, 3, 2, 60, 60),  -- Zapatillas Ultra 2024, Negro, Talla M
(1, 3, 3, 15, 15);  -- Zapatillas Ultra 2024, Negro, Talla L


-----------------------------------------------------------------------------------------------
