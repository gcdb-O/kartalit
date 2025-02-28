const headerBuscador = document.getElementById("header_buscador");
const headerBuscadorParentDiv = document.getElementById("header_buscador_parent");

let buscadorTimeout;
headerBuscador.addEventListener("input", buscador => {
    clearTimeout(buscadorTimeout);
    const token = buscador.target.value;
    if (token.length >= 3) {
        buscadorTimeout = setTimeout(() => {
            const spinner = headerBuscadorParentDiv.querySelector("span");
            spinner.classList.remove("hidden");
            fetch(`${BASE_PATH}/api/llibre/busca/${token}`, {
                method: "GET"
            })
                .then(res => {
                    if (res.status !== 200) reject(null);
                    return res.json()
                })
                .then(resJson => {
                    const resultLlibres = resJson.data ?? [];
                    if (resultLlibres.length > 0) {
                        const parentResultDiv = ensureParentResult();
                        resultLlibres.forEach(llibre => {
                            addBookToResult(parentResultDiv, llibre)
                        })
                    }
                    spinner.classList.add("hidden");
                })
        }, 1000)
    } else {
        const parentResultDiv = ensureParentResult();
        parentResultDiv.remove();
    }
});
headerBuscador.addEventListener("blur", buscador => {
    setTimeout(() => {
        const parentResultDiv = ensureParentResult();
        parentResultDiv.remove();
        buscador.target.value = null;
    }, 150)
})

function ensureParentResult() {
    const parentResultId = "buscador_result_parent";
    let parentResultDiv = document.getElementById(parentResultId);
    if (!parentResultDiv) {
        parentResultDiv = document.createElement("div");
        parentResultDiv.id = parentResultId;
        parentResultDiv.classList.add("absolute", "top-full", "pt-1", "w-auto", "min-w-full", "bg-orange-50", "max-h-[60vh]", "overflow-y-auto", "overflow-x-visible", "z-100")
        headerBuscadorParentDiv.appendChild(parentResultDiv)
    }
    parentResultDiv.innerHTML = null;
    return parentResultDiv;
}
function addBookToResult(parent, llibre) {
    const llibreDiv = document.createElement("div");
    llibreDiv.classList.add("flex", "gap-4", "p-1", "pl-2", "cursor-pointer", "hover:bg-klit-vermell/25");
    llibreDiv.id = "buscador-info";
    llibreDiv.onclick = function () { window.location.replace(`${BASE_PATH}/llibre/${llibre.id}`) }
    llibreDiv.appendChild(bookCobertaDiv(llibre));
    llibreDiv.appendChild(bookTitolDiv(llibre));
    parent.appendChild(llibreDiv);
}

function bookCobertaDiv(llibre) {
    const cobertaDiv = document.createElement("div");
    cobertaDiv.classList.add("shrink-0");
    const imgCoberta = document.createElement("img");
    imgCoberta.src = `data:image/jpg;base64,${llibre.coberta}`;
    imgCoberta.title = llibre.titol;
    imgCoberta.alt = llibre.titol;
    imgCoberta.onerror = function () { this.src = `${BASE_PATH}/img/no_coberta.png`; };
    imgCoberta.classList.add("w-[20px]", "md:w-[40px]", "rounded-sm");
    cobertaDiv.appendChild(imgCoberta)
    return cobertaDiv;
}
function bookTitolDiv(llibre) {
    const titolDiv = document.createElement("div");
    const titol = document.createElement("h1");
    titol.classList.add("text-base")
    titol.textContent = llibre.titol;
    const autors = document.createElement("h2");
    autors.classList.add("text-sm", "italic")
    autors.textContent = llibre.autors.map(a => a.nomComplet).join(", ");
    titolDiv.appendChild(titol);
    titolDiv.appendChild(autors);
    return titolDiv;
}