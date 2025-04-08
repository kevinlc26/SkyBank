DROP DATABASE IF EXISTS skybank;
CREATE DATABASE IF NOT EXISTS skybank;
USE skybank;

CREATE TABLE Clientes (
    ID_cliente INT PRIMARY KEY AUTO_INCREMENT,
    Num_ident VARCHAR(20) NOT NULL UNIQUE,
    Nombre VARCHAR(100) NOT NULL,
    Apellidos VARCHAR(100) NOT NULL,
    Nacionalidad VARCHAR(50),
    Fecha_nacimiento DATE,
    Telefono VARCHAR(15),
    Email VARCHAR(100),
    Direccion VARCHAR(200),
    Estado_Clientes ENUM('Activo', 'Inactivo') DEFAULT 'Activo',
    PIN VARCHAR(255) NOT NULL
);

CREATE TABLE Empleados (
    ID_empleado INT PRIMARY KEY AUTO_INCREMENT,
    Num_ident VARCHAR(20) NOT NULL UNIQUE,
    Nombre VARCHAR(100) NOT NULL,
    Apellidos VARCHAR(100) NOT NULL,
    Nacionalidad VARCHAR(50) NOT NULL,
    Fecha_nacimiento DATE NOT NULL,
    Telefono VARCHAR(15) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    Direccion VARCHAR(200) NOT NULL,
    Rol ENUM('Gestor', 'Administrador') NOT NULL,
    Num_SS VARCHAR(20) NOT NULL,
    Fecha_contratacion DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PIN_empleado VARCHAR (50) NOT NULL,
    Foto_empleado VARCHAR(5) DEFAULT '1.png' NOT NULL,
    Estado_empleado ENUM('Activo', 'Inactivo') NOT NULL DEFAULT 'Activo'
);

CREATE TABLE Cuentas (
    ID_cuenta VARCHAR(50) PRIMARY KEY,
    Fecha_creacion DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Tipo_cuenta ENUM('Online', 'Ahorro', 'Corriente') NOT NULL DEFAULT 'Corriente',
    Saldo DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    Estado_cuenta ENUM('Activa', 'Inactiva', 'Bloqueada') NOT NULL DEFAULT 'Activa'
);

CREATE TABLE Tarjetas (
    ID_tarjeta VARCHAR(20) PRIMARY KEY,
    ID_cuenta VARCHAR(50) NOT NULL,
    Tipo_tarjeta ENUM('Skydebit', 'Skycredit', 'Skypre') NOT NULL,
    Estado_tarjeta ENUM('Activa', 'Inactiva', 'Bloqueada') DEFAULT 'Activa' NOT NULL,
    Fecha_caducidad DATE NOT NULL,
    Limite_operativo DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (ID_cuenta) REFERENCES Cuentas(ID_cuenta)
);

CREATE TABLE Movimientos (
    ID_movimiento INT PRIMARY KEY AUTO_INCREMENT,
    ID_cuenta_emisor VARCHAR(50) NOT NULL,
    ID_cuenta_beneficiario VARCHAR(50) NOT NULL,
    ID_tarjeta VARCHAR(20) NOT NULL,
    Tipo_movimiento ENUM('Ingreso', 'Pago Tarjeta', 'Transferencia', 'Cobro', 'Comisión', 'Recibo', 'Traspaso'),
    Importe DECIMAL(10, 2),
    Saldo_nuevo DECIMAL(10,2),
    Estado ENUM('Activo', 'Bloqueado') DEFAULT 'Activo'
    Fecha_movimiento DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Concepto VARCHAR(200) DEFAULT NULL,
    FOREIGN KEY (ID_cuenta) REFERENCES Cuentas(ID_cuenta)
    FOREIGN KEY (ID_tarjeta) REFERENCES Tarjetas(ID_tarjeta)
);

CREATE TABLE Cliente_Cuenta (
    ID_cliente INT NOT NULL,
    ID_cuenta VARCHAR(50) NOT NULL,
    PRIMARY KEY (ID_cliente, ID_cuenta),
    FOREIGN KEY (ID_cliente) REFERENCES Clientes(ID_cliente),
    FOREIGN KEY (ID_cuenta) REFERENCES Cuentas(ID_cuenta)
);


