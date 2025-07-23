const formAutorNou = document.getElementById("form-autor-nou");
const invalidAutorData = {};
let ordenadorAutomatic = true;
addListenersToAutorNouForm(formAutorNou);

formAutorNou.addEventListener("submit", e => {
    e.preventDefault();
    const formData = validarFormAutorNou(formAutorNou);
    if (formData) {
        fetch(`${BASE_PATH}/api/autor`, {
            method: "POST",
            body: formData
        }).then(res => {
            if (res.status === 201) {
                console.log(res);
                res.json().then(({ data }) => {
                    window.location.href = `${BASE_PATH}/autor/${data.id}`;
                })
            } else {
                reject();
            }
        }).catch(() => {
            alert("Alguna cosa ha fallat i no s'ha pogut crear l'obra.");
        })
    }
})
const inputAutorNom = document.getElementById("autor_nom");
const inputAutorCognoms = document.getElementById("autor_cognoms");
const inputAutorOrdenador = document.getElementById("autor_ordenador");
[inputAutorNom, inputAutorCognoms].forEach(i => {
    i.addEventListener("input", e => {
        if (ordenadorAutomatic) {
            inputAutorOrdenador.value = `${inputAutorCognoms.value} ${inputAutorNom.value}`;
        }
    })
});

/** @param {HTMLFormElement} formAutorNou */
function addListenersToAutorNouForm(formAutorNou) {
    // Titols de menys de 50 caràcters
    const inputAutorNom = formAutorNou.querySelector("input#autor_nom");
    const inputAutorCognoms = formAutorNou.querySelector("input#autor_cognoms");
    const inputAutorPseudonim = formAutorNou.querySelector("input#autor_pseudonim");
    const inputTitols = [inputAutorNom, inputAutorCognoms, inputAutorPseudonim];
    inputTitols.forEach(inputTitol => {
        inputTitol.addEventListener("input", e => {
            const titol = e.target.value;
            if (titol.length <= 50) {
                invalidAutorData[inputTitol.id] = false;
                removeInputWarning(e.target)
            } else {
                invalidAutorData[inputTitol.id] = true;
                addInputWarning(e.target);
            }
        });
    })
}

/**
 * @param {HTMLFormElement} formAutorNou
 * @returns {FormData|null} 
 */
function validarFormAutorNou(formAutorNou) {
    const formData = new FormData(formAutorNou);
    let invalidData = 0;
    Object.entries(invalidAutorData).forEach(([key, value]) => {
        if (value) {
            invalidData++;
            return;
        }
    })
    return invalidData === 0 ? formData : null;
}