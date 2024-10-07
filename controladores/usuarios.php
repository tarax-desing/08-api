<?php
require_once '../data/usuario.php';

/*establecer el encabezado
la respuesta va a ser un objeto tipo JSON


*/ 
// header('Content-´Type: application/json');
$usuario =new Usuario();
/*
la variable superglobal  $_SERVER ['REQUEST_METHOD']
REQUEST_METHOD  : puede ser:
GET   Para solicitar datos de servidores
POST  Para enviar datos al servidor
PUT   Para actualizar datos existentes
DELETE Para eliminar
*/
$method = $_SERVER['REQUEST_METHOD'];
/**
 * 
 * CONTIENE INFO ADICIONAL SOBRE LA RUTA DE LA SOLICITUD ACTUAL
 * Ejemplo: si la url es http://ejemplo.com/script.php/usuarios/123
 * $_SERVER['PATH_INFO']  /usuarios/123
 * $_SERVER['SCRIPT_NAME'] /script.php
 */
// if(isset($_SERVER['PATH_INFO'])){
//     $request = explode('/',trim($_SERVER['PATH_INFO'],));
// }else{
//     $request =
//     [];
// }
$request = explode('=', $_SERVER['REQUEST_URI'])[1];
// var_dump($request);

// $id = explode('=', $request);


$id = isset($request[0]) && is_numeric($request[0]) ? intval($request[0]) : null;



switch($method) {
    case 'GET': 
        if($id){
            $respuesta = getUsuarioByid($usuario,$id);
        }else{
            $respuesta = getAllUsuarios($usuario);
        }
        echo json_encode($respuesta);
        break;
        default:
        http_response_code(405);
        echo json_encode(['error'=> 'Método no permitido']);
       

}
function getUsuarioByid($usuario,$id) {
    return $usuario->getById($id);
}
    function getAllUsuarios($usuario) {
        return $usuario->getAll();
    }