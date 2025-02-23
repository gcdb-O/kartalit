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
            data.forEach(({ latitud, longitud, precisio, tipus, usuari, adreca, comentari }) => {
                const markerGroup = usuari === usuariId ? mapaPropi : mapaAltres;
                let desc = `<p>${comentari}</p>`;
                if (adreca) { desc += `<p><i>${adreca}</i></p>`; }
                const marcador = crearMarcadorMapa({ latitud, longitud, precisio, tipus });
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