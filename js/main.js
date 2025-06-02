// ========== Swiper Inicialización ==========
document.addEventListener("DOMContentLoaded", function () {
  const swiper = new Swiper('.swiper-container', {
    loop: true,
    slidesPerView: 'auto',
    spaceBetween: 30,
    centeredSlides: true,
    speed: 5000,
    autoplay: {
      delay: 0,
      disableOnInteraction: false,
      pauseOnMouseEnter: true
    },
    allowTouchMove: true,
    grabCursor: true,
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
  });
});

// ========== LÓGICA PARA AGREGAR AL CARRITO ==========
document.addEventListener("DOMContentLoaded", function () {
  const botonesAgregar = document.querySelectorAll(".btn-agregar");

  botonesAgregar.forEach(boton => {
    boton.addEventListener("click", function () {
      const curso = {
        nombre: boton.dataset.nombre,
        precio: parseFloat(boton.dataset.precio),
        cantidad: 1
      };

      agregarAlCarrito(curso);
    });
  });

  function agregarAlCarrito(curso) {
    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

    const existe = carrito.find(item => item.nombre === curso.nombre);
    if (existe) {
      existe.cantidad += 1;
    } else {
      carrito.push(curso);
    }

    localStorage.setItem("carrito", JSON.stringify(carrito));
    alert("Curso agregado al carrito 🛒");
  }
});

// ========== MOSTRAR CARRITO EN carrito.html ==========
document.addEventListener("DOMContentLoaded", function () {
  if (document.getElementById("lista-carrito")) {
    mostrarCarrito();
  }
});

function mostrarCarrito() {
  const carrito = JSON.parse(localStorage.getItem("carrito")) || [];
  const contenedor = document.getElementById("lista-carrito");
  const totalElemento = document.getElementById("total");

  contenedor.innerHTML = "";
  let total = 0;

  carrito.forEach((curso, index) => {
    const subtotal = curso.precio * curso.cantidad;
    total += subtotal;

    const tarjeta = document.createElement("div");
    tarjeta.classList.add("tarjeta-curso");
    tarjeta.innerHTML = `
      <div class="etiqueta-descuento">${curso.cantidad > 1 ? 'Oferta x' + curso.cantidad : 'Curso'}</div>
      <div class="imagen-curso">
        <img src="img/${obtenerImagen(curso.nombre)}" alt="${curso.nombre}">
      </div>
      <div class="contenido-curso">
        <p class="modalidad">Curso agregado</p>
        <h3>${curso.nombre}</h3>
        <p class="descripcion">Subtotal por ${curso.cantidad} unidad/es:</p>
        <div class="info-precio">
          <p class="precio-descuento">$ ${subtotal.toFixed(2)} ARS</p>
        </div>
        <button class="btn-eliminar" onclick="eliminarCurso(${index})">Eliminar</button>
      </div>
    `;
    contenedor.appendChild(tarjeta);
  });

  totalElemento.textContent = total.toFixed(2);
}

// ========== ELIMINAR CURSO ==========
function eliminarCurso(index) {
  let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
  carrito.splice(index, 1);
  localStorage.setItem("carrito", JSON.stringify(carrito));
  mostrarCarrito();
}

// ========== MENÚ HAMBURGUESA ==========
document.addEventListener("DOMContentLoaded", () => {
  const btnToggle = document.querySelector(".menu-toggle");
  const menu = document.querySelector(".menu ul");

  if (btnToggle && menu) {
    btnToggle.addEventListener("click", () => {
      menu.classList.toggle("activo");
    });
  }
});

// ========== FUNCIÓN AUXILIAR PARA IMÁGENES SEGÚN CURSO ==========
function obtenerImagen(nombreCurso) {
  const nombre = nombreCurso.toLowerCase();

  if (nombre.includes("programación")) return "programacion.png";
  if (nombre.includes("sql")) return "sql.png";
  if (nombre.includes("inglés") || nombre.includes("ingles")) return "ingles.png";
  return "default.jpg";
}
