<?php
require_once 'database.php';
require_once 'validator.php';
require_once 'validatorException.php';



class Pelicula {
    private Database $cinebd;



    public function __construct()
    {
        $this->cinebd = new Database();
    }

    public function getAll( ){
        $result = $this->cinebd->query('SELECT id, titulo, precio, id_director FROM pelicula; ');
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getById( $id ){
        $result = $this->cinebd->query('SELECT id, titulo, precio, id_director FROM pelicula WHERE id = ?', [$id]);
        return $result->fetch_assoc();

}
public function create($titulo, $precio, $id_director) {

    //sanear y validar los datos

    //verificar si el id del director existe
    
    $sql = "INSERT INTO pelicula (titulo, precio, id_director) VALUES (?, ?, ?)";
    $stmt = $this->cinebd->query($sql, [$titulo, $precio, $id_director]);

    return $this->cinebd->query("SELECT LAST_INSERT_ID() as id") ->fetch_assoc()["id"];
}
// public function update($id, $titulo, $precio, $id_director) {
//     $data = ['id' => $id, 'titulo' => $titulo, 'precio' => $precio, 'id_director' => $id_director];
//     $dataSaneados = Validator::sanear($data);
//     $errors = Validator::validar($dataSaneados);

//     if (!empty($errors)) {
//         $errores = new ValidatorException($errors);
//         return $errores->getErrors();
//     }
//     $tituloSaneado = $dataSaneados['titulo'];
//     $precioSaneado = $dataSaneados['precio'];
//     $id_directorSaneado = $dataSaneados['id_director'];
//     $idSaneado = $dataSaneados['id'];
//     $this->db->query("UPDATE pelicula SET titulo = ?, precio = ?, id_director = ? WHERE id = ?", [$tituloSaneado, $precioSaneado, $id_directorSaneado, $idSaneado]);
//     return $this->db->query("SELECT ROW_COUNT() as affected")->fetch_assoc()['affected'];
// }
// public function delete($id) {
//     $this->db->query('id
//     ', [$id]);
// }
}