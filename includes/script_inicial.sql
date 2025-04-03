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
    NIE VARCHAR(20) NOT NULL UNIQUE,
    Nombre VARCHAR(100) NOT NULL,
    Apellidos VARCHAR(100) NOT NULL,
    Nacionalidad VARCHAR(50),
    Fecha_nacimiento DATE,
    Telefono VARCHAR(15),
    Email VARCHAR(100),
    Direccion VARCHAR(200),
    Rol ENUM('Gestor', 'Administrador') NOT NULL,
    Num_SS VARCHAR(20),
    Fecha_contratacion DATE NOT NULL,
    Estado_Empleados ENUM('Activo', 'Inactivo') DEFAULT 'Activo'
);

CREATE TABLE Cuentas (
    ID_cuenta VARCHAR(50) PRIMARY KEY,
    Fecha_creacion DATE DEFAULT CURRENT_TIMESTAMP,
    Tipo_cuenta ENUM('Online', 'Ahorro', 'corriente') ,
    Saldo DECIMAL(10, 2) DEFAULT 0.00,
    Estado_cuenta ENUM('Activa', 'Inactiva', 'Bloqueada') DEFAULT 'Activa'
);

CREATE TABLE Tarjetas (
    ID_tarjeta VARCHAR(20) PRIMARY KEY,
    ID_cuenta VARCHAR(50) NOT NULL,
    Tipo_tarjeta ENUM('Skydebit', 'Skycredit', 'Skypre') NOT NULL,
    Estado_tarjeta ENUM('Activa', 'Inactiva', 'Bloqueada') DEFAULT 'Activa',
    Fecha_caducidad DATE,
    Limite_operativo DECIMAL(10, 2),
    FOREIGN KEY (ID_cuenta) REFERENCES Cuentas(ID_cuenta)
);

CREATE TABLE Movimientos (
    ID_movimiento INT PRIMARY KEY AUTO_INCREMENT,
    ID_cuenta_emisor VARCHAR(50) NOT NULL,
    ID_cuenta_beneficiario VARCHAR(50) NOT NULL,
    ID_tarjeta VARCHAR(20) NOT NULL,
    Tipo_movimiento ENUM('Ingreso', 'Pago Tarjeta', 'Transferencia', 'Cobro', 'Comisión', 'Recibo'),
    Importe DECIMAL(10, 2),
    Estado ENUM('Activo', 'Bloqueado') DEFAULT 'Activo'
    Fecha_movimiento DATE NOT NULL,
    Concepto VARCHAR(200),
    FOREIGN KEY (ID_cuenta) REFERENCES Cuentas(ID_cuenta)
    FOREIGN KEY (ID_tarjeta) REFERENCES Tarjetas(ID_tarjeta)
);

CREATE TABLE Cliente_Cuenta (
    ID_cliente INT NOT NULL,
    ID_cuenta INT NOT NULL,
    PRIMARY KEY (ID_cliente, ID_cuenta),
    FOREIGN KEY (ID_cliente) REFERENCES Clientes(ID_cliente),
    FOREIGN KEY (ID_cuenta) REFERENCES Cuentas(ID_cuenta)
);
