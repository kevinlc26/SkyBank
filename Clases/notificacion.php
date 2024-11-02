<?php
class Notificacion {
    private $ID_notificacion;
    private $ID_cliente;
    private $Tipo;
    private $Mensaje;
    private $Fecha_envio;
    private $Estado;

	public function __construct($ID_notificacion, $ID_cliente, $Tipo, $Mensaje, $Fecha_envio, $Estado) {

		$this->ID_notificacion = $ID_notificacion;
		$this->ID_cliente = $ID_cliente;
		$this->Tipo = $Tipo;
		$this->Mensaje = $Mensaje;
		$this->Fecha_envio = $Fecha_envio;
		$this->Estado = $Estado;
	}

	public function getID_notificacion() {
		return $this->ID_notificacion;
	}

	public function setID_notificacion($value) {
		$this->ID_notificacion = $value;
	}

	public function getID_cliente() {
		return $this->ID_cliente;
	}

	public function setID_cliente($value) {
		$this->ID_cliente = $value;
	}

	public function getTipo() {
		return $this->Tipo;
	}

	public function setTipo($value) {
		$this->Tipo = $value;
	}

	public function getMensaje() {
		return $this->Mensaje;
	}

	public function setMensaje($value) {
		$this->Mensaje = $value;
	}

	public function getFecha_envio() {
		return $this->Fecha_envio;
	}

	public function setFecha_envio($value) {
		$this->Fecha_envio = $value;
	}

	public function getEstado() {
		return $this->Estado;
	}

	public function setEstado($value) {
		$this->Estado = $value;
	}
}

?>