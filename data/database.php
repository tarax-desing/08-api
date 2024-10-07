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
public function query($sql,$params = []) {
    $estamento = $this->conn->prepare($sql);
    if ($estamento === false) {
        die('error en la preparación:'. $this->conn->error);
    }
    if (!empty($params)) {
        $types = str_repeat('s', count($params));
       
        $estamento->bind_param($types, ...$params);
    }

$estamento->execute();
return $estamento->get_result();

}
public function close() {
    $this->conn->close();


}}
