<?php

class Tarjeta {
    private $Num_tarjeta;
    private $ID_cliente;
    private $ID_cuenta;
    private $Tipo;
    private $Estado;
    private $Fecha_caducidad;
    private $Limite_operativo;

	public function __construct($Num_tarjeta, $ID_cliente, $ID_cuenta, $Tipo, $Estado, $Fecha_caducidad, $Limite_operativo) {

		$this->Num_tarjeta = $Num_tarjeta;
		$this->ID_cliente = $ID_cliente;
		$this->ID_cuenta = $ID_cuenta;
		$this->Tipo = $Tipo;
		$this->Estado = $Estado;
		$this->Fecha_caducidad = $Fecha_caducidad;
		$this->Limite_operativo = $Limite_operativo;
	}

	public function getNum_tarjeta() {
		return $this->Num_tarjeta;
	}

	public function setNum_tarjeta($value) {
		$this->Num_tarjeta = $value;
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

	public function getTipo() {
		return $this->Tipo;
	}

	public function setTipo($value) {
		$this->Tipo = $value;
	}

	public function getEstado() {
		return $this->Estado;
	}

	public function setEstado($value) {
		$this->Estado = $value;
	}

	public function getFecha_caducidad() {
		return $this->Fecha_caducidad;
	}

	public function setFecha_caducidad($value) {
		$this->Fecha_caducidad = $value;
	}

	public function getLimite_operativo() {
		return $this->Limite_operativo;
	}

	public function setLimite_operativo($value) {
		$this->Limite_operativo = $value;
	}
}

?>