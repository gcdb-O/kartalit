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
addListenersToFormMapa(formMapaNou);
formMapaNou.addEventListener("submit", (e) => {
    e.preventDefault();
    const formData = validarFormMapa(formMapaNou);
    if (formData) {
        fetch(`${BASE_PATH}/api/mapa/obra/${formData.get("obra")}`, {
            method: "POST",
            body: formData
        }).then(res => {
            if (res.status === 201) {
                window.location.reload();
            } else {
                reject();
            }
        }).catch(() => {
            alert("Alguna cosa ha fallat i no s'ha pogut crear la ubicació.");
        })
    }
})

/** @param {HTMLFormElement} */
function addListenersToFormMapa(formMapa) {
    const inputLat = formMapa.querySelector("input#nou_mapa_lat");
    const inputLon = formMapa.querySelector("input#nou_mapa_lon");
    const inputAdreca = formMapa.querySelector("input#nou_mapa_adreca");
    const inputComentari = formMapa.querySelector("textarea#nou_mapa_comentari");
    inputLat.addEventListener("input", e => {
        const lat = e.target.value;
        if (lat.length === 0) {
            removeInputWarning(e.target)
        } else {
            isValidFloat(lat) && Math.abs(parseFloat(lat)) <= 90 ? removeInputWarning(e.target) : addInputWarning(e.target);
        }
    });
    inputLon.addEventListener("input", e => {
        const lon = e.target.value;
        if (lon.length === 0) {
            removeInputWarning(e.target)
        } else {
            isValidFloat(lon) && Math.abs(parseFloat(lon)) <= 180 ? removeInputWarning(e.target) : addInputWarning(e.target);
        }
    });
    inputComentari.addEventListener("input", e => {
        removeInputWarning(e.target)
    });
    inputAdreca.addEventListener("input", e => {
        const adreca = e.target.value;
        if (adreca.length <= 100) {
            removeInputWarning(e.target)
        } else {
            addInputWarning(e.target);
        }
    });

}
/**
 * @param {HTMLFormElement} formMapa 
 * @returns {FormData|null}
 */
function validarFormMapa(formMapa) {
    const formData = new FormData(formMapa);
    let invalidData = 0;
    if (!formData.get("tipus") || formData.get("tipus").length > 50) {
        addInputWarning(document.getElementById("nou_mapa_tipus"));
        invalidData++;
    }
    if (!formData.get("latitud") ||
        !isValidFloat(formData.get("latitud")) ||
        Math.abs(parseFloat(formData.get("latitud"))) > 90
    ) {
        addInputWarning(document.getElementById("nou_mapa_lat"));
        invalidData++;
    }
    if (!formData.get("longitud") ||
        !isValidFloat(formData.get("longitud")) ||
        Math.abs(parseFloat(formData.get("longitud"))) > 180
    ) {
        addInputWarning(document.getElementById("nou_mapa_lon"));
        invalidData++;
    }
    if (!formData.get("comentari")) {
        addInputWarning(document.getElementById("nou_mapa_comentari"));
        invalidData++;
    }
    if (!formData.get("obra") || isValidInt(formData.get("obra"))) {
        addInputWarning(document.getElementById("nou_mapa_obra"));
        invalidData++;
    }
    return invalidData === 0 ? formData : null;
}