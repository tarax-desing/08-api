
<?php
class Validator
{
    public static function sanear($datos)
    {
        $saneados = [];
        foreach ($datos as $key => $value) {
            $saneados[$key] = htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
        }
        return $saneados;
    }
    ////validar nombre
    public static function validar($data)
    {
        $errors = [];

        if (!isset($data['nombre']) || empty(trim($data['nombre']))) {
            $errors['nombre'] = 'El nombre está vacio.';
        } elseif (strlen($data['nombre']) < 2 || strlen($data['nombre']) > 50) {
            $errosr['nombre'] = 'El nombre debe tener entre 2 y 50 caracteres:';
        } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ' -]+$/u", $data['nombre'])) {
            $errors['nombre'] = 'El nombre sólo debe contener letras y espacios';
        }


////validar correo
if (!isset($data['email']) || empty(trim($data['email']))) {
    $errors['email'] = 'El email está vacio.';
} elseif (!filter_var($data['email'],FILTER_VALIDATE_EMAIL)){
    $errors['email'] = 'El formato del email no es válido';
} elseif (strlen( $data['email']) >255) {
    $errors['email'] = 'El email no puede exceder de 255 caracteres';
}


return $errors;


    }


}
