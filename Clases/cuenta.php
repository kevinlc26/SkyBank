<?php 

class Cuenta {

    private $ID_cuenta;
    private $Fecha_creacion;
    private $Tip;
    private $Saldo;
    private $Estado;

    public function __construct( $ID_cuenta,  $Fecha_creacion,  $Tip,  $Saldo,  $Estado){
        $this->ID_cuenta = $ID_cuenta;
        $this->Fecha_creacion = $Fecha_creacion;
        $this->Tip = $Tip;
        $this->Saldo = $Saldo;
        $this->Estado = $Estado;
    }
	
    
    public function getIDCuenta() {return $this->ID_cuenta;}

	public function getFechaCreacion() {return $this->Fecha_creacion;}

	public function getTip() {return $this->Tip;}

	public function getSaldo() {return $this->Saldo;}

	public function getEstado() {return $this->Estado;}

	public function setIDCuenta( $ID_cuenta): void {$this->ID_cuenta = $ID_cuenta;}

	public function setFechaCreacion( $Fecha_creacion): void {$this->Fecha_creacion = $Fecha_creacion;}

	public function setTip( $Tip): void {$this->Tip = $Tip;}

	public function setSaldo( $Saldo): void {$this->Saldo = $Saldo;}

	public function setEstado( $Estado): void {$this->Estado = $Estado;}

	
}