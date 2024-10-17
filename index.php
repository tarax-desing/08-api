
<?php
// galeria.php
$conexion = mysqli_connect("localhost", "root", "", "cinebd");
// require_once '../data/pelicula.php';
// require_once 'utilidades.php';

// header('Content-Type: application/json');
// $pelicula = new Pelicula();
// $method = $_SERVER['REQUEST_METHOD'];
// $uri = $_SERVER['REQUEST_URI'];
// $parametros = Utilidades::parseUriParameters($uri);
// $id = Utilidades::getParameterValue($parametros, 'id');


// Obtener todas las imágenes con el título de su película
$query = "SELECT i.ruta_imagen, p.titulo 
          FROM imagenes i 
          JOIN pelicula p ON i.pelicula_id = p.id 
          JOIN cartel c ON p.id = c.pelicula_id 
          ORDER BY p.titulo";
// ?>

<!DOCTYPE html>
<html>
<head>
    <title>Galería de Películas</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }

        .galeria {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .imagen-container {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            background-color: white;
        }

        .imagen-container:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }

        .imagen-container img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .imagen-container:hover img {
            transform: scale(1.1);
        }

        .titulo-pelicula {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            color: white;
            padding: 15px;
            margin: 0;
            opacity: 0;
            transition: opacity 0.3s ease;
            text-align: center;
        }

        .imagen-container:hover .titulo-pelicula {
            opacity: 1;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 2.5em;
        }

        /* Añadir filtros */
        .filtros {
            margin-bottom: 20px;
            text-align: center;
        }

        .filtros select {
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        /* Animación de carga */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <h1>Galería de Películas</h1>

    <!-- Filtro por película -->
    <div class="filtros">
        <select id="filtro-pelicula" onchange="filtrarPeliculas()">
            <option value="">Todas las películas</option>
            <?php
            $query_peliculas = "SELECT DISTINCT titulo FROM peliculas ORDER BY titulo";
            $resultado_peliculas = mysqli_query($conexion, $query_peliculas);
            while($pelicula = mysqli_fetch_assoc($resultado_peliculas)) {
                echo "<option value='".$pelicula['titulo']."'>".$pelicula['titulo']."</option>";
            }
            ?>
        </select>
    </div>

    <div class="galeria">
        <?php
        
        while($fila = mysqli_fetch_assoc($resultado)) {
            if($fila['ruta_imagen'] != '') {
                echo "<div class='imagen-container fade-in' data-pelicula='".$fila['titulo']."'>";
                echo "<img src='".$fila['ruta_imagen']."' alt='".$fila['titulo']."'>";
                echo "<h3 class='titulo-pelicula'>".$fila['titulo']."</h3>";
                echo "</div>";
            }
        }
    
        ?>
    </div>

    <script>
    function filtrarPeliculas() {
        const filtro = document.getElementById('filtro-pelicula').value;
        const contenedores = document.querySelectorAll('.imagen-container');

        contenedores.forEach(contenedor => {
            if (filtro === '' || contenedor.dataset.pelicula === filtro) {
                contenedor.style.display = 'block';
            } else {
                contenedor.style.display = 'none';
            }
        });
    }

    // Añadir efecto de carga suave
    document.addEventListener('DOMContentLoaded', function() {
        const imagenes = document.querySelectorAll('.imagen-container');
        imagenes.forEach((imagen, index) => {
            setTimeout(() => {
                imagen.classList.add('fade-in');
            }, index * 100);
        });
    });
    </script>
</body>
</html>
</body>
</html>