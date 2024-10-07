<?php

///hace las consulta a la base de usuario
require_once 'database.php';
class Usuario{

    private Database $db;
    public function __construct(){
        $this->db = new Database();
    }
    public function getAll(){
        $result= $this->db->query('SELECT id, nombre,email FROM usuario;');
        return $result->fetch_all(MYSQLI_ASSOC)
           ;
     
    }
    public function getById($id){
        $result= $this->db->query('SELECT id, nombre, email FROM usuario WHERE id = ?', [$id]);
        return $result->fetch_assoc();



}
}
