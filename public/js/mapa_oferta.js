document.addEventListener('DOMContentLoaded', function() {
    const contenedor = document.getElementById('mapa-seleccion');
    
    if (contenedor) {
        const mapaSeleccion = L.map('mapa-seleccion').setView([43.5322, -5.6611], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(mapaSeleccion);

        let marcador;

        const latInicial = document.getElementById('lat_input').value;
        const lngInicial = document.getElementById('lng_input').value;
        
        if (latInicial && lngInicial) {
            marcador = L.marker([latInicial, lngInicial]).addTo(mapaSeleccion);
            mapaSeleccion.setView([latInicial, lngInicial], 15);
        }

        mapaSeleccion.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            
            if (marcador) {
                marcador.setLatLng(e.latlng);
            } else {
                marcador = L.marker(e.latlng).addTo(mapaSeleccion);
            }

            const inputLat = document.getElementById('lat_input');
            const inputLng = document.getElementById('lng_input');

            if(inputLat && inputLng) {
                inputLat.value = lat;
                inputLng.value = lng;
            }
        });
    }
});