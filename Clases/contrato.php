<?php
class Contrato{
    private $ID_contrato;
    private $ID_cuenta;
    private $Fecha_emision;
    private $Estado;
    private $Texto;

	public function __construct($ID_contrato, $ID_cuenta, $Fecha_emision, $Estado, $Texto) {

		$this->ID_contrato = $ID_contrato;
		$this->ID_cuenta = $ID_cuenta;
		$this->Fecha_emision = $Fecha_emision;
		$this->Estado = $Estado;
		$this->Texto = $Texto;
	}

	public function getID_contrato() {
		return $this->ID_contrato;
	}

	public function setID_contrato($value) {
		$this->ID_contrato = $value;
	}

	public function getID_cuenta() {
		return $this->ID_cuenta;
	}

	public function setID_cuenta($value) {
		$this->ID_cuenta = $value;
	}

	public function getFecha_emision() {
		return $this->Fecha_emision;
	}

	public function setFecha_emision($value) {
		$this->Fecha_emision = $value;
	}

	public function getEstado() {
		return $this->Estado;
	}

	public function setEstado($value) {
		$this->Estado = $value;
	}

	public function getTexto() {
		return $this->Texto;
	}

	public function setTexto($value) {
		$this->Texto = $value;
	}
}

?>