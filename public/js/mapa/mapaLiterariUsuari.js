const usuariMapa = document.head.querySelector('meta[name="usuariMapa"]')?.getAttribute("value") ?? null;
const mapaUsuari = new L.featureGroup();
mapaLiterari.addLayer(mapaUsuari);

fetch(`${BASE_PATH}/api/mapa/usuari/${usuariMapa}`)
    .then(res => res.json())
    .then(({ data = [] }) => {
        data.forEach(mapa => {
            const marker = crearMarcadorMapa(mapa);
            const popUp = generaDescripcio(mapa);
            marker.addTo(mapaUsuari).bindPopup(popUp);
        })
        const marge = mapaUsuari.getBounds();
        mapaLiterari.fitBounds(marge);
    });

function generaDescripcio(mapa) {
    const { obra, comentari, adreca } = mapa;
    const popUpDiv = document.createElement("div");
    popUpDiv.classList.add("inline-block", "p-1");
    popUpDiv.id = "obra-info";
    const desc = document.createElement("div");
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
    // Autors
    const autors = document.createElement("h2");
    autors.classList.add("text-xs", "italic");
    autors.textContent = obra.autors.map(a => a.nomComplet).join(", ");
    // Comentari
    const comentariP = document.createElement("p");
    comentariP.classList.add("text-xs");
    comentariP.textContent = comentari;
    // Adreça
    const adrecaP = document.createElement("p");
    adrecaP.classList.add("text-xs", "italic");
    adrecaP.textContent = adreca;

    desc.appendChild(titolLink);
    desc.appendChild(autors);
    desc.appendChild(comentariP);
    desc.appendChild(adrecaP);
    popUpDiv.appendChild(desc);
    return popUpDiv;
}