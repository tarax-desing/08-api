const  url = 'https://taraxdesing.com/controladores/peliculas.php';
contenedorPeliculas=document.getElementById('track-grid')
function getPeliculas (){
    fetch(url).then(result=>result.json()).then(data=>{
        console.log(data);
        data.forEach(pelicula=>{
            contenedorPeliculas.innerHTML+=`
            <div class='track-item'>
            <img src="carteles/${pelicula.cartel}">
            <h3>${pelicula.titulo}</h3>
            <p>${pelicula.precio}</p>
            </div>
            `
        })
    })
}

getPeliculas()

