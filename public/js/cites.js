// Botó afegir cita
document.getElementById("add-cita-boto").addEventListener("click", function () {
    window.scrollTo({
        top: document.getElementById("cites-block").offsetTop,
        behavior: "smooth",
    })
    const citaNovaDiv = document.getElementById("cita-nova");
    citaNovaDiv.classList.toggle("hidden");
});
// Formulari cita nova
const formCitaNova = document.getElementById("form-cita-nova");
addListenersToCitaForm(formCitaNova);
formCitaNova.addEventListener("submit", (e) => {
    e.preventDefault();
    const formData = validarFormCita(formCitaNova);
    if (formData) {
        fetch(`${BASE_PATH}/api/cita/llibre/${llibreId}/obra/${obres[0]}`, {
            method: 'POST',
            body: formData
        })
            .then(res => {
                if (res.status === 201) {
                    window.location.reload();
                } else {
                    reject();
                }
            })
            .catch(() => {
                alert("Alguna cosa ha fallat i no s'ha pogut crear la cita.");
            });
    }
});
// Cita privat
function citaPrivat(citaId, privat) {
    const boto = document.getElementById(`privat-cita-${citaId}`);
    const icon = boto.querySelector("i");

    icon.classList.remove("fa-lock");
    icon.classList.remove("fa-unlock");
    icon.classList.add("fa-compass", "animate-spin");

    fetch(`${BASE_PATH}/api/cita/${citaId}`, {
        method: "PATCH",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ privat: !privat }),
    }).then(res => {
        icon.classList.remove("animate-spin");
        icon.classList.remove("fa-compass");
        if (res.status === 200) {
            if (privat) {
                icon.classList.add("fa-lock");
            } else {
                icon.classList.add("fa-unlock");
            }
            boto.onclick = function () { citaPrivat(citaId, !privat) };
            document.getElementById(`cita_${citaId}`).classList.toggle("text-klit-dark");
        } else if (res.status === 403) {
            boto.onclick = null;
            icon.title = "No pots editar aquesta cita";
            icon.classList.add("fa-ban");
            boto.classList.remove("cursor-pointer");
        } else {
            privat ? icon.classList.add("fa-unlock") : icon.classList.add("fa-lock");
        }
    })
}
function citaEdita(citaId, editar = true) {
    const citaDiv = document.getElementById(`cita_${citaId}`);
    if (!citaDiv) return;
    const boto = document.getElementById(`edit-cita-${citaId}`);
    const citaP = citaDiv.querySelector(`p#cita_${citaId}_cita`);
    const paginaP = citaDiv.querySelector(`p#cita_${citaId}_pag`);
    const comentariP = citaDiv.querySelector(`p#cita_${citaId}_comentari`);

    if (editar) {
        // Crear formulari amb dades de la cita
        const citaNovaForm = document.getElementById("form-cita-nova");
        const clonedForm = citaNovaForm.cloneNode(true);
        addListenersToCitaForm(clonedForm);
        // Afegir botó d'eliminar cita
        const deleteButton = document.createElement("span");
        deleteButton.classList.add("float-right", "m-2", "cursor-pointer");
        deleteButton.title = "Eliminar cita";
        const xmark = document.createElement("i");
        xmark.classList.add("fa-solid", "fa-xmark", "text-lg", "text-klit-vermell");
        deleteButton.appendChild(xmark);
        deleteButton.onclick = function () { eliminaCita(citaId, xmark, citaDiv) }
        clonedForm.children[0].appendChild(deleteButton);
        //Afegir formulari amb dades
        citaDiv.appendChild(clonedForm);
        [citaP, paginaP, comentariP].forEach(i => i?.classList.add("hidden"));

        clonedForm.querySelector("input#nou_cita_pagina").value = paginaP.textContent.split(".").pop().trim();
        // clonedForm.querySelector("input#nou_cita_privat").checked = 
        clonedForm.querySelector("textarea#nou_cita_cita").innerText = citaP.innerText;
        clonedForm.querySelector("textarea#nou_cita_comentari").innerText = comentariP?.innerText.slice(1, -1) || null;
        // Botó de cancel·lar
        const cancelButton = document.createElement("input");
        cancelButton.type = "reset";
        cancelButton.value = "Cancel·la";
        cancelButton.className = "px-1 mt-2 mr-2 border-2 rounded-lg cursor-pointer border-klit-lila hover:bg-klit-lila hover:text-white";
        cancelButton.onclick = function () { citaEdita(citaId, false) }
        // Botó d'acceptar
        const submitButton = clonedForm.querySelector('input[type="submit"]');
        clonedForm.querySelector('p.text-right').insertBefore(cancelButton, submitButton)

        // Crida d'editar cita
        clonedForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const editIcon = boto.querySelector("i");
            editIcon.classList.remove("fa-pen-to-square");
            editIcon.classList.add("fa-compass", "animate-spin");
            const formData = validarFormCita(clonedForm);
            if (formData) {
                const bodyData = new URLSearchParams(formData);
                fetch(`${BASE_PATH}/api/cita/${citaId}`, {
                    method: "PATCH",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: bodyData.toString()
                }).then(res => {
                    if (res.status === 200) {
                        // TODO: Resoldre millor això.
                        paginaP.textContent = `Pàg. ${formData.get("pagina")}`
                        citaP.innerText = formData.get("cita");
                        // comentariP?.innerText = formData.get("comentari");
                        citaDiv.removeChild(citaDiv.querySelector("form"));
                        [citaP, paginaP, comentariP].forEach(i => i?.classList.remove("hidden"));
                    } else {
                        alert("Alguna cosa ha fallat i no s'ha pogut editar la cita.");
                    }
                })
            }
            editIcon.classList.remove("fa-compass", "animate-spin");
            editIcon.classList.add("fa-pen-to-square");
        });
    } else {
        citaDiv.removeChild(citaDiv.querySelector("form"));
        [citaP, paginaP, comentariP].forEach(i => i?.classList.remove("hidden"));
    }
    boto.onclick = function () { citaEdita(citaId, !editar) }
}
function eliminaCita(citaId, xmark, citaDiv) {
    xmark.classList.remove("fa-xmark");
    xmark.classList.add("fa-spinner", "animate-spin");
    if (confirm("Vols eliminar la cita? Aquesta acció no es pot desfer.")) {
        fetch(`${BASE_PATH}/api/cita/${citaId}`, { method: "DELETE" })
            .then(res => {
                if (res.status === 200) {
                    citaDiv.parentNode.remove();
                } else {
                    xmark.classList.remove("fa-spinner", "animate-spin");
                    xmark.classList.add("fa-xmark");
                }
            })
    } else {
        xmark.classList.remove("fa-spinner", "animate-spin");
        xmark.classList.add("fa-xmark");
    }
}
/** @param {HTMLFormElement} formCita  */
function addListenersToCitaForm(formCita) {
    const inputPag = formCita.querySelector("input#nou_cita_pagina");
    inputPag.addEventListener("input", e => {
        const pagina = e.target.value;
        if (pagina.length === 0) {
            removeInputWarning(e.target)
        } else {
            isValidInt(pagina) ? removeInputWarning(e.target) : addInputWarning(e.target);
        }
    });
}
/**
 * @param {HTMLFormElement} formHtml 
 * @returns {FormData|null}
 */
function validarFormCita(formHtml) {
    const formData = new FormData(formHtml);
    let invalidData = 0;
    if (formData.get("pagina")) {
        if (!isValidInt(formData.get("pagina"))) {
            invalidData++;
            const pagInput = formHtml.querySelector("input#nou_cita_pagina");
            addInputWarning(pagInput);
        }
    }
    return invalidData === 0 ? formData : null;
}