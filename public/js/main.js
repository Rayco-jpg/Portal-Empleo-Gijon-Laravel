/**
 * MAIN.JS - Lógica principal de Portal Empleo Gijón
 */

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
            document.body.classList.toggle("modo-oscuro", nuevoTema === "oscuro");
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
                let filas = tablas[0].getElementsByTagName("tbody")[0].getElementsByTagName("tr");
                for (let i = 0; i < filas.length; i++) {
                    let titulos = filas[i].getElementsByClassName("nombre-puesto");
                    if (titulos.length > 0) {
                        let texto = titulos[0].textContent || titulos[0].innerText;
                        filas[i].style.display = texto.toUpperCase().indexOf(filter) > -1 ? "" : "none";
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
            if (seccionCandidato) seccionCandidato.style.display = (tipo === "candidato") ? "block" : "none";
            if (seccionEmpresa) seccionEmpresa.style.display = (tipo === "candidato") ? "none" : "block";

            let inCandidato = document.getElementsByName("nombre_candidato")[0];
            let inEmpresa = document.getElementsByName("nombre_empresa")[0];
            if (inCandidato) inCandidato.required = (tipo === "candidato");
            if (inEmpresa) inEmpresa.required = (tipo === "empresa");
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
                    textoArchivo.innerHTML = '<i class="fa-solid fa-circle-xmark"></i> Solo PDF';
                    textoArchivo.style.color = "#e74c3c";
                    inputCV.value = "";
                    if (botonSubir) botonSubir.disabled = true;
                } else {
                    textoArchivo.innerHTML = '<i class="fa-solid fa-check"></i> ' + archivo.name;
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
                L.marker([o.lat, o.lng])
                    .addTo(map)
                    .bindPopup(`
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

// --- 5. GENERACIÓN DE PDF (jsPDF) ---

window.prepararPDF = function () {
    const el = document.getElementById("datos-oferta");
    if (!el) return;

    const contenido = {
        "Puesto Vacante": el.dataset.titulo,
        "Empresa": el.dataset.empresa,
        "Ubicación": el.dataset.zona,
        "Salario": el.dataset.salario,
        "Jornada": document.querySelector(".tarjeta-dato:nth-of-type(4) span")?.innerText || "N/A",
    };
    const desc = document.querySelector(".texto-descripcion")?.innerText || "";
    exportarPDF(`Oferta_${el.dataset.titulo}`, contenido, desc);
};

window.exportarPDF = function (tituloDoc, contenido, descripcionLarga) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    doc.setFont("helvetica", "bold");
    doc.setFontSize(18);
    doc.setTextColor(52, 152, 219);
    doc.text("PORTAL EMPLEO GIJÓN", 20, 20);
    
    doc.setDrawColor(52, 152, 219);
    doc.line(20, 30, 190, 30);
    
    let y = 50;
    doc.setFontSize(10);
    for (const [key, value] of Object.entries(contenido)) {
        doc.setFont("helvetica", "bold");
        doc.text(`${key}:`, 20, y);
        doc.setFont("helvetica", "normal");
        doc.text(`${value}`, 60, y);
        y += 8;
    }
    
    doc.save(`${tituloDoc}.pdf`);
};
