const divMapaNou = document.getElementById("mapa-nou");
const nouMapaBoto = document.getElementById("add-mapa-boto") ?? null;

nouMapaBoto.addEventListener("click", () => {
    divMapaNou.classList.toggle("hidden");
    if (divMapaNou.classList.contains("hidden")) {
        nouMapa.clearLayers();
        return;
    }
    window.scrollTo({
        top: document.getElementById("mapa-literari-block").offsetTop,
        behavior: "smooth",
    });
    divMapa.classList.remove("hidden");
    mapaLiterari.invalidateSize(true);
    if (!mapaLiterari._loaded) {
        mapaLiterari.setView([0, 0], 2);
    }
    const mapCenter = mapaLiterari.getCenter();
    const markerNouMapa = L.marker(mapCenter, {
        icon: icones[`${nouMapaTipus.value}`][nouMapaPrecisio.checked ? 1 : 0] ?? iconLite,
        draggable: true,
        autoPan: true
    }).addTo(nouMapa);

    markerNouMapa.on("moveend", ({ target }) => {
        const destLatLng = target.getLatLng();
        nouMapaLat.value = Math.round(destLatLng.lat * 1000000) / 1000000;
        nouMapaLon.value = Math.round(destLatLng.lng * 1000000) / 1000000;
    });
    [nouMapaTipus, nouMapaLat, nouMapaLon, nouMapaPrecisio].forEach(input => {
        input.addEventListener("change", ({ target }) => {
            if ([nouMapaTipus, nouMapaPrecisio].includes(target)) {
                markerNouMapa.setIcon(icones[`${nouMapaTipus.value}`][nouMapaPrecisio.checked ? 1 : 0] ?? iconLite);
            } else {
                markerNouMapa.setLatLng([nouMapaLat.value, nouMapaLon.value]);
            }
        })
    });
});

const formMapaNou = document.getElementById("form-mapa-nou");
formMapaNou.addEventListener("submit", (e) => {
    e.preventDefault();
    //TODO: Vaidar dades

    const formData = new FormData(formMapaNou);
    let invalidData = 0;
    if (!formData.get("latitud")) {
        addInputWarning(document.getElementById("nou_mapa_lat").classList);
        invalidData++;
    }
    if (!formData.get("longitud")) {
        addInputWarning(document.getElementById("nou_mapa_lon").classList);
        invalidData++;
    }
    if (!formData.get("comentari")) {
        addInputWarning(document.getElementById("nou_mapa_comentari").classList);
        invalidData++;
    }
    if (!formData.get("obra")) {
        addInputWarning(document.getElementById("nou_mapa_obra").classList);
        invalidData++;
    }
    if (invalidData > 0) return

    fetch(`${BASE_PATH}/api/mapa/obra/${formData.get("obra")}`, {
        method: "POST",
        body: formData
    }).then(res => {
        if (res.status === 201) {
            window.location.reload();
        }
    })
    //TODO: Gestionar error.



})

function addInputWarning(input) {
    input.add("border-2");
    input.add("border-klit-light");
}