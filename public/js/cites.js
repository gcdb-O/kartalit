document.getElementById("add-cita-boto").addEventListener("click", function () {
    window.scrollTo({
        top: document.getElementById("cites-block").offsetTop,
        behavior: "smooth",
    })
    const citaNovaDiv = document.getElementById("cita-nova");
    citaNovaDiv.classList.toggle("hidden");
});
const formCitaNova = document.getElementById("form-cita-nova");

formCitaNova.addEventListener("submit", (e) => {
    e.preventDefault();
    // TODO: Validar dades
    const formData = new FormData(formCitaNova);

    fetch(`${BASE_PATH}/api/cita/llibre/${llibreId}/obra/${obres[0]}`, {
        method: 'POST',
        body: formData
    })
        .then(res => {
            if (res.status === 201) {
                window.location.reload();
            }
        });
    // TODO: Gestionar possible error a la crida
});

function citaPrivat(citaId, privat) {
    const boto = document.getElementById(`privat-cita-${citaId}`);
    const icon = boto.querySelector("i");

    icon.classList.remove("fa-lock");
    icon.classList.remove("fa-unlock");
    icon.classList.add("fa-compass");
    icon.classList.add("animate-spin");

    fetch(`${BASE_PATH}/api/cita/${citaId}`, {
        method: "PATCH",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ privat: !privat }),
    })
        .then(res => {
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
                boto.classList.remove("cursor-pointer");
                boto.onclick = null;
                icon.classList.add("fa-ban");
                icon.title = "No pots editar aquesta cita";
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
        const citaNovaForm = document.getElementById("form-cita-nova");
        const clonedForm = citaNovaForm.cloneNode(true);
        citaDiv.appendChild(clonedForm);
        [citaP, paginaP, comentariP].forEach(i => i?.classList.add("hidden"));

        clonedForm.querySelector("input#nou_cita_pagina").value = paginaP.textContent.split(/\t/).pop();
        // clonedForm.querySelector("input#nou_cita_privat").checked = 
        clonedForm.querySelector("textarea#nou_cita_cita").innerText = citaP.innerText;
        //TODO: Treure-li els parèntesis
        clonedForm.querySelector("textarea#nou_cita_comentari").innerText = comentariP?.innerText || null;
        const cancelButton = document.createElement("input");
        cancelButton.type = "reset";
        cancelButton.value = "Cancel·la";
        cancelButton.className = "px-1 mt-2 mr-2 border-2 rounded-lg cursor-pointer border-klit-lila hover:bg-klit-lila hover:text-white";
        cancelButton.onclick = function () { citaEdita(citaId, false) }
        const submitButton = clonedForm.querySelector('input[type="submit"]');
        clonedForm.querySelector('p.text-right').insertBefore(cancelButton, submitButton)

        clonedForm.addEventListener("submit", (e) => {
            e.preventDefault();
            //TODO: Spinners
            const formData = new FormData(clonedForm);
            const bodyData = new URLSearchParams(formData);
            fetch(`${BASE_PATH}/api/cita/${citaId}`, {
                method: "PATCH",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: bodyData.toString()
            }).then(res => {
                if (res.status === 200) {
                    paginaP.textContent = `Pàg. ${formData.get("pagina")}`
                    citaP.innerText = formData.get("cita");
                    // comentariP?.innerText = formData.get("comentari");
                    citaDiv.removeChild(citaDiv.querySelector("form"));
                    [citaP, paginaP, comentariP].forEach(i => i?.classList.remove("hidden"));
                }
            })
        });
    } else {
        citaDiv.removeChild(citaDiv.querySelector("form"));
        [citaP, paginaP, comentariP].forEach(i => i?.classList.remove("hidden"));
    }
    boto.onclick = function () { citaEdita(citaId, !editar) }
}