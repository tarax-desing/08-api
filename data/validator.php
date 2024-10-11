
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
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'El formato del email no es válido';
        } elseif (strlen($data['email']) > 255) {
            $errors['email'] = 'El email no puede exceder de 255 caracteres';
        }


        return $errors;
    }

    public static function validarPelicula($data)
    {
        $errors = [];
        if (!isset($data['titulo']) || empty(trim($data['titulo']))) {
            $errors['titulo'] = 'El titulo está vacio.';
        } elseif (strlen($data['titulo']) < 2 || strlen($data['titulo']) > 100) {

            $errors['titulo'] = 'El titulo sólo debe contener entre 2 y 100 caracteres';
        }
        if (!isset($data['precio']) || !is_numeric($data['precio'])) {
            $errors['precio'] = 'El precio está vacio.';
        } elseif (($data['precio']) < 0) {
            $errors['precio'] = 'El precio debe ser mayor de 0';
        }
        if (!isset($data['id_director']) || !is_numeric($data['id_director'])) {
            $errors['id_director'] = 'El id_director está vacio.';
        }
        return $errors;
    }
    public static function validarDirector($data)
    {
        $errors = [];
        if (!isset($data['nombre']) || empty(trim($data['nombre']))) {
            $errors['nombre'] = 'El nombre está vacio.';
        } elseif (strlen($data['nombre']) <2 || strlen($data['nombre']) >30) {

            $errors['nombre'] = 'El nombre sólo debe contener entre 2 y 30 caracteres';
        }
        if (!isset($data['apellido']) || empty($data['apellido'])) {
            $errors['apellido'] = 'El apellido está vacio.';
        } elseif (($data['apellido']) <2) {
            $errors['apellido'] = 'El apellido debe ser mayor de 2';
        }
        if (!isset($data['f_nacimiento']) || empty($data['f_nacimiento'])) {
            $errors['f_nacimiento'] = 'La fecha de nacimiento  está vacia.';
        } else {
            $fecha = DateTime::createFromFormat('Y-m-d', $data['f_nacimiento']);
            if (!$fecha || $fecha->format('Y-m-d') !== $data['f_nacimiento']) {
                $errors['f_nacimiento'] = 'El formato de la fecha debe ser AAAA-MM-DD.';
            } elseif ($fecha > new DateTime()) {
                $errors['f_nacimiento'] = 'La fecha de nacimiento no puede ser en el futuro.';
            } elseif ($fecha < new DateTime('1900-01-01')) {
                $errors['f_nacimiento'] = 'La fecha de nacimiento no puede ser anterior a 1840.';
            }
        }

        if (!isset($data['biografia']) || empty($data['biografia'])) {
            $errors['biografia'] = 'La biografía está vacía.';
        }
        return $errors;
    }
}
