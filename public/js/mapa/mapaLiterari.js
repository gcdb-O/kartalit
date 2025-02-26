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

const capaOsm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
    id: 'osm',
})

const mapaLiterari = L.map('mapa-literari', {
    maxZoom: 18,
    layers: [capaOsm],
});
L.control.scale({ imperial: false }).addTo(mapaLiterari);


// Funcions
function getIcona(tipus, precisio) {
    return icones[tipus][precisio ? 1 : 0] ?? iconLite;
}
function crearMarcadorMapa(mapa) {
    const { latitud, longitud, precisio, tipus } = mapa;

    return L.marker(
        [latitud, longitud],
        {
            icon: getIcona(tipus, precisio),
            title: tipus,
            alt: tipus,
            riseOnHover: true
        }
    );
}
function generaDescripcio(mapa, infoObra = false) {
    const { comentari, adreca } = mapa;
    const popUpDiv = document.createElement("div");
    popUpDiv.classList.add("inline-block", "p-1");
    const desc = document.createElement("div");
    if (infoObra) {
        const { obra } = mapa;
        popUpDiv.id = "obra-info";
        // Titol
        const titol = document.createElement("h1");
        titol.classList.add("text-sm", "text-klit-dark", "hover:text-klit-lila", "hover:underline");
        titol.textContent = obra.titolOriginal;
        const titolLink = document.createElement("a");
        titolLink.href = `${BASE_PATH}/obra/${obra.id}`;
        if (obra.titolCatala) {
            titolLink.title = obra.titolCatala;
        }
        titolLink.appendChild(titol);
        desc.appendChild(titolLink);
        // Autors
        const autors = document.createElement("h2");
        autors.classList.add("text-xs", "italic");
        autors.textContent = obra.autors.map(a => a.nomComplet).join(", ");
        desc.appendChild(autors);
    }
    // Comentari
    const comentariP = document.createElement("p");
    comentariP.classList.add("text-xs");
    comentariP.textContent = comentari;
    desc.appendChild(comentariP);
    // Adreça
    if (adreca) {
        const adrecaP = document.createElement("p");
        adrecaP.classList.add("text-xs", "italic");
        adrecaP.textContent = adreca;
        desc.appendChild(adrecaP);
    }
    popUpDiv.appendChild(desc);
    return popUpDiv;
}