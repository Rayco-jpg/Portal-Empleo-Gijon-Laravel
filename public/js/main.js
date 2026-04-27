/**
 * MAIN.JS - Lógica principal de Portal Empleo Gijón
 */

document.addEventListener("DOMContentLoaded", function () {
    const btnMenu = document.getElementById("btn-menu");
    const menuNav = document.getElementById("menu-navegacion");

    if (btnMenu && menuNav) {
        btnMenu.addEventListener("click", function () {
            menuNav.classList.toggle("menu-abierto");

            // Opcional: Cambiar el icono de barras a una X
            const icono = btnMenu.querySelector("i");
            icono.classList.toggle("fa-bars");
            icono.classList.toggle("fa-xmark");
        });
    }
});

// --- 1. FUNCIONES DE INTERFAZ (TEMA Y APARIENCIA) ---

function actualizarIcono(tema) {
    let boton = document.getElementById("btn-tema");
    if (!boton) return;
    let icono = boton.querySelector("i");
    if (!icono) return;

    if (tema === "oscuro") {
        icono.classList.remove("fa-moon");
        icono.classList.add("fa-sun");
    } else {
        icono.classList.remove("fa-sun");
        icono.classList.add("fa-moon");
    }
}

// --- 2. MOTOR PRINCIPAL (DOM CONTENT LOADED) ---

document.addEventListener("DOMContentLoaded", function () {
    // Gestión de Tema (Oscuro/Claro)
    const botonTema = document.getElementById("btn-tema");
    const htmlElement = document.documentElement;
    const temaGuardado = localStorage.getItem("tema") || "claro";

    htmlElement.setAttribute("data-tema", temaGuardado);
    document.body.classList.toggle("modo-oscuro", temaGuardado === "oscuro");
    actualizarIcono(temaGuardado);

    if (botonTema) {
        botonTema.addEventListener("click", function () {
            let temaActual = htmlElement.getAttribute("data-tema");
            let nuevoTema = temaActual === "oscuro" ? "claro" : "oscuro";

            localStorage.setItem("tema", nuevoTema);
            htmlElement.setAttribute("data-tema", nuevoTema);
            document.body.classList.toggle(
                "modo-oscuro",
                nuevoTema === "oscuro",
            );
            actualizarIcono(nuevoTema);
        });
    }

    // Filtro de búsqueda en tablas (Puestos)
    let inputFiltro = document.getElementById("filtroPuesto");
    if (inputFiltro) {
        inputFiltro.addEventListener("keyup", function () {
            let filter = this.value.toUpperCase();
            let tablas = document.getElementsByTagName("table");
            if (tablas.length > 0) {
                let filas = tablas[0]
                    .getElementsByTagName("tbody")[0]
                    .getElementsByTagName("tr");
                for (let i = 0; i < filas.length; i++) {
                    let titulos =
                        filas[i].getElementsByClassName("nombre-puesto");
                    if (titulos.length > 0) {
                        let texto =
                            titulos[0].textContent || titulos[0].innerText;
                        filas[i].style.display =
                            texto.toUpperCase().indexOf(filter) > -1
                                ? ""
                                : "none";
                    }
                }
            }
        });
    }

    // Adaptación dinámica del formulario de Registro
    let selectorTipo = document.getElementById("tipo_usuario");
    if (selectorTipo) {
        let seccionCandidato = document.getElementById("seccion_candidato");
        let seccionEmpresa = document.getElementById("seccion_empresa");

        function adaptarFormulario() {
            let tipo = selectorTipo.value;
            if (seccionCandidato)
                seccionCandidato.style.display =
                    tipo === "candidato" ? "block" : "none";
            if (seccionEmpresa)
                seccionEmpresa.style.display =
                    tipo === "candidato" ? "none" : "block";

            let inCandidato = document.getElementsByName("nombre_candidato")[0];
            let inEmpresa = document.getElementsByName("nombre_empresa")[0];
            if (inCandidato) inCandidato.required = tipo === "candidato";
            if (inEmpresa) inEmpresa.required = tipo === "empresa";
        }
        adaptarFormulario();
        selectorTipo.onchange = adaptarFormulario;
    }

    // Previsualización de Foto de Perfil
    let inputFoto = document.getElementById("foto");
    let imgPreview = document.getElementById("img-preview");
    if (inputFoto && imgPreview) {
        inputFoto.onchange = function () {
            if (inputFoto.files && inputFoto.files[0]) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    imgPreview.setAttribute("src", e.target.result);
                };
                reader.readAsDataURL(inputFoto.files[0]);
            }
        };
    }

    // Validación de archivo Curriculum (Solo PDF)
    let inputCV = document.getElementById("curriculum");
    let textoArchivo = document.getElementById("nombre-archivo-pdf");
    let botonSubir = document.querySelector(".boton-subir-verde-perfil");

    if (inputCV && textoArchivo) {
        inputCV.onchange = function () {
            if (inputCV.files.length > 0) {
                let archivo = inputCV.files[0];
                let extension = archivo.name.split(".").pop().toLowerCase();
                if (extension !== "pdf") {
                    textoArchivo.innerHTML =
                        '<i class="fa-solid fa-circle-xmark"></i> Solo PDF';
                    textoArchivo.style.color = "#e74c3c";
                    inputCV.value = "";
                    if (botonSubir) botonSubir.disabled = true;
                } else {
                    textoArchivo.innerHTML =
                        '<i class="fa-solid fa-check"></i> ' + archivo.name;
                    textoArchivo.style.color = "#27ae60";
                    if (botonSubir) botonSubir.disabled = false;
                }
            }
        };
    }
});

