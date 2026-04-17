document.addEventListener("DOMContentLoaded", function () {
    const contenedor = document.getElementById("map");

    if (contenedor) {
        const mapaBuscador = L.map("map").setView([43.5322, -5.6611], 13);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: "&copy; OpenStreetMap contributors",
        }).addTo(mapaBuscador);

        setTimeout(() => {
            const puntos = window.DATOS_MAPA_BUSCADOR || [];

            puntos.forEach((p) => {
                if (p.lat && p.lng) {
                    const lat = parseFloat(p.lat);
                    const lng = parseFloat(p.lng);

                    L.marker([lat, lng]).addTo(mapaBuscador).bindPopup(`
    <div style="text-align: center; font-family: sans-serif; min-width: 140px;">
        <strong style="color: #2563eb; display: block; margin-bottom: 3px; font-size: 13px;">${p.titulo}</strong>
        <b style="font-size: 11px; color: #4b5563;">${p.empresa}</b><br>
        
        <div style="margin: 5px 0; font-size: 11px;">
            <span style="color: #10b981; font-weight: bold;">
                ${p.salario}
            </span>
            <br>
            <span style="color: #6b7280; font-weight: 500;">
                Jornada: ${p.jornada ? p.jornada : "Consultar"}
            </span>
        </div>

        <a href="/oferta/${p.id}" 
           style="display:inline-block; margin-top:5px; background:#2563eb; color:white; padding:5px 12px; border-radius:6px; text-decoration:none; font-size:11px; font-weight: bold;">
           Ver detalles
        </a>
    </div>
`);
                }
            });
            mapaBuscador.invalidateSize();
        }, 100);
    }
});
