// 1. --- LA FUNCIÓN QUE YA TENÍAS (Perfecta) ---
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

// 2. --- EL MOTOR (Lo que le falta a tu archivo) ---
document.addEventListener("DOMContentLoaded", function () {
    const botonTema = document.getElementById("btn-tema");
    const htmlElement = document.documentElement; // Importante para data-tema

    // Cargar el tema guardado al entrar en cualquier página (Buscador, Favoritos...)
    const temaGuardado = localStorage.getItem("tema") || "claro";
    
    // Aplicamos el tema al HTML y al icono
    htmlElement.setAttribute("data-tema", temaGuardado);
    document.body.classList.toggle("modo-oscuro", temaGuardado === "oscuro");
    actualizarIcono(temaGuardado);

    // Escuchar el clic en el botón
    if (botonTema) {
        botonTema.addEventListener("click", function () {
            let temaActual = htmlElement.getAttribute("data-tema");
            let nuevoTema = (temaActual === "oscuro") ? "claro" : "oscuro";

            // Guardamos la elección para que no se pierda al navegar
            localStorage.setItem("tema", nuevoTema);
            
            // Aplicamos los cambios
            htmlElement.setAttribute("data-tema", nuevoTema);
            document.body.classList.toggle("modo-oscuro", nuevoTema === "oscuro");
            actualizarIcono(nuevoTema);
        });
    }
});

function inicializarMapaBuscador(datosOfertas) {
    let mapaContenedor = document.getElementById("map");
    if (mapaContenedor) {
        if (window.mapaActivo) {
            window.mapaActivo.remove();
        }
        let map = L.map("map").setView([43.5322, -5.6611], 13);
        window.mapaActivo = map;

        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: "&copy; OpenStreetMap",
        }).addTo(map);

        datosOfertas.forEach(function (o) {
            if (o.lat && o.lng) {
                L.marker([o.lat, o.lng])
                    .addTo(map)
                    .bindPopup(
                        '<div style="min-width: 150px;">' +
                            '<strong style="color: #007bff;">' +
                            o.titulo +
                            "</strong><br>" +
                            "<small>" +
                            o.empresa +
                            "</small><br>" +
                            '<a href="index.php?seccion=ver_oferta&id=' +
                            o.id +
                            '" style="display:block; background:#007bff; color:white; text-align:center; padding:4px; border-radius:4px; text-decoration:none; margin-top:5px;">Ver detalles</a>' +
                            "</div>",
                    );
            }
        });
    }
}

window.prepararPDF = function () {
    const el = document.getElementById("datos-oferta");
    if (!el) return;

    const puesto = el.dataset.titulo;
    const empresa = el.dataset.empresa;
    const zona = el.dataset.zona;
    const salario = el.dataset.salario;
    const jornadaText =
        document.querySelector(".tarjeta-dato:nth-of-type(4) span")
            ?.innerText || "No especificada";
    const experienciaText =
        document.querySelector(".tarjeta-dato:nth-of-type(5) span")
            ?.innerText || "Sin especificar";
    const publicadaText =
        document.querySelector(".tarjeta-dato:nth-of-type(6) span")
            ?.innerText || "";
    const descripcionText =
        document.querySelector(".texto-descripcion")?.innerText || "";
    const datosCompletosPDF = {
        "Puesto Vacante": puesto,
        Empresa: empresa,
        Ubicación: zona,
        "Salario Estimado": salario,
        "Tipo de Jornada": jornadaText,
        "Experiencia Mínima": experienciaText,
        "Fecha Publicación": publicadaText,
    };
    exportarPDF(`Oferta_${puesto}`, datosCompletosPDF, descripcionText);
};

window.exportarPDF = function (tituloDoc, contenido, descripcionLarga) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const pageHeight = doc.internal.pageSize.height;
    doc.setFont("helvetica", "bold");
    doc.setFontSize(18);
    doc.setTextColor(52, 152, 219);
    doc.text("PORTAL EMPLEO GIJÓN", 20, 20);
    doc.setFontSize(9);
    doc.setFont("helvetica", "italic");
    doc.setTextColor(100);
    doc.text("Documento informativo de vacante", 20, 26);
    doc.setDrawColor(52, 152, 219);
    doc.setLineWidth(0.5);
    doc.line(20, 30, 190, 30);
    doc.setFontSize(14);
    doc.setFont("helvetica", "bold");
    doc.setTextColor(0, 0, 0);
    doc.text("Detalles de la Oferta", 20, 42);
    doc.setFontSize(10);
    let y = 52;

    for (const [key, value] of Object.entries(contenido)) {
        doc.setFont("helvetica", "bold");
        doc.text(`${key}:`, 20, y);
        doc.setFont("helvetica", "normal");
        doc.text(`${value}`, 60, y);
        y += 8;
    }
    y += 4;
    doc.setDrawColor(230);
    doc.line(20, y, 190, y);
    y += 10;
    doc.setFont("helvetica", "bold");
    doc.setFontSize(12);
    doc.text("Descripción del puesto:", 20, y);
    y += 8;

    doc.setFont("helvetica", "normal");
    doc.setFontSize(10);
    const líneas = doc.splitTextToSize(descripcionLarga, 170);

    líneas.forEach((línea) => {
        if (y > pageHeight - 20) {
            doc.addPage();
            y = 20;
        }
        doc.text(línea, 20, y);
        y += 6;
    });

    const finalY = doc.internal.pageSize.height - 10;
    doc.setFontSize(8);
    doc.setTextColor(150);
    doc.text(
        `Generado el: ${new Date().toLocaleString()} - TFG Portal Empleo`,
        20,
        finalY,
    );

    doc.save(`${tituloDoc.replace(/\s+/g, "_")}.pdf`);
};

const hfToken = document.querySelector('meta[name="hf-token"]').content;

async function llamarALaIA(datos) {
    const respuesta = await fetch(
        "https://api-inference.huggingface.co/models/TU_MODELO",
        {
            method: "POST",
            headers: {
                Authorization: `Bearer ${hfToken}`, 
                "Content-Type": "application/json",
            },
            body: JSON.stringify(datos),
        },
    );
}

document.addEventListener("DOMContentLoaded", function () {
    let btn = document.getElementById("btn-tema");
    let raiz = document.documentElement;

    let temaGuardado = localStorage.getItem("modo");
    if (temaGuardado === "oscuro") {
        raiz.setAttribute("data-tema", "oscuro");
        actualizarIcono("oscuro");
    }

    if (btn) {
        btn.addEventListener("click", function () {
            let estadoActual = raiz.getAttribute("data-tema");
            if (estadoActual === "oscuro") {
                raiz.setAttribute("data-tema", "claro");
                localStorage.setItem("modo", "claro");
                actualizarIcono("claro");
            } else {
                raiz.setAttribute("data-tema", "oscuro");
                localStorage.setItem("modo", "oscuro");
                actualizarIcono("oscuro");
            }
        });
    }

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

    let botonesBorrar = document.getElementsByClassName("btn-borrar-oferta");
    for (let j = 0; j < botonesBorrar.length; j++) {
        botonesBorrar[j].addEventListener("click", function (event) {
            if (!confirm("¿Estás seguro de que deseas eliminar esta oferta?")) {
                event.preventDefault();
            }
        });
    }

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
                        '<i class="fa-solid fa-circle-xmark"></i> Error: Solo PDF';
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
});
