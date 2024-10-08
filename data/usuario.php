<?php

///hace las consulta a la base de usuario
require_once 'database.php';
require_once 'validator.php';
require_once 'validatorException.php';
class Usuario
{

    private Database $db;
    public function __construct()
    {
        $this->db = new Database();
    }
    public function getAll()
    {
        $result = $this->db->query('SELECT id, nombre, email FROM usuario;');
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getById($id)
    {
        $result = $this->db->query('SELECT id, nombre, email FROM usuario WHERE id = ?', [$id]);
        return $result->fetch_assoc();
    }
    public function create($nombre, $email)
    {
        $data = ['nombre' => $nombre, 'email' => $email];
        $dataSaneados = Validator::sanear($data);
        $errors = Validator::validar($dataSaneados);

        if (!empty($errors)) {
           $errores = new ValidatorException($errors);
            return $errores->getErrors();
        }
        //lanzamos la consulta
        $nombreSaneado = $dataSaneados['nombre'];
        $emailSaneado = $dataSaneados['email'];

        $this->db->query('INSERT INTO usuario (nombre, email) VALUES (?,?)',[$nombreSaneado,$emailSaneado]);
        return $this->db->query("SELECT LAST_INSERT_ID() as id") ->fetch_assoc()["id"];
    }
}
