DROP DATABASE IF EXISTS skybank;
CREATE DATABASE IF NOT EXISTS skybank;
USE skybank;

CREATE TABLE Persona (
    NIE VARCHAR(20) PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    Apellidos VARCHAR(100) NOT NULL,
    Nacionalidad VARCHAR(50),
    Fecha_nacimiento DATE,
    Telefono VARCHAR(15),
    Email VARCHAR(100),
    Direccion VARCHAR(200)
);

CREATE TABLE Cuenta (
    ID_cuenta INT PRIMARY KEY AUTO_INCREMENT,
    Fecha_creacion DATE NOT NULL,
    Tipo VARCHAR(50),
    Saldo DECIMAL(10, 2) DEFAULT 0.00,
    Estado VARCHAR(50) DEFAULT 'Activa'
);

CREATE TABLE Cliente (
    ID_cliente INT PRIMARY KEY AUTO_INCREMENT,
    NIE VARCHAR(20) NOT NULL,
    PIN INT NOT NULL,
    FOREIGN KEY (NIE) REFERENCES Persona(NIE)
);

CREATE TABLE Empleado (
    ID_empleado INT PRIMARY KEY AUTO_INCREMENT,
    NIE VARCHAR(20) NOT NULL,
    Rol VARCHAR(50) NOT NULL,
    Num_SS VARCHAR(20),
    Nivel_acceso INT,
    Fecha_contratacion DATE NOT NULL,
    FOREIGN KEY (NIE) REFERENCES Persona(NIE)
);

CREATE TABLE Tarjeta (
    Num_tarjeta VARCHAR(20) PRIMARY KEY,
    ID_cliente INT NOT NULL,
    ID_cuenta INT NOT NULL,
    Tipo VARCHAR(50),
    Estado VARCHAR(50) DEFAULT 'Activa',
    Fecha_caducidad DATE,
    Limite_operativo DECIMAL(10, 2),
    FOREIGN KEY (ID_cliente) REFERENCES Cliente(ID_cliente),
    FOREIGN KEY (ID_cuenta) REFERENCES Cuenta(ID_cuenta)
);

CREATE TABLE Transferencia (
    ID_transferencia INT PRIMARY KEY AUTO_INCREMENT,
    ID_cuenta_emisor INT NOT NULL,
    ID_cuenta_beneficiario INT NOT NULL,
    Importe DECIMAL(10, 2) NOT NULL,
    Estado VARCHAR(50),
    Fecha_realizacion DATE NOT NULL,
    Concepto VARCHAR(200),
    FOREIGN KEY (ID_cuenta_emisor) REFERENCES Cuenta(ID_cuenta),
    FOREIGN KEY (ID_cuenta_beneficiario) REFERENCES Cuenta(ID_cuenta)
);

CREATE TABLE Embargo (
    ID_embargo INT PRIMARY KEY AUTO_INCREMENT,
    ID_cuenta INT NOT NULL,
    Fecha_aplicacion DATE NOT NULL,
    Tipo VARCHAR(50),
    Emisor VARCHAR(50),
    Importe DECIMAL(10, 2),
    FOREIGN KEY (ID_cuenta) REFERENCES Cuenta(ID_cuenta)
);

CREATE TABLE Cita (
    ID_cita INT PRIMARY KEY AUTO_INCREMENT,
    ID_cliente INT NOT NULL,
    ID_empleado INT NOT NULL,
    Tipo VARCHAR(50),
    Estado VARCHAR(50),
    Fecha_hora DATETIME NOT NULL,
    FOREIGN KEY (ID_cliente) REFERENCES Cliente(ID_cliente),
    FOREIGN KEY (ID_empleado) REFERENCES Empleado(ID_empleado)
);

CREATE TABLE Notificacion (
    ID_notificacion INT PRIMARY KEY AUTO_INCREMENT,
    ID_cliente INT NOT NULL,
    Tipo VARCHAR(50), -- Ejemplo: Transferencia, Bloqueo, Aviso de comisiones
    Mensaje TEXT,
    Fecha_envio DATETIME NOT NULL,
    Estado VARCHAR(50) DEFAULT 'No Leído', -- Leído, No Leído
    FOREIGN KEY (ID_cliente) REFERENCES Cliente(ID_cliente)
);

CREATE TABLE Movimiento (
    ID_movimiento INT PRIMARY KEY AUTO_INCREMENT,
    ID_cuenta INT NOT NULL,
    Tipo VARCHAR(50), -- Ejemplo: Ingreso, Pago, Comisión
    Importe DECIMAL(10, 2),
    Fecha DATE NOT NULL,
    Concepto VARCHAR(200),
    FOREIGN KEY (ID_cuenta) REFERENCES Cuenta(ID_cuenta)
);

