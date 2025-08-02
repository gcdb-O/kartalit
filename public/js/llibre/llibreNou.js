const formLlibreNou = document.getElementById("form-llibre-nou");
const invalidLlibreData = {};
addListenersToLlibreNouForm(formLlibreNou);
formLlibreNou.addEventListener("submit", (e) => {
    e.preventDefault();
    const formData = validarFormLlibreNou(formLlibreNou);
    let statusRes;
    if (formData) {
        fetch(`${BASE_PATH}/api/llibre`, {
            method: "POST",
            body: formData
        }).then(res => {
            statusRes = res.status;
            return res.json();
        }).then(({ data = "" }) => {
            if (statusRes === 201) {
                window.location.href = `${BASE_PATH}/llibre/${data.llibre}`;
            } else {
                alert("Alguna cosa ha fallat i no s'ha pogut crear el llibre. " + JSON.stringify(data));
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
                invalidLlibreData[inputTitol.id] = false;
                removeInputWarning(e.target);
            } else {
                invalidLlibreData[inputTitol.id] = true;
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
                invalidLlibreData[inputInteger.id] = false;
                removeInputWarning(e.target);
            } else {
                if (isValidInt(inputInt)) {
                    invalidLlibreData[inputInteger.id] = false;
                    removeInputWarning(e.target);
                } else {
                    invalidLlibreData[inputInteger.id] = true;
                    addInputWarning(e.target);
                }
            }
        });
    })

    // Selects
    const inputLlibreIdioma = formLlibreNou.querySelector("select#llibre_idioma");
    inputLlibreIdioma.addEventListener("change", e => {
        if (e.target.value !== "") {
            invalidLlibreData[inputLlibreIdioma.id] = false;
            removeInputWarning(e.target);
        } else {
            invalidLlibreData[inputLlibreIdioma.id] = true;
            addInputWarning(e.target);
        }
    })
}

/**
 * @param {HTMLFormElement} formLlibreNou
 * @returns {FormData|null} 
 */
function validarFormLlibreNou(formLlibreNou) {
    const formData = new FormData(formLlibreNou);
    let invalidData = 0;

    // Camps obligatoris
    if (!formData.get("titol")) {
        invalidLlibreData["llibre_titol"] = true;
        addInputWarning(document.getElementById("llibre_titol"));
    }
    if (!formData.get("idioma")) {
        invalidLlibreData["llibre_idioma"] = true;
        addInputWarning(document.getElementById("llibre_idioma"));
    }

    Object.entries(invalidLlibreData).forEach(([key, value]) => {
        if (value) {
            invalidData++;
            return;
        }
    })

    formData.entries().forEach(([key, value]) => {
        if (!value || value === "") {
            formData.delete(key);
        }
    })

    return invalidData === 0 ? formData : null;
}