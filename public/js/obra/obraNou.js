const formObraNou = document.getElementById("form-obra-nou");
const invalidObraData = {};
addListenersToObraNouForm(formObraNou);

formObraNou.addEventListener("submit", e => {
    e.preventDefault();
    const formData = validarFormObraNou(formObraNou);
    if (formData) {
        fetch(`${BASE_PATH}/api/obra`, {
            method: "POST",
            body: formData
        }).then(res => {
            if (res.status === 201) {
                console.log(res);
                res.json().then(({ data }) => {
                    window.location.href = `${BASE_PATH}/obra/${data.id}`;
                })
            } else {
                reject();
            }
        }).catch(() => {
            alert("Alguna cosa ha fallat i no s'ha pogut crear l'obra.");
        })
    }
})

/** @param {HTMLFormElement} formObraNou */
function addListenersToObraNouForm(formObraNou) {
    // Titols de menys de 150 caràcters
    const inputTitolOriginal = formObraNou.querySelector("input#obra_titol_original");
    const inputTitolCatala = formObraNou.querySelector("input#obra_titol_catala");
    const inputTitols = [inputTitolOriginal, inputTitolCatala];
    inputTitols.forEach(inputTitol => {
        inputTitol.addEventListener("input", e => {
            const titol = e.target.value;
            if (titol.length <= 150) {
                invalidObraData[inputTitol.id] = false;
                removeInputWarning(e.target)
            } else {
                invalidObraData[inputTitol.id] = true;
                addInputWarning(e.target);
            }
        });
    })

    // Integers
    const inputObraAnyPublicacio = formObraNou.querySelector("input#obra_any_publicacio");
    const inputIntegers = [inputObraAnyPublicacio];
    inputIntegers.forEach(inputInteger => {
        inputInteger.addEventListener("input", e => {
            const inputInt = e.target.value;
            if (inputInt.length === 0) {
                invalidObraData[inputInteger.id] = false;
                removeInputWarning(e.target)
            } else {
                if (isValidInt(inputInt)) {
                    invalidObraData[inputInteger.id] = false;
                    removeInputWarning(e.target)
                } else {
                    invalidObraData[inputInteger.id] = true;
                    addInputWarning(e.target);
                }
            }
        });
    })
}

/**
 * @param {HTMLFormElement} formObraNou
 * @returns {FormData|null} 
 */
function validarFormObraNou(formObraNou) {
    const formData = new FormData(formObraNou);
    let invalidData = 0;
    Object.entries(invalidObraData).forEach(([key, value]) => {
        if (value) {
            invalidData++;
            return;
        }
    })
    formData.entries().forEach(([key, value]) => {
        if (value === "") {
            formData.delete(key);
        }
    })
    return invalidData === 0 ? formData : null;
}