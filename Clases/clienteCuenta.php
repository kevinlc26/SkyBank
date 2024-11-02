<?php
class ClienteCuenta {
    private $ID_cliente;
    private $ID_cuenta;

	public function __construct($ID_cliente, $ID_cuenta) {

		$this->ID_cliente = $ID_cliente;
		$this->ID_cuenta = $ID_cuenta;
	}

	public function getID_cliente() {
		return $this->ID_cliente;
	}

	public function setID_cliente($value) {
		$this->ID_cliente = $value;
	}

	public function getID_cuenta() {
		return $this->ID_cuenta;
	}

	public function setID_cuenta($value) {
		$this->ID_cuenta = $value;
	}
}

?>