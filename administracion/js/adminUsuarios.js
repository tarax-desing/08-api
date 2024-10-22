const API_URL = " https://taraxdesing.com/08-php-api/controllers/usuarios.php;";
const errorElement = document.getElementById("createError");
// @param {*} str
// @return String limpio de caracteres HTMLAllCollection
// limpia una cadena de texto convirtiendo ciertos caracteres potencialmente peligrosos en sus equivalentes html seguros.
// coincide con cualquier carácter que no esté en el conjunto,
// \w caracteres de palabra, letras, num, guión bajo
// \  . @- admite puntos espacios arrobas y guión medio
//gi Flags para que la busq, de caracteres sea global (g ) e insensible a mayusc. y min,
// funcion (c){return '&# + caches.charCodeAt(0) + ';'} crea para cada carácter su código ASCII con charCodeAt()
///devuelve la entidad HTML numérica correspondiente, por ej &#60; para

function limpiarHTML(str) {
  return str.replace(/[^\w. @-]/gi, function (e) {
    return "&#" + e.charCodeAt(0) + ";";
  });
}

function validarEmail(email) {
  return email;
}
function validarNombre(nombre) {
  return nombre.length >= 2 && nombre.length <= 50;
}
function esEntero(str) {
  return /^\d+$/.test(str);
}

function getUsers() {
  fetch(API_URL)
    .then((response) => response.json())
    .then((users) => {
      const tableBody = document.querySelector("#userTable tbody");
      tableBody.innerHTML = "";
      users.forEach((user) => {
        const sanitizedNombre = limpiarHTML(user.nombre);
        const sanitizedEmail = limpiarHTML(user.email);
        tableBody.innerHTML += `
        <tr data-id="${user.id}" class="view-mode">
            <td> 
                ${user.id}
                </td>
                 <td>
                <span class= "listado">${sanitizedNombre}</span>
                <input class="edicion" type="text" value="${sanitizedNombre}">
                </td>
                 <td>
                  <span  class= "listado">${sanitizedEmail}</span>
                   <input class="edicion" type="email" value="${sanitizedEmail}">
                  </td>
                    <td class="td-btn">
                   <button class= "listado" onclick="editMode(${user.id})">Editar</button>
                   <button class= "listado" onclick="deleteUser(${user.id})">Eliminar</button>
                   <button class="edicion" onclick="updateUser(${user.id})">Guardar</button>
                   <button class="edicion" onclick="cancelEdit(${user.id})">Cancelar</button>
                   </td>
        </tr> `;
      });
    })
    .catch((error) => console.log("Error", error));
}

function createUser(event) {
  event.preventDefault();
  const nombre = document.getElementById("createNombre").value.trim();
  const email = document.getElementById("createEmail").value.trim();
  //   const errorElement = document.getElementById("createError");
  if (!validarNombre(nombre)) {
    errorElement.textContent = "El nombre debe tener entre 2 y 50 caracteres.";
    return;
  }
  if (!validarEmail(email)) {
    errorElement.textContent = "El email debe ser formato válido.";
    return;
  }
  errorElement.textContent = "";

  

  fetch(API_URL, {
    
    
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ nombre, email }),
  })
    .then((response) => response.json())
    .then((result) => {
      console.log("Usuario creado:", result);
      if (!esEntero(result["id"])) {
        mostrarErrores(result["id"]);
      } else {
        getUsers();
      }
      event.target.reset();
    })
    .catch((error) => {
      console.log("Error: ", JSON.stringify(error));
    })
}




function updateUser(id) {
  const row = document.querySelector(`tr[data-id="${id}"]`);
  const newNombre = row.querySelector("td:nth-child(2) input").value.trim();
  const newEmail = row.querySelector("td:nth-child(3) input").value.trim();

  if (!validarNombre(newNombre)) {
    alert("nombre con más caracteres");
    return;
  }
  if (!validarEmail(newEmail)) {
    alert("email no válido");
    return;
  }
  fetch(`${API_URL}?id=${id}`, {
    method: "PUT",
    headers: {
      "Content-Type": " application/json",
    },
    body: JSON.stringify({ nombre: newNombre, email: newEmail }),
  })
    .then((response) => response.json())
    .then((result) => {
      console.log("usuario actualizado", result);
      if (!esEntero(result["affected"])) {
        mostrarErrores(result["affected"]);
      }else{
        getUsers();
      }
    })
    .catch((error) => {
      console.error("error:", error);
      alert("error al actualizar prueba otra vez");
    });
  }
    function mostrarErrores(errores){
      arrayErrores = Object.values(errores);
      errorElement.innerHTML = '<ul>';
      arrayErrores.forEach(error => {
          return errorElement.innerHTML += `<li>${error}</li>`;
      });
      errorElement.innerHTML += '</ul>';
  }
  
  function editMode(id){
      const row = document.querySelector(`tr[data-id="${id}"]`);
      row.querySelectorAll('.edicion').forEach(ro => {
          ro.style.display = 'inline-block';
      })
      row.querySelectorAll('.listado').forEach(ro => {
          ro.style.display = 'none';
      })
  }
  function cancelEdit(id){
      const row = document.querySelector(`tr[data-id="${id}"]`);
      row.querySelectorAll('.edicion').forEach(ro => {
          ro.style.display = 'none';
      })
      row.querySelectorAll('.listado').forEach(ro => {
          ro.style.display = 'inline-block';
      })
    }

  function deleteUser(id) {
    if (confirm("¿Estás seguro de que quieres eliminar este usuario?")) {
      fetch(`${API_URL}?id=${id}`, {
        method: "DELETE",
      })
        .then((response) => response.json())
        .then((result) => {
          console.log("Usuario eliminado:", result);
          getUsers();
        })
        .catch((error) => console.error("error", error));
    }
    // alert('Eliminar usuario ' + id);
  }


document.getElementById("createForm").addEventListener("submit", createUser);

getUsers();
