function initMap() {
    const mapOptions = {
        center: { lat: 21.484798431396484, lng: -104.8647232055664 }, // Coordenadas del centro del mapa
        zoom: 14, // Nivel de zoom del mapa
    };

    const map = new google.maps.Map(document.getElementById("map"), mapOptions);

    const marker = new google.maps.Marker({
        position: { lat: 21.484798431396484, lng: -104.8647232055664 }, // Coordenadas del marcador
        map: map,
        title: "Pa' La Barber Shop", // Texto del marcador
    });
}