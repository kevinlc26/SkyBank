DROP DATABASE IF EXISTS skybank;
CREATE DATABASE IF NOT EXISTS skybank;
USE skybank;

CREATE TABLE Clientes (
    ID_cliente INT PRIMARY KEY AUTO_INCREMENT,
    NIE VARCHAR(20) NOT NULL UNIQUE,
    Nombre VARCHAR(100) NOT NULL,
    Apellidos VARCHAR(100) NOT NULL,
    Nacionalidad VARCHAR(50),
    Fecha_nacimiento DATE,
    Telefono VARCHAR(15),
    Email VARCHAR(100),
    Direccion VARCHAR(200),
    PIN INT NOT NULL
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
    Fecha_contratacion DATE NOT NULL
);

CREATE TABLE Cuentas (
    ID_cuenta VARCHAR(50) PRIMARY KEY,
    Fecha_creacion DATE NOT NULL,
    Tipo VARCHAR(50),
    Saldo DECIMAL(10, 2) DEFAULT 0.00,
    Estado VARCHAR(50) DEFAULT 'Activa'
);

CREATE TABLE Tarjetas (
    Num_tarjeta VARCHAR(20) PRIMARY KEY,
    ID_cuenta INT NOT NULL,
    Tipo ENUM('Débito', 'Crédito', 'Prepago') NOT NULL,
    Estado VARCHAR(50) DEFAULT 'Activa',
    Fecha_caducidad DATE,
    Limite_operativo DECIMAL(10, 2),
    FOREIGN KEY (ID_cuenta) REFERENCES Cuentas(ID_cuenta)
);

CREATE TABLE Transferencias (
    ID_transferencia INT PRIMARY KEY AUTO_INCREMENT,
    ID_cuenta_emisor INT NOT NULL,
    ID_cuenta_beneficiario INT NOT NULL,
    Importe DECIMAL(10, 2) NOT NULL,
    Estado VARCHAR(50),
    Fecha_realizacion DATE NOT NULL,
    Concepto VARCHAR(200),
    FOREIGN KEY (ID_cuenta_emisor) REFERENCES Cuentas(ID_cuenta),
    FOREIGN KEY (ID_cuenta_beneficiario) REFERENCES Cuentas(ID_cuenta)
);

CREATE TABLE Movimientos (
    ID_movimiento INT PRIMARY KEY AUTO_INCREMENT,
    Num_cuenta VARCHAR(50) NOT NULL,
    Num_tarjeta VARCHAR(20) NOT NULL,
    Tipo VARCHAR(50), -- Ejemplo: Ingreso, Pago, Comisión
    Importe DECIMAL(10, 2),
    Fecha DATE NOT NULL,
    Concepto VARCHAR(200),
    FOREIGN KEY (Num_cuenta) REFERENCES Cuentas(ID_cuenta)
    FOREIGN KEY (Num_tarjeta) REFERENCES Tarjetas(Num_tarjeta)
);

CREATE TABLE Cliente_Cuenta (
    ID_cliente INT NOT NULL,
    ID_cuenta INT NOT NULL,
    PRIMARY KEY (ID_cliente, ID_cuenta),
    FOREIGN KEY (ID_cliente) REFERENCES Clientes(ID_cliente),
    FOREIGN KEY (ID_cuenta) REFERENCES Cuentas(ID_cuenta)
);
