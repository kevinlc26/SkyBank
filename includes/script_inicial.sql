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
    Limite_operativo DECIMAL(10, 2) DEFAULT 600.00 NOT NULL,
    Limite_Mensual DECIMAL(10, 2) DEFAULT 1200.00 NOT NULL,
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

DELIMITER $$
CREATE PROCEDURE `TransferenciaACliente`(
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

    -- Validación de propiedad
    IF NOT EXISTS (
        SELECT 1 FROM Cliente_Cuenta
        WHERE ID_cliente = p_id_cliente AND ID_cuenta = p_cuenta_origen
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La cuenta de origen no pertenece al cliente.';
    END IF;

    -- Validación: no permitir misma cuenta
    IF p_cuenta_origen = p_cuenta_destino THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No se puede transferir a la misma cuenta.';
    END IF;

    -- Validar saldo suficiente
    SELECT Saldo INTO v_saldo_origen FROM Cuentas WHERE ID_cuenta = p_cuenta_origen;
    IF v_saldo_origen < p_importe THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Saldo insuficiente.';
    END IF;

    -- Actualizar saldos
    SET v_saldo_nuevo_origen = v_saldo_origen - p_importe;
    UPDATE Cuentas SET Saldo = v_saldo_nuevo_origen WHERE ID_cuenta = p_cuenta_origen;
    UPDATE Cuentas SET Saldo = Saldo + p_importe WHERE ID_cuenta = p_cuenta_destino;

    -- Registro: Transferencia enviada
    INSERT INTO Movimientos (
        ID_cuenta_emisor, ID_cuenta_beneficiario, Tipo_movimiento, Importe, Saldo_nuevo, Concepto
    )
    VALUES (
        p_cuenta_origen, p_cuenta_destino, 'Transferencia enviada', p_importe, v_saldo_nuevo_origen, p_concepto
    );

    -- Obtener saldo receptor actualizado
    SELECT Saldo INTO v_saldo_nuevo_destino FROM Cuentas WHERE ID_cuenta = p_cuenta_destino;

    -- Registro: Transferencia recibida
    INSERT INTO Movimientos (
        ID_cuenta_emisor, ID_cuenta_beneficiario, Tipo_movimiento, Importe, Saldo_nuevo, Concepto
    )
    VALUES (
        p_cuenta_destino, p_cuenta_origen, 'Transferencia recibida', p_importe, v_saldo_nuevo_destino, p_concepto
    );
END$$
DELIMITER ;

--INSERTS DUMMY

-- INSERTAR DATOS EN CLIENTES
INSERT INTO Clientes (Num_ident, Nombre, Apellidos, Nacionalidad, Fecha_nacimiento, Telefono, Email, Direccion, Estado_Clientes, PIN) VALUES
('12345678A', 'Juan', 'Pérez Gómez', 'Española', '1985-06-15', '600123456', 'juan.perez@email.com', 'Calle Mayor 23, Madrid', 'Activo', MD5('1234')),
('87654321B', 'María', 'López Sánchez', 'Española', '1992-09-21', '601987654', 'maria.lopez@email.com', 'Avenida Diagonal 456, Barcelona', 'Activo', MD5('2345')),
('X8765432C', 'Hans', 'Schmidt', 'Alemana', '1976-03-12', '611223344', 'hans.schmidt@email.com', 'Paseo del Prado 78, Madrid', 'Activo', MD5('3456')),
('Y1234567D', 'Sophie', 'Martin', 'Francesa', '1989-11-30', '622334455', 'sophie.martin@email.com', 'Plaza Cataluña 9, Barcelona', 'Activo', MD5('4567')),
('11223344E', 'Antonio', 'García Ruiz', 'Española', '1965-07-22', '633445566', 'antonio.garcia@email.com', 'Calle Serrano 112, Madrid', 'Activo', MD5('5678')),
('22334455F', 'Laura', 'Martínez Díaz', 'Española', '1995-02-18', '644556677', 'laura.martinez@email.com', 'Rambla Catalunya 56, Barcelona', 'Activo', MD5('6789')),
('33445566G', 'Carlos', 'Fernández Sanz', 'Española', '1982-12-03', '655667788', 'carlos.fernandez@email.com', 'Calle Goya 34, Madrid', 'Inactivo', MD5('7890')),
('Z9876543H', 'Giulia', 'Rossi', 'Italiana', '1990-04-25', '666778899', 'giulia.rossi@email.com', 'Calle Gran Vía 78, Madrid', 'Activo', MD5('8901')),
('44556677I', 'Manuel', 'Torres Vega', 'Española', '1978-09-14', '677889900', 'manuel.torres@email.com', 'Avenida Meridiana 234, Barcelona', 'Activo', MD5('9012')),
('55667788J', 'Cristina', 'Navarro Gil', 'Española', '1993-05-29', '688990011', 'cristina.navarro@email.com', 'Calle Alcalá 189, Madrid', 'Activo', MD5('0123'));

-- INSERTAR DATOS EN EMPLEADOS
INSERT INTO Empleados (Num_ident, Nombre, Apellidos, Nacionalidad, Fecha_nacimiento, Telefono, Email, Direccion, Rol, Num_SS, Fecha_contratacion, PIN_empleado, Foto_empleado) VALUES
('99999999C', 'Carlos', 'Fernández Ruiz', 'Española', '1980-02-12', '610000000', 'carlos.fernandez@skybank.com', 'Calle del Trabajo 789, Madrid', 'Administrador', 'SS123456789', '2020-01-15', MD5('admin123'), '1.png'),
('88888888D', 'Laura', 'García Torres', 'Española', '1988-07-30', '611111111', 'laura.garcia@skybank.com', 'Plaza Central 34, Sevilla', 'Gestor', 'SS234567890', '2021-05-20', MD5('gestor123'), '2.png'),
('77777777E', 'Miguel', 'Sánchez López', 'Española', '1990-11-25', '612222222', 'miguel.sanchez@skybank.com', 'Avenida del Sol 56, Valencia', 'Gestor', 'SS345678901', '2022-09-10', MD5('gestor456'), '3.png'),
('66666666F', 'Elena', 'Martín González', 'Española', '1985-04-18', '613333333', 'elena.martin@skybank.com', 'Calle Luna 12, Barcelona', 'Administrador', 'SS456789012', '2019-11-05', MD5('admin456'), '4.png'),
('55555555G', 'Javier', 'Rodríguez Pérez', 'Española', '1992-08-07', '614444444', 'javier.rodriguez@skybank.com', 'Paseo Marítimo 45, Málaga', 'Gestor', 'SS567890123', '2023-02-15', MD5('gestor789'), '5.png');

-- INSERTAR DATOS EN CUENTAS
INSERT INTO Cuentas (ID_cuenta, Fecha_creacion, Tipo_cuenta, Saldo, Estado_cuenta) VALUES
('ES1112345678910111211', '2022-01-10', 'Ahorro', 15000.50, 'Activa'),
('ES1122345678910111222', '2022-01-15', 'Corriente', 3200.75, 'Activa'),
('ES2232345678910111233', '2022-02-05', 'Online', 8500.25, 'Activa'),
('ES3342345678910111244', '2022-03-20', 'Corriente', 1200.00, 'Activa'),
('ES4452345678910111255', '2022-04-12', 'Ahorro', 22000.00, 'Activa'),
('ES5562345678910111266', '2022-05-30', 'Online', 3600.50, 'Activa'),
('ES6672345678910111277', '2022-06-18', 'Corriente', 950.25, 'Inactiva'),
('ES7782345678910111288', '2022-07-05', 'Ahorro', 18500.75, 'Activa'),
('ES8892345678910111299', '2022-08-22', 'Online', 4750.00, 'Activa'),
('ES9902345678910111300', '2022-09-14', 'Corriente', 6300.25, 'Bloqueada'),
('ES1013345678910111311', '2022-10-03', 'Ahorro', 9800.50, 'Activa'),
('ES1123456789101113222', '2022-11-19', 'Online', 2100.75, 'Activa'),
('ES1233567891011133333', '2022-12-07', 'Corriente', 7400.00, 'Activa');

-- INSERTAR DATOS EN CLIENTE_CUENTA (RELACIÓN ENTRE CLIENTES Y CUENTAS)
INSERT INTO Cliente_Cuenta (ID_cliente, ID_cuenta) VALUES
(1, 'ES1112345678910111211'),
(1, 'ES1122345678910111222'),
(2, 'ES2232345678910111233'),
(2, 'ES3342345678910111244'),
(3, 'ES4452345678910111255'),
(4, 'ES5562345678910111266'),
(5, 'ES6672345678910111277'),
(5, 'ES7782345678910111288'),
(6, 'ES8892345678910111299'),
(7, 'ES9902345678910111300'),
(8, 'ES1013345678910111311'),
(9, 'ES1123456789101113222'),
(10, 'ES1233567891011133333'),
(3, 'ES3342345678910111244'); 

-- INSERTAR DATOS EN TARJETAS
INSERT INTO Tarjetas (ID_tarjeta, ID_cuenta, Tipo_tarjeta, Estado_tarjeta, Limite_operativo, Limite_Mensual, Compras_online, Compras_internacional, PIN) VALUES
('1111222233334444', 'ES1112345678910111211', 'Skydebit', 'Activa', 1000.00, 5000.00, TRUE, FALSE, '1234'),
('2222333344445555', 'ES1122345678910111222', 'Skycredit', 'Activa', 2000.00, 8000.00, TRUE, TRUE, '2345'),
('3333444455556666', 'ES2232345678910111233', 'Skydebit', 'Activa', 800.00, 4000.00, TRUE, FALSE, '3456'),
('4444555566667777', 'ES3342345678910111244', 'Skypre', 'Activa', 500.00, 2500.00, FALSE, FALSE, '4567'),
('5555666677778888', 'ES4452345678910111255', 'Skycredit', 'Activa', 3000.00, 10000.00, TRUE, TRUE, '5678'),
('6666777788889999', 'ES5562345678910111266', 'Skydebit', 'Activa', 1200.00, 6000.00, TRUE, FALSE, '6789'),
('7777888899990000', 'ES6672345678910111277', 'Skydebit', 'Inactiva', 800.00, 4000.00, FALSE, FALSE, '7890'),
('8888999900001111', 'ES7782345678910111288', 'Skycredit', 'Activa', 2500.00, 9000.00, TRUE, TRUE, '8901'),
('9999000011112222', 'ES8892345678910111299', 'Skypre', 'Activa', 1500.00, 7500.00, TRUE, FALSE, '9012'),
('0000111122223333', 'ES9902345678910111300', 'Skydebit', 'Bloqueada', 1000.00, 5000.00, FALSE, FALSE, '0123'),
('1111222233334445', 'ES1013345678910111311', 'Skycredit', 'Activa', 2200.00, 8500.00, TRUE, TRUE, '1234'),
('2222333344445556', 'ES1123456789101113222', 'Skydebit', 'Activa', 900.00, 4500.00, TRUE, FALSE, '2345'),
('3333444455556667', 'ES1233567891011133333', 'Skypre', 'Activa', 1800.00, 7000.00, TRUE, TRUE, '3456');

-- INSERTAR DATOS EN MOVIMIENTOS
INSERT INTO Movimientos (ID_cuenta_emisor, ID_cuenta_beneficiario, ID_tarjeta, Tipo_movimiento, Importe, Saldo_nuevo, Estado, Fecha_movimiento, Concepto) VALUES
-- Movimientos para Juan (cliente 1)
('ES1112345678910111211', NULL, '1111222233334444', 'Pago Tarjeta', 120.50, 14879.50, 'Activo', '2024-01-05', 'Compra en supermercado Mercadona'),
('ES1112345678910111211', 'ES2232345678910111233', NULL, 'Transferencia enviada', 500.00, 14379.50, 'Activo', '2024-01-10', 'Transferencia a María para gastos compartidos'),
('ES1122345678910111222', NULL, '2222333344445555', 'Pago Tarjeta', 349.99, 2850.76, 'Activo', '2024-01-15', 'Compra de electrónica en MediaMarkt'),
('ES1122345678910111222', 'ES4452345678910111255', NULL, 'Transferencia enviada', 200.00, 2650.76, 'Activo', '2024-01-20', 'Transferencia a Hans - entrada concierto'),

-- Movimientos para María (cliente 2)
('ES2232345678910111233', NULL, NULL, 'Ingreso', 1000.00, 9000.25, 'Activo', '2024-01-12', 'Ingreso en efectivo'),
('ES2232345678910111233', 'ES1112345678910111211', NULL, 'Transferencia enviada', 300.00, 8700.25, 'Activo', '2024-01-18', 'Devolución préstamo a Juan'),
('ES3342345678910111244', NULL, '4444555566667777', 'Pago Tarjeta', 85.75, 1114.25, 'Activo', '2024-01-22', 'Compra en farmacia'),

-- Movimientos para Hans (cliente 3)
('ES4452345678910111255', NULL, '5555666677778888', 'Pago Tarjeta', 1250.00, 20750.00, 'Activo', '2024-01-25', 'Reserva hotel vacaciones'),
('ES4452345678910111255', 'ES5562345678910111266', NULL, 'Transferencia enviada', 450.00, 20300.00, 'Activo', '2024-01-28', 'Pago a Sophie por servicios de traducción'),

-- Movimientos para Sophie (cliente 4)
('ES5562345678910111266', NULL, NULL, 'Ingreso', 2000.00, 5150.50, 'Activo', '2024-02-01', 'Ingreso nómina'),
('ES5562345678910111266', NULL, '6666777788889999', 'Pago Tarjeta', 320.45, 4830.05, 'Activo', '2024-02-05', 'Compra ropa online'),

-- Movimientos para Antonio (cliente 5)
('ES7782345678910111288', NULL, '8888999900001111', 'Pago Tarjeta', 560.25, 17940.50, 'Activo', '2024-02-10', 'Reparación vehículo'),

-- Movimientos para Laura (cliente 6)
('ES8892345678910111299', NULL, '9999000011112222', 'Pago Tarjeta', 125.80, 4624.20, 'Activo', '2024-02-15', 'Suscripciones mensuales'),
('ES8892345678910111299', 'ES1013345678910111311', NULL, 'Transferencia enviada', 350.00, 4274.20, 'Activo', '2024-02-18', 'Transferencia a Giulia - alquiler compartido'),

-- Movimientos para Giulia (cliente 8)
('ES1013345678910111311', NULL, '1111222233334445', 'Pago Tarjeta', 890.75, 8909.75, 'Activo', '2024-02-22', 'Compra de muebles IKEA'),
('ES1013345678910111311', NULL, NULL, 'Ingreso', 1200.00, 10109.75, 'Activo', '2024-02-25', 'Ingreso transferencia internacional'),

-- Movimientos para Manuel (cliente 9)
('ES1123456789101113222', NULL, '2222333344445556', 'Pago Tarjeta', 78.50, 2022.25, 'Activo', '2024-03-01', 'Compra en restaurante'),
('ES1123456789101113222', 'ES1233567891011133333', NULL, 'Transferencia enviada', 420.00, 1602.25, 'Activo', '2024-03-05', 'Transferencia a Cristina - pago factura compartida'),

-- Movimientos para Cristina (cliente 10)
('ES1233567891011133333', NULL, '3333444455556667', 'Pago Tarjeta', 1100.00, 6300.00, 'Activo', '2024-03-10', 'Compra de billete avión'),
('ES1233567891011133333', NULL, NULL, 'Ingreso', 2500.00, 8800.00, 'Activo', '2024-03-15', 'Ingreso nómina'),

-- Algunos recibos y comisiones
('ES1112345678910111211', NULL, NULL, 'Recibo', 80.00, 14299.50, 'Activo', '2024-03-18', 'Recibo electricidad'),
('ES2232345678910111233', NULL, NULL, 'Recibo', 65.50, 8634.75, 'Activo', '2024-03-20', 'Recibo teléfono'),
('ES4452345678910111255', NULL, NULL, 'Recibo', 120.00, 20180.00, 'Activo', '2024-03-22', 'Recibo hipoteca'),
('ES1122345678910111222', NULL, NULL, 'Comisión', 15.00, 2635.76, 'Activo', '2024-03-25', 'Comisión mantenimiento cuenta'),
('ES8892345678910111299', NULL, NULL, 'Comisión', 12.50, 4261.70, 'Activo', '2024-03-28', 'Comisión tarjeta anual'),

-- Traspasos entre cuentas del mismo cliente
('ES1112345678910111211', 'ES1122345678910111222', NULL, 'Traspaso', 1000.00, 13299.50, 'Activo', '2024-04-01', 'Traspaso entre mis cuentas'),
('ES7782345678910111288', 'ES6672345678910111277', NULL, 'Traspaso', 500.00, 17440.50, 'Activo', '2024-04-05', 'Traspaso para gastos'),

-- Movimientos más recientes
('ES2232345678910111233', NULL, '3333444455556666', 'Pago Tarjeta', 45.99, 8588.76, 'Activo', '2024-04-10', 'Compra en tienda online'),
('ES3342345678910111244', 'ES5562345678910111266', NULL, 'Transferencia enviada', 150.00, 964.25, 'Activo', '2024-04-15', 'Transferencia para regalo compartido'),
('ES5562345678910111266', NULL, NULL, 'Ingreso', 1500.00, 6180.05, 'Activo', '2024-04-20', 'Ingreso por trabajos freelance');