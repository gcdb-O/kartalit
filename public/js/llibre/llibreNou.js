const formLlibreNou = document.getElementById("form-llibre-nou");
addListenersToLlibreNouForm(formLlibreNou);
formLlibreNou.addEventListener("submit", (e) => {
    e.preventDefault();
    const formData = validarFormLlibreNou(formLlibreNou);
    if (formData) {
        fetch(`${BASE_PATH}/api/llibre`, {
            method: "POST",
            body: formData
        }).then(res => {
            if (res.status === 201) {
                //TODO: Redirigir al llibre nou
                window.location.reload();
            } else {
                reject();
            }
        }).catch(() => {
            alert("Alguna cosa ha fallat i no s'ha pogut crear el llibre.");
        })
    }
})

/** @param {HTMLFormElement} formLlibreNou */
function addListenersToLlibreNouForm(formLlibreNou) {
    // Titols de menys de 150 caràcters
    const inputLlibreTitol = formLlibreNou.querySelector("input#llibre_titol");
    const inputObraTitolOriginal = formLlibreNou.querySelector("input#obra_titol_original");
    const inputObraTitolCatala = formLlibreNou.querySelector("input#obra_titol_catala");
    const inputTitols = [inputLlibreTitol, inputObraTitolOriginal, inputObraTitolCatala];
    inputTitols.forEach(inputTitol => {
        inputTitol.addEventListener("input", e => {
            const titol = e.target.value;
            if (titol.length <= 150) {
                removeInputWarning(e.target)
            } else {
                addInputWarning(e.target);
            }
        });
    })

    // Integers
    const inputObraAnyPublicacio = formLlibreNou.querySelector("input#obra_any_publicacio");
    const inputLlibrePagines = formLlibreNou.querySelector("input#llibre_pagines");
    const inputIntegers = [inputObraAnyPublicacio, inputLlibrePagines];
    inputIntegers.forEach(inputInteger => {
        inputInteger.addEventListener("input", e => {
            const inputInt = e.target.value;
            if (inputInt.length === 0) {
                removeInputWarning(e.target)
            } else {
                isValidInt(inputInt) ? removeInputWarning(e.target) : addInputWarning(e.target);
            }
        });
    })
}

/**
 * @param {HTMLFormElement} formLlibreNou
 * @returns {FormData|null} 
 */
function validarFormLlibreNou(formLlibreNou) {
    const formData = new FormData(formLlibreNou);
    let invalidData = 0;
}