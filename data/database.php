<?php
require_once 'config.php';
class Database{
    private $conn;
    public function __construct() {
        $this->conn = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        if ($this->conn->connect_error) {
            
            die('error de conexión:'. $this->conn->connect_error);
    }



}
public function query($sql, $params = []) {
    $estamento = $this->conn->prepare($sql);
    if ($estamento === false) {
        die('error en la preparación:'. $this->conn->error);
    }
    if (!empty($params)) {

        //count($params), cuenta los parámetros q hay,  str_repeat('s', count($params))cre una cadena con tantas 's' como parámetros hay,   's' indica q todos los param serán tratados como strings,   
        
        $types = str_repeat('s', count($params));
       //añade los param a la consulta , $typers es la cadena de tipos que acabamos de creaar,  ...$params es el operador de expansión que desempaqueta el array $params en argumentos individduales.
        $estamento->bind_param($types, ...$params);
    }
//ejecuta la consulta
$estamento->execute();
return $estamento->get_result();

}
public function close() {
    $this->conn->close();


}}
