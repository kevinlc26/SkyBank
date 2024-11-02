<?php
class Movimiento {
    private $ID_movimiento;
    private $ID_cuenta;
    private $Tipo;
    private $Importe;
    private $Fecha_movimiento;
    private $Concepto;

	public function __construct($ID_movimiento, $ID_cuenta, $Tipo, $Importe, $Fecha_movimiento, $Concepto) {

		$this->ID_movimiento = $ID_movimiento;
		$this->ID_cuenta = $ID_cuenta;
		$this->Tipo = $Tipo;
		$this->Importe = $Importe;
		$this->Fecha_movimiento = $Fecha_movimiento;
		$this->Concepto = $Concepto;
	}

	public function getID_movimiento() {
		return $this->ID_movimiento;
	}

	public function setID_movimiento($value) {
		$this->ID_movimiento = $value;
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

	public function getImporte() {
		return $this->Importe;
	}

	public function setImporte($value) {
		$this->Importe = $value;
	}

	public function getFecha_movimiento() {
		return $this->Fecha_movimiento;
	}

	public function setFecha_movimiento($value) {
		$this->Fecha_movimiento = $value;
	}

	public function getConcepto() {
		return $this->Concepto;
	}

	public function setConcepto($value) {
		$this->Concepto = $value;
	}
}

?>