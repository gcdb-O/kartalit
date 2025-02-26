const usuariMapa = document.head.querySelector('meta[name="usuariMapa"]')?.getAttribute("value") ?? null;
const mapaUsuari = new L.featureGroup();
mapaLiterari.addLayer(mapaUsuari);

fetch(`${BASE_PATH}/api/mapa/usuari/${usuariMapa}`)
    .then(res => res.json())
    .then(({ data = [] }) => {
        data.forEach(mapa => {
            const marker = crearMarcadorMapa(mapa);
            const popUp = generaDescripcio(mapa, true);
            marker.addTo(mapaUsuari).bindPopup(popUp);
        })
        const marge = mapaUsuari.getBounds();
        mapaLiterari.fitBounds(marge);
    });