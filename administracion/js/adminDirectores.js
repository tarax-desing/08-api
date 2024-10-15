const API_URL = "http://localhost/08-php-api/controladores/directores.php";
const errorElement = document.getElementById("createError");

function limpiarHTML(str) {
    return str.replace(/[^\w. @-]/gi, function (e) {
      return "&#" + e.charCodeAt(0) + ";";
    });
  }
  function validarNombre(nombre) {
    return nombre.length >= 2 && nombre.length <= 50;
  }
  
  function validarApellido(apellido) {
    return true;
  }

 
  function validarFecha(f_nacimiento) {
    return true;
  }
  function esFechaAnterior(f_nacimiento){
    const hoy = new Date();
    const fechaAcomprobar = new Date(f_nacimiento);
    return fechaAcomprobar < hoy;

  }
  function validarBiografia(biografia) {
    return biografia.length < 65500;
  }
  function esEntero(str) {
    return /^\d+$/.test(str);
  }
  
  function getDirectors() {
    fetch(API_URL)
      .then((response) => response.json())
      .then((directors) => {
        const tableBody = document.querySelector("#directoresTable tbody");
        tableBody.innerHTML = "";
        directors.forEach((director) => {
          const sanitizedNombre = limpiarHTML(director.nombre);
          const sanitizedApellido = limpiarHTML(director.apellido);
          const sanitizedFecha = limpiarHTML(director.f_nacimiento);
          const sanitizedBiografia = limpiarHTML(director.biografia);
          tableBody.innerHTML += `
          <tr data-id="${director.id}" class="view-mode">
              <td>${director.id}</td>
              <td>
                  <span class="listado">${sanitizedNombre}</span>
                  <input class="edicion" type="text" value="${sanitizedNombre}">
              </td>
              <td>
                  <span class="listado">${sanitizedApellido}</span>
                  <input class="edicion" type="text" value="${sanitizedApellido}">
              </td>
              <td>
                  <span class="listado">${sanitizedFecha}</span>
                  <input class="edicion" type="date" value="${sanitizedFecha}">
              </td>
              <td>
                  <span class="listado">${sanitizedBiografia}</span>
                  <textarea class="edicion" >${sanitizedBiografia}</textarea>
                  
              </td>
              <td class="td-btn">
                  <button class="listado" onclick="editMode(${director.id})">Editar</button>
                  <button class="listado" onclick="deleteDirector(${director.id})">Eliminar</button>
                  <button class="edicion" onclick="updateDirector(${director.id})">Guardar</button>
                  <button class="edicion" onclick="cancelEdit(${director.id})">Cancelar</button>
              </td>
          </tr>`;
        });
      })
      .catch((error) => console.log("Error", error));
  }

  function createDirector(event) {
    event.preventDefault();
    const nombre = document.getElementById("createNombre").value.trim();
    const apellido = document.getElementById("createApellido").value.trim();
    const f_nacimiento = document.getElementById("createFechaNacimiento").value.trim();
    const biografia = document.getElementById("createBiografia").value.trim();
    // const errorElement = document.getElementById("createError");
  
    if (!validarNombre(nombre)) {
      errorElement.textContent = "El nombre debe tener entre 2 y 50 caracteres.";
      return;
    }
    if (!validarApellido(apellido)) {
      errorElement.textContent = "El nombre de la película debe ser válido.";
      return;
    }
    if (!validarFecha(f_nacimiento)) {
        errorElement.textContent = "El formato fecha no es correcto.";
        return;
      }
    if (!validarBiografia(biografia)) {
        errorElement.textContent = "Formato biografía incorrecto.";
        return;
      }

    errorElement.textContent = "";
  
    fetch(API_URL, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ nombre, apellido, f_nacimiento, biografia }),
    })
      .then((response) => response.json())
      .then((result) => {
        console.log("Director creado:", result);
        if (esEntero(result["id"])) {
          errorElement.textContent = "Director creado";
        }
        getDirectors();
        event.target.reset();
      })
      .catch((error) => {
        console.log("Error", error);
        errorElement.textContent = JSON.stringify(error);
      });
  }

  function editMode(id) {
    const row = document.querySelector(`tr[data-id="${id}"]`);
    row.querySelectorAll(".edicion").forEach((ro) => {
      ro.style.display = "inline-block";
    });
    row.querySelectorAll(".listado").forEach((ro) => {
      ro.style.display = "none";
    });
  }
  
  function cancelEdit(id) {
    const row = document.querySelector(`tr[data-id="${id}"]`);
    row.querySelectorAll(".edicion").forEach((ro) => {
      ro.style.display = "none";
    });
    row.querySelectorAll(".listado").forEach((ro) => {
      ro.style.display = "inline-block";
    });
  }
  
  function updateDirector(id) {
    const row = document.querySelector(`tr[data-id="${id}"]`);
    const newNombre = row.querySelector("td:nth-child(2) input").value.trim();
    const newApellido = row.querySelector("td:nth-child(3) input").value.trim();
    const newFecha = row.querySelector("td:nth-child(4) input").value.trim();
    const newBiografia = row.querySelector("td:nth-child(5) textarea").value.trim();
  
    if (!validarNombre(newNombre)) {
      alert("El nombre debe tener entre 2 y 50 caracteres.");
      return;
    }
    if (!validarApellido(newApellido)) {
      alert("El apellido de la película no es válido.");
      return;
    }
  
    fetch(`${API_URL}?id=${id}`, {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ nombre: newNombre, apellido: newApellido, f_nacimiento: newFecha, biografia: newBiografia }),
    })
      .then((response) => response.json())
      .then((result) => {
        console.log("Director actualizado", result);
        if (!esEntero(result['affected'])) {
          errorElement.innerHTML = result['affected'];
        } else {
          getDirectors();
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Error al actualizar. Por favor, inténtalo de nuevo.");
      });
  }
  
  function deleteDirector(id) {
    if (confirm("¿Estás seguro de que quieres eliminar este director?")) {
      fetch(`${API_URL}?id=${id}`, {
        method: "DELETE",
      })
        .then((response) => response.json())
        .then((result) => {
          console.log("Director eliminado:", result);
          getDirectors();
        })
        .catch((error) => console.error("Error", error));
    }
  }
  
  document.getElementById("createForm").addEventListener("submit", createDirector);
  
  getDirectors();