<?php
class Transferencia {
    private $ID_transferencia;
    private $ID_cuenta_emisor;
    private $ID_cuenta_beneficiario;
    private $Importe;
    private $Estado;
    private $Fecha_realizacion;
    private $Concepto;

	public function __construct($ID_transferencia, $ID_cuenta_emisor, $ID_cuenta_beneficiario, $Importe, $Estado, $Fecha_realizacion, $Concepto) {

		$this->ID_transferencia = $ID_transferencia;
		$this->ID_cuenta_emisor = $ID_cuenta_emisor;
		$this->ID_cuenta_beneficiario = $ID_cuenta_beneficiario;
		$this->Importe = $Importe;
		$this->Estado = $Estado;
		$this->Fecha_realizacion = $Fecha_realizacion;
		$this->Concepto = $Concepto;
	}

	public function getID_transferencia() {
		return $this->ID_transferencia;
	}

	public function setID_transferencia($value) {
		$this->ID_transferencia = $value;
	}

	public function getID_cuenta_emisor() {
		return $this->ID_cuenta_emisor;
	}

	public function setID_cuenta_emisor($value) {
		$this->ID_cuenta_emisor = $value;
	}

	public function getID_cuenta_beneficiario() {
		return $this->ID_cuenta_beneficiario;
	}

	public function setID_cuenta_beneficiario($value) {
		$this->ID_cuenta_beneficiario = $value;
	}

	public function getImporte() {
		return $this->Importe;
	}

	public function setImporte($value) {
		$this->Importe = $value;
	}

	public function getEstado() {
		return $this->Estado;
	}

	public function setEstado($value) {
		$this->Estado = $value;
	}

	public function getFecha_realizacion() {
		return $this->Fecha_realizacion;
	}

	public function setFecha_realizacion($value) {
		$this->Fecha_realizacion = $value;
	}

	public function getConcepto() {
		return $this->Concepto;
	}

	public function setConcepto($value) {
		$this->Concepto = $value;
	}
}

?>