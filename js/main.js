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

// ========== LÓGICA PARA OFERTAS (Agregar al carrito) ==========
document.addEventListener("DOMContentLoaded", function () {
  const botonesAgregar = document.querySelectorAll(".btn-agregar");

  botonesAgregar.forEach(boton => {
    boton.addEventListener("click", function () {
      const curso = {
        nombre: this.dataset.nombre,
        precio: parseFloat(this.dataset.precio),
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

// ========== LÓGICA PARA CARRITO.HTML ==========
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
    tarjeta.classList.add("producto-carrito");
    tarjeta.innerHTML = `
      <div class="contenido-curso">
        <h4>${curso.nombre}</h4>
        <p><strong>Precio:</strong> $${curso.precio}</p>
        <p><strong>Cantidad:</strong> ${curso.cantidad}</p>
        <p><strong>Subtotal:</strong> $${subtotal}</p>
      </div>
      <div class="acciones-curso">
        <button onclick="eliminarCurso(${index})" class="btn-eliminar">Eliminar</button>
      </div>
    `;
    contenedor.appendChild(tarjeta);
  });

  totalElemento.textContent = total.toFixed(2);
}

function eliminarCurso(index) {
  let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
  carrito.splice(index, 1);
  localStorage.setItem("carrito", JSON.stringify(carrito));
  mostrarCarrito();
}

document.addEventListener("DOMContentLoaded", () => {
  const btnToggle = document.querySelector(".menu-toggle");
  const menu = document.querySelector(".menu ul");

  if (btnToggle && menu) {
    btnToggle.addEventListener("click", () => {
      menu.classList.toggle("activo");
    });
  }
});