// --- 3. FUNCIONES DE MAPA (LEAFLET) ---

function inicializarMapaBuscador(datosOfertas) {
    let mapaContenedor = document.getElementById("map");
    if (mapaContenedor) {
        if (window.mapaActivo) window.mapaActivo.remove();

        let map = L.map("map").setView([43.5322, -5.6611], 13);
        window.mapaActivo = map;

        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: "&copy; OpenStreetMap",
        }).addTo(map);

        datosOfertas.forEach(function (o) {
            if (o.lat && o.lng) {
                L.marker([o.lat, o.lng]).addTo(map).bindPopup(`
                        <div style="min-width: 150px;">
                            <strong style="color: #007bff;">${o.titulo}</strong><br>
                            <small>Empresa: ${o.empresa}</small><br>
                            <a href="index.php?seccion=ver_oferta&id=${o.id}" 
                               style="display:block; background:#007bff; color:white; text-align:center; padding:4px; border-radius:4px; text-decoration:none; margin-top:5px;">
                               Ver detalles
                            </a>
                        </div>
                    `);
            }
        });
    }
}

function prepararPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // 1. CAPTURA DE DATOS (MÉTODO ULTRA-SEGURO)
    const tituloPuesto = document.querySelector('.nombre-puesto')?.innerText || 'OFERTA DE EMPLEO';
    const descripcion = document.querySelector('.texto-descripcion')?.innerText || 'Sin descripción disponible.';
    
    // Buscamos todas las tarjetas (da igual cómo se llamen las clases internas)
    const tarjetasCuerpo = Array.from(document.querySelectorAll('.tarjeta-dato'));
    const infoCards = tarjetasCuerpo.map(tarjeta => {
        const fuerte = tarjeta.querySelector('strong')?.innerText || "";
        const textoNormal = tarjeta.querySelector('span')?.innerText || "";
        return { label: fuerte, value: textoNormal };
    });

    // 2. DISEÑO DEL PDF
    // Cabecera azul
    doc.setFillColor(52, 152, 219); // El azul #3498db de tu CSS
    doc.rect(0, 0, 210, 40, 'F');
    doc.setTextColor(255, 255, 255);
    doc.setFontSize(22);
    doc.setFont("helvetica", "bold");
    doc.text("PORTAL DE EMPLEO GIJÓN", 20, 25);

    // Título de la oferta
    doc.setTextColor(44, 62, 80);
    doc.setFontSize(18);
    doc.text(tituloPuesto.toUpperCase(), 20, 55);
    
    // Línea decorativa
    doc.setDrawColor(52, 152, 219);
    doc.setLineWidth(1);
    doc.line(20, 60, 100, 60);

    // SECCIÓN DE DETALLES (Empresa, Ubicación, etc.)
    let y = 75;
    doc.setFontSize(11);
    
    infoCards.forEach(item => {
        if (item.label) {
            doc.setFont("helvetica", "bold");
            doc.setTextColor(52, 152, 219);
            doc.text(`${item.label.toUpperCase()}:`, 20, y);
            
            doc.setFont("helvetica", "normal");
            doc.setTextColor(50, 50, 50);
            doc.text(item.value, 65, y);
            y += 10;
        }
    });

    // SECCIÓN DE DESCRIPCIÓN
    y += 10;
    doc.setFont("helvetica", "bold");
    doc.setTextColor(52, 152, 219);
    doc.text("DESCRIPCIÓN DEL PUESTO", 20, y);
    
    doc.setDrawColor(200, 200, 200);
    doc.line(20, y + 2, 190, y + 2);

    doc.setFont("helvetica", "normal");
    doc.setTextColor(60, 60, 60);
    y += 12;
    
    // Ajuste de texto automático
    const lineasDesc = doc.splitTextToSize(descripcion, 170);
    doc.text(lineasDesc, 20, y);

    // Pie de página
    doc.setFontSize(9);
    doc.setTextColor(150, 150, 150);
    doc.text("Proyecto TFG - Portal de Empleo Gijón 2026", 105, 285, { align: "center" });

    // 3. DESCARGA
    doc.save(`Ficha_Oferta_${tituloPuesto.replace(/\s+/g, '_')}.pdf`);
}