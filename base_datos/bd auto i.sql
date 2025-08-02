-- CLIENTE
CREATE TABLE cliente (
   id_cliente INT(11) NOT NULL AUTO_INCREMENT,
   nombre VARCHAR(100) NOT NULL,
   direccion VARCHAR(255) DEFAULT NULL,
   telefono VARCHAR(20) DEFAULT NULL,
   email VARCHAR(100) DEFAULT NULL,
   PRIMARY KEY (id_cliente)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- CUENTA
CREATE TABLE cuenta (
   id_cuenta INT(11) NOT NULL AUTO_INCREMENT,
   numero_cuenta VARCHAR(20) NOT NULL,
   tipo ENUM('Ahorro','Corriente') NOT NULL,
   saldo DECIMAL(12,2) NOT NULL DEFAULT 0.00,
   fecha_apertura DATE NOT NULL DEFAULT CURDATE(),
   id_cliente INT(11) NOT NULL,
   id_sucursal INT(11) NOT NULL,
   PRIMARY KEY (id_cuenta),
   FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente),
   FOREIGN KEY (id_sucursal) REFERENCES sucursal(id_sucursal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- EMPLEADO
CREATE TABLE empleado (
   id_empleado INT(11) NOT NULL AUTO_INCREMENT,
   nombre VARCHAR(100) NOT NULL,
   puesto ENUM('Cajero','Asesor','Gerente','Administrador') NOT NULL,
   telefono VARCHAR(20) DEFAULT NULL,
   email VARCHAR(100) DEFAULT NULL,
   id_sucursal INT(11) NOT NULL,
   PRIMARY KEY (id_empleado),
   FOREIGN KEY (id_sucursal) REFERENCES sucursal(id_sucursal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- PERFILES
CREATE TABLE perfiles (
   CREATE TABLE perfiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_perfil VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- PRESTAMO
CREATE TABLE prestamo (
   id_prestamo INT(11) NOT NULL AUTO_INCREMENT,
   monto DECIMAL(10,2) NOT NULL,
   interes DECIMAL(5,2) NOT NULL,
   plazo INT(11) NOT NULL,
   fecha_inicio DATE NOT NULL DEFAULT CURDATE(),
   estado ENUM('Activo','Cancelado','En mora') DEFAULT 'Activo',
   id_cliente INT(11) NOT NULL,
   id_sucursal INT(11) NOT NULL,
   PRIMARY KEY (id_prestamo),
   FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente),
   FOREIGN KEY (id_sucursal) REFERENCES sucursal(id_sucursal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- SUCURSAL
CREATE TABLE sucursal (
   id_sucursal INT(11) NOT NULL AUTO_INCREMENT,
   nombre VARCHAR(100) NOT NULL,
   direccion VARCHAR(255) DEFAULT NULL,
   telefono VARCHAR(20) DEFAULT NULL,
   PRIMARY KEY (id_sucursal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- TARJETA_CREDITO
CREATE TABLE tarjeta_credito (
   id_tarjeta INT(11) NOT NULL AUTO_INCREMENT,
   numero_tarjeta VARCHAR(20) NOT NULL,
   tipo ENUM('Visa','MasterCard','AmericanExpress') NOT NULL,
   limite_credito DECIMAL(10,2) NOT NULL,
   fecha_emision DATE NOT NULL DEFAULT CURDATE(),
   id_cuenta INT(11) NOT NULL,
   PRIMARY KEY (id_tarjeta),
   FOREIGN KEY (id_cuenta) REFERENCES cuenta(id_cuenta)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- TRANSACCION
CREATE TABLE transaccion (
   id_transaccion INT(11) NOT NULL AUTO_INCREMENT,
   fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
   monto DECIMAL(10,2) NOT NULL,
   tipo ENUM('Depósito','Retiro','Transferencia') NOT NULL,
   descripcion TEXT DEFAULT NULL,
   id_cuenta INT(11) NOT NULL,
   PRIMARY KEY (id_transaccion),
   FOREIGN KEY (id_cuenta) REFERENCES cuenta(id_cuenta)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- USUARIOS
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL, -- Guardaremos la contraseña encriptada
    perfil_id INT,
    FOREIGN KEY (perfil_id) REFERENCES perfiles(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
