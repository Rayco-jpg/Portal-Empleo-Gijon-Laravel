function reportarOferta(id, titulo) {
    Swal.fire({
        title: "Reportar oferta",
        text: `¿Por qué quieres reportar la oferta "${titulo}"?`,
        input: "textarea",
        inputPlaceholder: "Escribe aquí el motivo del reporte...",
        showCancelButton: true,
        confirmButtonText: "Enviar Reporte",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        inputValidator: (value) => {
            if (!value) return "¡Necesitas escribir un motivo!";
        },
    }).then((result) => {
        if (result.isConfirmed) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

            fetch("/reportar-oferta", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token,
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: JSON.stringify({
                    asunto: "oferta",
                    mensaje: `REPORTE DE OFERTA (ID: ${id}): ${result.value}`,
                }),
            })
            .then((response) => response.json())
            .then(() => {
                Swal.fire("¡Enviado!", "El reporte ha sido enviado al buzón.", "success");
            })
            .catch(() => {
                Swal.fire("Error", "No se pudo enviar el reporte.", "error");
            });
        }
    });
}

window.confirmarRetirada = function(idFormulario) {
    Swal.fire({
        title: '¿Retirar candidatura?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, retirar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(idFormulario).submit();
        }
    });
};

/**
 * Confirmación para que la empresa elimine una oferta publicada
 */
window.confirmarBorradoEmpresa = function(idFormulario, tituloOferta) {
    Swal.fire({
        title: '¿Eliminar oferta?',
        html: `Vas a eliminar la oferta: <b>${tituloOferta}</b>.<br><span style="color: #d33;">Esta acción borrará también a todos los candidatos inscritos.</span>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar permanentemente',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(idFormulario).submit();
        }
    });
};
