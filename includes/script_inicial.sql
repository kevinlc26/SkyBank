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
    Fecha_caducidad DATE,
    Limite_operativo DECIMAL(10, 2) NOT NULL,
    Limite_Mensual DECIMAL(10, 2) NOT NULL,
    Compras_online BOOLEAN DEFAULT TRUE,
    Compras_internacional BOOLEAN DEFAULT FALSE,
    PIN VARCHAR(4),
    FOREIGN KEY (ID_cuenta) REFERENCES Cuentas(ID_cuenta)
);

CREATE TABLE Movimientos (
    ID_movimiento INT PRIMARY KEY AUTO_INCREMENT,
    ID_cuenta_emisor VARCHAR(50) NOT NULL,
    ID_cuenta_beneficiario VARCHAR(50),
    ID_tarjeta VARCHAR(20),
    Tipo_movimiento ENUM('Ingreso', 'Pago Tarjeta','Transferencia enviada', 'Transferencia recibida', 'Cobro', 'Comisión', 'Recibo', 'Traspaso'),
    Importe DECIMAL(10, 2),
    Saldo_nuevo DECIMAL(10,2),
    Estado ENUM('Activo', 'Bloqueado') DEFAULT 'Activo',
    Fecha_movimiento DATE DEFAULT CURRENT_TIMESTAMP NOT NULL ,
    Concepto VARCHAR(200) DEFAULT NULL,
    FOREIGN KEY (ID_cuenta_emisor) REFERENCES Cuentas(ID_cuenta),
    FOREIGN KEY (ID_tarjeta) REFERENCES Tarjetas(ID_tarjeta)
);

CREATE TABLE Cliente_Cuenta (
    ID_cliente INT NOT NULL,
    ID_cuenta VARCHAR(50) NOT NULL,
    PRIMARY KEY (ID_cliente, ID_cuenta),
    FOREIGN KEY (ID_cliente) REFERENCES Clientes(ID_cliente),
    FOREIGN KEY (ID_cuenta) REFERENCES Cuentas(ID_cuenta)
);

CREATE TABLE textos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  clave VARCHAR(255) NOT NULL,      
  texto_original TEXT NOT NULL,    
  texto_traducido TEXT,            
  idioma_original VARCHAR(10) NOT NULL,      
  idioma_destino VARCHAR(10)       
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

-- TRIGGER PARA PONER DE MANERA PROVISIONAL UN PASSWORD AL NUEVO EMPLEADO
DELIMITER //

CREATE TRIGGER set_pin_empleado_default
BEFORE INSERT ON Empleados
FOR EACH ROW
BEGIN
  IF NEW.PIN_empleado IS NULL OR NEW.PIN_empleado = '' THEN
    SET NEW.PIN_empleado = MD5(NEW.Num_ident);
  END IF;
END //

DELIMITER ;

-- INACTIVAR EN CASCADA CUANDO INACTVO CLIENTE

DELIMITER $$

CREATE TRIGGER trg_cliente_inactivo
AFTER UPDATE ON Clientes
FOR EACH ROW
BEGIN
  DECLARE done INT DEFAULT 0;
  DECLARE cuenta_id VARCHAR(50);

  DECLARE cur CURSOR FOR
    SELECT ID_cuenta
    FROM Cliente_Cuenta
    WHERE ID_cliente = NEW.ID_cliente
    GROUP BY ID_cuenta
    HAVING COUNT(*) = 1;

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

  IF NEW.Estado_Clientes = 'Inactivo' AND OLD.Estado_Clientes != 'Inactivo' THEN
    OPEN cur;

    read_loop: LOOP
      FETCH cur INTO cuenta_id;
      IF done THEN
        LEAVE read_loop;
      END IF;

      UPDATE Cuentas
      SET Estado_cuenta = 'Inactiva'
      WHERE ID_cuenta = cuenta_id;

      UPDATE Tarjetas
      SET Estado_tarjeta = 'Inactiva'
      WHERE ID_cuenta = cuenta_id;

    END LOOP;

    CLOSE cur;

  END IF;
END$$

DELIMITER ;



--INACTIVAR EN CASCADA CUNADO INACTIVO CUENTA

DELIMITER $$

