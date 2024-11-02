<?php

class ClienteContrato {
    public $ID_cliente;
    public $ID_contrato;

	public function __construct($ID_cliente, $ID_contrato) {

		$this->ID_cliente = $ID_cliente;
		$this->ID_contrato = $ID_contrato;
	}

	public function getID_cliente() {
		return $this->ID_cliente;
	}

	public function setID_cliente($value) {
		$this->ID_cliente = $value;
	}

	public function getID_contrato() {
		return $this->ID_contrato;
	}

	public function setID_contrato($value) {
		$this->ID_contrato = $value;
	}
}
?>