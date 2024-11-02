<?php

class Cliente extends Persona {
    private $ID_cliente;
    private $NIE;
    private $PIN;
    
    public function __construct($ID_cliente, $NIE, $PIN, $Nombre, $Apellidos, $Nacionalidad, $FechaNacimiento, $Telefono, $Email, $Direccion){
        parent::__construct($NIE, $Nombre, $Apellidos, $Nacionalidad, $FechaNacimiento, $Telefono, $Email, $Direccion);
        $this->ID_cliente = $ID_cliente;
        $this->NIE = $NIE;
        $this->PIN = $PIN;
    }

    public function setIDCliente( $ID_cliente): void {$this->ID_cliente = $ID_cliente;}

	public function setNIE( $NIE): void {$this->NIE = $NIE;}

	public function setPIN( $PIN): void {$this->PIN = $PIN;}

    
    public function getIDCliente() {return $this->ID_cliente;}

	public function getNIE() {return $this->NIE;}

	public function getPIN() {return $this->PIN;}


	
	
}

?>