-- TRIGGER PARA PONER LA FECHA DE CADUCIDAD AUTOMATICA EN 5 AÑOS DE HOY
DELIMITER $$

CREATE TRIGGER set_fecha_caducidad
BEFORE INSERT ON Tarjetas
FOR EACH ROW
BEGIN
    IF NEW.Fecha_caducidad IS NULL THEN
        SET NEW.Fecha_caducidad = CURDATE() + INTERVAL 5 YEAR;
    END IF;
END $$

DELIMITER ;

--INSERTS DUMMY

-- Insertar clientes
INSERT INTO Clientes (Num_ident, Nombre, Apellidos, Nacionalidad, Fecha_nacimiento, Telefono, Email, Direccion, PIN)
VALUES 
('12345678A', 'Juan', 'Pérez Gómez', 'Española', '1985-06-15', '600123456', 'juan.perez@email.com', 'Calle Falsa 123, Madrid', '1234'),
('87654321B', 'María', 'López Sánchez', 'Española', '1992-09-21', '601987654', 'maria.lopez@email.com', 'Avenida Siempre Viva 456, Barcelona', '5678');

-- Insertar cuentas
INSERT INTO Cuentas (ID_cuenta, Tipo_cuenta, Saldo)
VALUES 
('ES11 1234 5678 9101 1121', 'Ahorro', 1500.00),
('ES11 2234 5678 9101 1122', 'Corriente', 3200.50),
('ES22 3234 5678 9101 1123', 'Online', 500.75);

-- Relacionar cuentas con clientes
INSERT INTO Cliente_Cuenta (ID_cliente, ID_cuenta)
VALUES 
(1, 'ES11 1234 5678 9101 1121'),
(1, 'ES11 2234 5678 9101 1122'),
(2, 'ES22 3234 5678 9101 1123');

-- Insertar tarjetas
INSERT INTO Tarjetas (ID_tarjeta, ID_cuenta, Tipo_tarjeta, Limite_operativo)
VALUES 
('1111 2222 3333 4444', 'ES11 1234 5678 9101 1121', 'Skydebit', 1000.00),
('5555 6666 7777 8888', 'ES22 3234 5678 9101 1123', 'Skycredit', 2000.00);

-- Insertar movimientos
INSERT INTO Movimientos (ID_cuenta_emisor, ID_cuenta_beneficiario, ID_tarjeta, Tipo_movimiento, Importe, Fecha_movimiento, Concepto)
VALUES 
('ES11 1234 5678 9101 1121', NULL, '1111 2222 3333 4444', 'Pago Tarjeta', 120.75, '2024-04-01', 'Compra en supermercado'),
('ES22 3234 5678 9101 1123', NULL, '5555 6666 7777 8888', 'Pago Tarjeta', 75.30, '2024-04-02', 'Compra en gasolinera'),
('ES11 1234 5678 9101 1121', 'ES33 4234 5678 9101 1124', NULL, 'Transferencia', 500.00, '2024-04-03', 'Pago alquiler'),
('ES11 2234 5678 9101 1122', 'ES44 5234 5678 9101 1125', NULL, 'Transferencia', 200.00, '2024-04-03', 'Pago servicios'),
('ES22 3234 5678 9101 1123', 'ES55 6234 5678 9101 1126', NULL, 'Ingreso', 1000.00, '2024-04-04', 'Depósito de efectivo');

-- Insertar empleados
INSERT INTO Empleados (Num_ident, Nombre, Apellidos, Nacionalidad, Fecha_nacimiento, Telefono, Email, Direccion, Rol, Num_SS, Fecha_contratacion)
VALUES 
('99999999C', 'Carlos', 'Fernández Ruiz', 'Española', '1980-02-12', '610000000', 'carlos.fernandez@skybank.com', 'Calle del Trabajo 789, Madrid', 'Administrador', 'SS123456', '2020-01-15'),
('88888888D', 'Laura', 'García Torres', 'Española', '1995-07-30', '611111111', 'laura.garcia@skybank.com', 'Plaza Central 34, Sevilla', 'Gestor', 'SS654321', '2021-05-20'),
('77777777E', 'Miguel', 'Sánchez López', 'Española', '1990-11-25', '612222222', 'miguel.sanchez@skybank.com', 'Avenida del Sol 56, Valencia', 'Gestor', 'SS987654', '2022-09-10');
