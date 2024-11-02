<?php

class Embargo {
    private $ID_embargo;
    private $Importe;
    private $Emisor;
    private $Fecha_aplicacion;

	public function __construct($ID_embargo, $Importe, $Emisor, $Fecha_aplicacion) {

		$this->ID_embargo = $ID_embargo;
		$this->Importe = $Importe;
		$this->Emisor = $Emisor;
		$this->Fecha_aplicacion = $Fecha_aplicacion;
	}

	public function getID_embargo() {
		return $this->ID_embargo;
	}

	public function setID_embargo($value) {
		$this->ID_embargo = $value;
	}

	public function getImporte() {
		return $this->Importe;
	}

	public function setImporte($value) {
		$this->Importe = $value;
	}

	public function getEmisor() {
		return $this->Emisor;
	}

	public function setEmisor($value) {
		$this->Emisor = $value;
	}

	public function getFecha_aplicacion() {
		return $this->Fecha_aplicacion;
	}

	public function setFecha_aplicacion($value) {
		$this->Fecha_aplicacion = $value;
	}
}

?>