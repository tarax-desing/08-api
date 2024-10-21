<?php
// Agrega estos headers al inicio de tu archivo peliculas.php
header('Access-Control-Allow-Origin: https://taraxdesing.com');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Maneja la solicitud preliminar OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit();
}

require_once '../data/usuario.php';
require_once 'utilidades.php';


/*establecer el encabezado
la respuesta va a ser un objeto tipo JSON


// */

header('Content-Type: application/json');
$usuario = new Usuario();
/*
la variable superglobal  $_SERVER ['REQUEST_METHOD']
REQUEST_METHOD  : puede ser:
GET   Para solicitar datos de servidores
POST  Para enviar datos al servidor
PUT   Para actualizar datos existentes
DELETE Para eliminar
*/
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

//obtener los parámetros de la petición
$parametros = Utilidades::parseUriParameters($uri);

//obtener el parámetro id
$id = Utilidades::getParameterValue($parametros, 'id');
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

// var_dump($request);

// $id = explode('=', $request);


// $id = isset($request[0]) && is_numeric($request[0]) ? intval($request[0]) : null;
// Obtener la URI de la petición



switch ($method) {
    case 'GET':
        if ($id) {
            $respuesta = getUsuarioByid($usuario, $id);
        } else {
            $respuesta = getAllUsuarios($usuario);
        }
        echo json_encode($respuesta);
        break;
    case 'POST':
        setUser($usuario);
        break;
    case 'PUT':
        if ($id) {
            updateUser($usuario, $id);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID no proporcionado']);
        }
        break;
    case 'DELETE':
        if ($id) {
            deleteUser($usuario, $id);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID no proporcionado']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
}
function getUsuarioByid($usuario, $id)
{
    return $usuario->getById($id);
}
function getAllUsuarios($usuario)
{
    return $usuario->getAll();
}
function setUser($usuario)
{
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['nombre']) && isset($data['email'])) {
        $id = $usuario->create($data['nombre'], $data['email']);
        echo json_encode(['id' => $id]); // Corregido
    }else{
        echo json_encode(['error'=> 'Datos insuficientes']);
    }
}
function updateUser($usuario, $id){
    $data = json_decode(file_get_contents('php://input'), true);

    if(isset($data['nombre']) && isset($data['email'])){
      $affected = $usuario->update($id, $data['nombre'], $data['email']);
      echo json_encode(['affected' => $affected]); 
    }else{
      echo json_encode(['error' => 'datos insuficientes']);
    }
 
  }
        //    $affected = $usuario->update($id,$data['nombre'],$data['email']);
        //    echo json_encode(['affected'=> $affected]);
        // }

        function deleteUser($usuario, $id)
        {
            $affected = $usuario->delete($id);
            echo json_encode(['affected' => $affected]);
        }
        /////


    
