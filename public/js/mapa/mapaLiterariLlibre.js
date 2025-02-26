const [mapaPropi, mapaAltres, nouMapa] = [new L.featureGroup(), new L.featureGroup(), new L.featureGroup()];
mapaLiterari.addLayer(mapaPropi);
mapaLiterari.addLayer(mapaAltres);
mapaLiterari.addLayer(nouMapa);

obres.forEach(obraId => {
    fetch(`${BASE_PATH}/api/mapa/obra/${obraId}`)
        .then(res => res.json())
        .then(({ data }) => {
            if (!data || data.length === 0) {
                divMapaBuit.classList.remove("hidden");
                return;
            }
            divMapa.classList.remove("hidden");
            data.forEach((mapa) => {
                const markerGroup = mapa.usuari === usuariId ? mapaPropi : mapaAltres;
                const marcador = crearMarcadorMapa(mapa);
                const desc = generaDescripcio(mapa);
                marcador.addTo(markerGroup).bindPopup(desc);
            })
            //TODO: Refactoritzar això..
            if (usuariId === null) {
                const marges = mapaAltres.getBounds();
                mapaLiterari.fitBounds(marges);
            } else {
                const boundGroup = mapaPropi.getLayers().length === 0 ? mapaAltres : mapaPropi;
                const marges = boundGroup.getBounds();
                mapaLiterari.fitBounds(marges);
            }
        })
});