CREATE TRIGGER trg_cuenta_inactiva
AFTER UPDATE ON Cuentas
FOR EACH ROW
BEGIN
  IF NEW.Estado_cuenta = 'Inactiva' AND OLD.Estado_cuenta != 'Inactiva' THEN
    UPDATE Tarjetas
    SET Estado_tarjeta = 'Inactiva'
    WHERE ID_cuenta = NEW.ID_cuenta;
  END IF;
END$$

DELIMITER ;

--PROCEDURE TRASPASO ENTRE CUENTAS DEL CLIENTE
DELIMITER $$

CREATE PROCEDURE TraspasarEntreCuentas(
    IN p_id_cliente INT,
    IN p_cuenta_origen VARCHAR(50),
    IN p_cuenta_destino VARCHAR(50),
    IN p_importe DECIMAL(10,2),
    IN p_concepto VARCHAR(200)
)
BEGIN
    DECLARE v_saldo_origen DECIMAL(10,2);
    DECLARE v_saldo_nuevo_origen DECIMAL(10,2);
    DECLARE v_saldo_nuevo_destino DECIMAL(10,2);

    -- Verifica que ambas cuentas pertenecen al cliente
    IF NOT EXISTS (
        SELECT 1 FROM Cliente_Cuenta
        WHERE ID_cliente = p_id_cliente AND ID_cuenta = p_cuenta_origen
    ) OR NOT EXISTS (
        SELECT 1 FROM Cliente_Cuenta
        WHERE ID_cliente = p_id_cliente AND ID_cuenta = p_cuenta_destino
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Una o ambas cuentas no pertenecen al cliente.';
    END IF;

    -- Verifica saldo suficiente
    SELECT Saldo INTO v_saldo_origen FROM Cuentas WHERE ID_cuenta = p_cuenta_origen;

    IF v_saldo_origen < p_importe THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Saldo insuficiente.';
    END IF;

    -- Actualiza saldos
    SET v_saldo_nuevo_origen = v_saldo_origen - p_importe;
    UPDATE Cuentas SET Saldo = v_saldo_nuevo_origen WHERE ID_cuenta = p_cuenta_origen;

    UPDATE Cuentas SET Saldo = Saldo + p_importe WHERE ID_cuenta = p_cuenta_destino;

    -- Registra el movimiento
    INSERT INTO Movimientos (
        ID_cuenta_emisor, ID_cuenta_beneficiario, Tipo_movimiento, Importe, Saldo_nuevo, Concepto
    )
    VALUES (
        p_cuenta_origen, p_cuenta_destino, 'Traspaso', p_importe, v_saldo_nuevo_origen, p_concepto
    );
END$$

DELIMITER ;


--INSERTS DUMMY

-- Insertar clientes
INSERT INTO Clientes (Num_ident, Nombre, Apellidos, Nacionalidad, Fecha_nacimiento, Telefono, Email, Direccion, PIN)
VALUES 
('12345678A', 'Juan', 'Pérez Gómez', 'Española', '1985-06-15', '600123456', 'juan.perez@email.com', 'Calle Falsa 123, Madrid', '202cb962ac59075b964b07152d234b70'),
('87654321B', 'María', 'López Sánchez', 'Española', '1992-09-21', '601987654', 'maria.lopez@email.com', 'Avenida Siempre Viva 456, Barcelona', '202cb962ac59075b964b07152d234b70');

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
INSERT INTO Empleados (Num_ident, Nombre, Apellidos, Nacionalidad, Fecha_nacimiento, Telefono, Email, Direccion, Rol, Num_SS, Fecha_contratacion, PIN_empleado)
VALUES 
('99999999C', 'Carlos', 'Fernández Ruiz', 'Española', '1980-02-12', '610000000', 'carlos.fernandez@skybank.com', 'Calle del Trabajo 789, Madrid', 'Administrador', 'SS123456', '2020-01-15', '202cb962ac59075b964b07152d234b70'),
('88888888D', 'Laura', 'García Torres', 'Española', '1995-07-30', '611111111', 'laura.garcia@skybank.com', 'Plaza Central 34, Sevilla', 'Gestor', 'SS654321', '2021-05-20', '202cb962ac59075b964b07152d234b70'),
('77777777E', 'Miguel', 'Sánchez López', 'Española', '1990-11-25', '612222222', 'miguel.sanchez@skybank.com', 'Avenida del Sol 56, Valencia', 'Gestor', 'SS987654', '2022-09-10', '202cb962ac59075b964b07152d234b70');
