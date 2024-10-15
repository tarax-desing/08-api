<?php
require_once 'database.php';
require_once 'validator.php';
require_once 'validatorException.php';

class Director{
    private Database $cinebd;
    public function __construct()
    {
        $this->cinebd = new Database();
    }
    public function getAll( ){
        $result = $this->cinebd->query('SELECT id, nombre, apellido, f_nacimiento, biografia FROM director; ');
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById( $id ){
        
        $result = $this->cinebd->query('SELECT id, nombre, apellido, f_nacimiento, biografia FROM director WHERE id = ?', [$id]);
        return $result->fetch_assoc();

}
public function create($nombre, $apellido,$f_nacimiento, $biografia){
    $data = ['nombre' => $nombre, 'apellido' => $apellido, 'f_nacimiento' => $f_nacimiento, 'biografia'=>$biografia];
    $dataSaneados = Validator::sanear($data);
    $errors = Validator::validarDirector($dataSaneados);

    if(!empty($errors)){
        $errores = new ValidatorException($errors);
        return $errors;
    }
    $nombreSaneado = $dataSaneados['nombre'];
    $apellidoSaneado = $dataSaneados['apellido'];
    $f_nacimientoSaneado = $dataSaneados['f_nacimiento'];
    $biografiaSaneado = $dataSaneados['biografia'];

    
    $this->cinebd->query("INSERT INTO director (nombre, apellido, f_nacimiento, biografia) VALUES(?, ?, ?,?)", [$nombreSaneado, $apellidoSaneado, $f_nacimientoSaneado,$biografiaSaneado]);

    return $this->cinebd->query("SELECT LAST_INSERT_ID() as id")->fetch_assoc()['id'];
}
public function update($id, $nombre, $apellido, $f_nacimiento, $biografia ){
    $data = ['id'=> $id,'nombre' => $nombre, 'apellido' => $apellido, 'f_nacimiento' => $f_nacimiento, 'biografia'=>$biografia];
    $dataSaneados = Validator::sanear($data);
    $errors = Validator::validarDirector($dataSaneados);

    if(!empty($errors)){
       
        return $errors;
    }

    $idSaneado = $dataSaneados['id'];
    $nombreSaneado = $dataSaneados['nombre'];
    $apellidoSaneado = $dataSaneados['apellido'];
    $f_nacimientoSaneado = $dataSaneados['f_nacimiento'];
    $biografiaSaneado = $dataSaneados['biografia'];
    $this->cinebd->query("UPDATE director SET nombre = ?, apellido = ?, f_nacimiento = ? , biografia = ? WHERE id = ?", [$nombreSaneado, $apellidoSaneado, $f_nacimientoSaneado, $biografiaSaneado, $idSaneado]);
    return $this->cinebd->query("SELECT ROW_COUNT() as affected")->fetch_assoc()['affected'];
}
public function delete($id){
    $this->cinebd->query('DELETE FROM director WHERE id = ?', [$id]);
    return $this->cinebd->query('SELECT ROW_COUNT()as affected') -> fetch_assoc()['affected'];
}
}