const divMapa = document.getElementById("mapa-literari");
const divMapaBuit = document.getElementById("mapa-buit");

// Nova ubicacio
const nouMapaTipus = document.getElementById("nou_mapa_tipus");
const nouMapaPrivat = document.getElementById("nou_mapa_privat");
const nouMapaAdreca = document.getElementById("nou_mapa_adreca");
const nouMapaUbicacio = document.getElementById("nou_mapa_ubicacio");
const nouMapaLat = document.getElementById("nou_mapa_lat");
const nouMapaLon = document.getElementById("nou_mapa_lon");
const nouMapaPrecisio = document.getElementById("nou_mapa_precisio");

// Icones
const iconsMapaPath = PATHS["icons"]["mapa"];

const iconPrecis = L.Icon.extend({
    options: {
        iconSize: [30, 30],
        iconAnchor: [15, 30],
        popupAnchor: [0, -15],
    }
});
const iconImprecis = L.Icon.extend({
    options: {
        iconSize: [30, 30],
        iconAnchor: [15, 15],
        popupAnchor: [0, -15],
    }
})
const iconLitePin = new iconPrecis({ iconUrl: `${iconsMapaPath}/lite_pin.png` });
const iconLlibrePin = new iconPrecis({ iconUrl: `${iconsMapaPath}/llibre_pin.png` });
const iconLite = new iconImprecis({ iconUrl: `${iconsMapaPath}/lite.png` });
const iconLlibre = new iconImprecis({ iconUrl: `${iconsMapaPath}/llibre.png` });
const iconCasa = new iconImprecis({ iconUrl: `${iconsMapaPath}/casa_lite.png` });
const iconEstrella = new iconImprecis({ iconUrl: `${iconsMapaPath}/estrella_lite.png` });
const icones = {
    "Lloc puntual": [
        iconLlibre,
        iconLlibrePin,
    ],
    "Ubicació general": [
        iconLite,
        iconLitePin,
    ],
    "UbicaciÃ³ general": [
        iconLite,
        iconLitePin,
    ],
    "Lloc on viu": [iconCasa, iconCasa],
    "Lloc permanent": [iconEstrella, iconEstrella],
};

const mapaCapa = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
    id: 'osm',
})
const [mapaPropi, mapaAltres, nouMapa] = [new L.featureGroup(), new L.featureGroup(), new L.featureGroup()];

const mapaLiterari = L.map('mapa-literari', {
    maxZoom: 18,
    layers: [mapaCapa, mapaPropi, mapaAltres, nouMapa],
});
L.control.scale({ imperial: false }).addTo(mapaLiterari);
// L.control.layers({ "OpenStreetMap": mapaCapa }, { "Propis": mapaPropi, "Altres": mapaAltres }).addTo(mapaLiterari);

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
                L.marker(
                    [latitud, longitud],
                    {
                        icon: icones[`${tipus}`][precisio ? 1 : 0] ?? iconLite,
                        title: tipus,
                        alt: tipus,
                        riseOnHover: true,
                    }
                ).addTo(markerGroup).bindPopup(desc);
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