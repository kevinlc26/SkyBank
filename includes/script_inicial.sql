DROP DATABASE IF EXISTS skybank;
CREATE DATABASE IF NOT EXISTS skybank;
USE skybank;

CREATE TABLE Personas (
    NIE VARCHAR(20) PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    Apellidos VARCHAR(100) NOT NULL,
    Nacionalidad VARCHAR(50),
    Fecha_nacimiento DATE,
    Telefono VARCHAR(15),
    Email VARCHAR(100),
    Direccion VARCHAR(200)
);

CREATE TABLE Cuentas (
    ID_cuenta INT PRIMARY KEY AUTO_INCREMENT,
    Fecha_creacion DATE NOT NULL,
    Tipo VARCHAR(50),
    Saldo DECIMAL(10, 2) DEFAULT 0.00,
    Estado VARCHAR(50) DEFAULT 'Activa'
);

CREATE TABLE Clientes (
    ID_cliente INT PRIMARY KEY AUTO_INCREMENT,
    NIE VARCHAR(20) NOT NULL,
    PIN INT NOT NULL,
    FOREIGN KEY (NIE) REFERENCES Personas(NIE)
);

CREATE TABLE Empleados (
    ID_empleado INT PRIMARY KEY AUTO_INCREMENT,
    NIE VARCHAR(20) NOT NULL,
    Rol ENUM('Gestor', 'Administrador') NOT NULL,
    Num_SS VARCHAR(20),
    Fecha_contratacion DATE NOT NULL,
    FOREIGN KEY (NIE) REFERENCES Personas(NIE)
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

CREATE TABLE Embargos (
    ID_embargo INT PRIMARY KEY AUTO_INCREMENT,
    ID_cuenta INT NOT NULL,
    Fecha_aplicacion DATE NOT NULL,
    Tipo VARCHAR(50),
    Emisor VARCHAR(50),
    Importe DECIMAL(10, 2),
    FOREIGN KEY (ID_cuenta) REFERENCES Cuentas(ID_cuenta)
);

CREATE TABLE Citas (
    ID_cita INT PRIMARY KEY AUTO_INCREMENT,
    ID_cliente INT NOT NULL,
    ID_empleado INT NOT NULL,
    Tipo VARCHAR(50),
    Estado VARCHAR(50),
    Fecha_hora DATETIME NOT NULL,
    FOREIGN KEY (ID_cliente) REFERENCES Clientes(ID_cliente),
    FOREIGN KEY (ID_empleado) REFERENCES Empleados(ID_empleado)
);

CREATE TABLE Notificaciones (
    ID_notificacion INT PRIMARY KEY AUTO_INCREMENT,
    ID_cliente INT NOT NULL,
    Tipo VARCHAR(50), -- Ejemplo: Transferencia, Bloqueo, Aviso de comisiones
    Mensaje TEXT,
    Fecha_envio DATETIME NOT NULL,
    Estado VARCHAR(50) DEFAULT 'No Leído', -- Leído, No Leído
    FOREIGN KEY (ID_cliente) REFERENCES Clientes(ID_cliente)
);

CREATE TABLE Movimientos (
    ID_movimiento INT PRIMARY KEY AUTO_INCREMENT,
    Num_tarjeta VARCHAR(20) NOT NULL,
    Tipo VARCHAR(50), -- Ejemplo: Ingreso, Pago, Comisión
    Importe DECIMAL(10, 2),
    Fecha DATE NOT NULL,
    Concepto VARCHAR(200),
    FOREIGN KEY (Num_tarjeta) REFERENCES Tarjetas(Num_tarjeta)
);

CREATE TABLE Cliente_Cuenta (
    ID_cliente INT NOT NULL,
    ID_cuenta INT NOT NULL,
    PRIMARY KEY (ID_cliente, ID_cuenta),
    FOREIGN KEY (ID_cliente) REFERENCES Clientes(ID_cliente),
    FOREIGN KEY (ID_cuenta) REFERENCES Cuentas(ID_cuenta)
);
