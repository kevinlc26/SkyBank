<?php
abstract class Persona{
    protected $NIE;
    protected $Nombre;
    protected $Apellidos;
    protected $Nacionalidad;
    protected $FechaNacimiento;
    protected $Telefono;
    protected $Email;
    protected $Direccion;

    public function __construct($NIE, $Nombre, $Apellidos, $Nacionalidad, $FechaNacimiento, $Telefono, $Email, $Direccion) {
        $this->NIE = $NIE;
        $this->Nombre = $Nombre;
        $this->Apellidos = $Apellidos;
        $this->Nacionalidad = $Nacionalidad;
        $this->FechaNacimiento = $FechaNacimiento;
        $this->Telefono = $Telefono;
        $this->Email = $Email;
        $this->Direccion = $Direccion;
    }
    
}

    public function getNIE() {return $this->NIE;}

	public function getNombre() {return $this->Nombre;}

	public function getApellidos() {return $this->Apellidos;}

	public function getNacionalidad() {return $this->Nacionalidad;}

	public function getFechaNacimiento() {return $this->FechaNacimiento;}

	public function getTelefono() {return $this->Telefono;}

	public function getEmail() {return $this->Email;}

	public function getDireccion() {return $this->Direccion;}
    

    public function setNIE( $NIE): void {$this->NIE = $NIE;}

	public function setNombre( $Nombre): void {$this->Nombre = $Nombre;}

	public function setApellidos( $Apellidos): void {$this->Apellidos = $Apellidos;}

	public function setNacionalidad( $Nacionalidad): void {$this->Nacionalidad = $Nacionalidad;}

	public function setFechaNacimiento( $FechaNacimiento): void {$this->FechaNacimiento = $FechaNacimiento;}

	public function setTelefono( $Telefono): void {$this->Telefono = $Telefono;}

	public function setEmail( $Email): void {$this->Email = $Email;}

	public function setDireccion( $Direccion): void {$this->Direccion = $Direccion;}

	

?>