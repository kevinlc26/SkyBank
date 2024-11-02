<?php

class Empleado extends Persona {

    private $ID_empleado;
    private $NIE;
    private $Rol;
    private $Num_SS;
    private $Fecha_contratacion;

    public function __construct( $ID_empleado,  $NIE,  $Rol,  $Num_SS,  $Fecha_contratacion, $Nombre, $Apellidos, $Nacionalidad, $FechaNacimiento, $Telefono, $Email, $Direccion){
        parent::__construct($NIE, $Nombre, $Apellidos, $Nacionalidad, $FechaNacimiento, $Telefono, $Email, $Direccion);
        $this->ID_empleado = $ID_empleado;
        $this->NIE = $NIE;
        $this->Rol = $Rol;
        $this->Num_SS = $Num_SS;
        $this->Fecha_contratacion = $Fecha_contratacion;

    }
    public function setIDEmpleado( $ID_empleado): void {$this->ID_empleado = $ID_empleado;}

	public function setNIE( $NIE): void {$this->NIE = $NIE;}

	public function setRol( $Rol): void {$this->Rol = $Rol;}

	public function setNumSS( $Num_SS): void {$this->Num_SS = $Num_SS;}

	public function setFechaContratacion( $Fecha_contratacion): void {$this->Fecha_contratacion = $Fecha_contratacion;}

	public function getIDEmpleado() {return $this->ID_empleado;}

	public function getNIE() {return $this->NIE;}

	public function getRol() {return $this->Rol;}

	public function getNumSS() {return $this->Num_SS;}

	public function getFechaContratacion() {return $this->Fecha_contratacion;}

	
	
}