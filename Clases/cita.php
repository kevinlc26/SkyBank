<?php
class Cita {
    private $ID_cita;
    private $ID_cliente;
    private $ID_Empleado;
    private $Tipo;
    private $Estado;
    private $Fecha_hora;

	public function __construct($ID_cita, $ID_cliente, $ID_Empleado, $Tipo, $Estado, $Fecha_hora) {

		$this->ID_cita = $ID_cita;
		$this->ID_cliente = $ID_cliente;
		$this->ID_Empleado = $ID_Empleado;
		$this->Tipo = $Tipo;
		$this->Estado = $Estado;
		$this->Fecha_hora = $Fecha_hora;
	}

	public function getID_cita() {
		return $this->ID_cita;
	}

	public function setID_cita($value) {
		$this->ID_cita = $value;
	}

	public function getID_cliente() {
		return $this->ID_cliente;
	}

	public function setID_cliente($value) {
		$this->ID_cliente = $value;
	}

	public function getID_Empleado() {
		return $this->ID_Empleado;
	}

	public function setID_Empleado($value) {
		$this->ID_Empleado = $value;
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

	public function getFecha_hora() {
		return $this->Fecha_hora;
	}

	public function setFecha_hora($value) {
		$this->Fecha_hora = $value;
	}
}

?>