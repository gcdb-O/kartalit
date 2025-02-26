const nouBibliotecaDiv = document.getElementById("nou_biblioteca");

if (nouBibliotecaDiv) {
    nouBibliotecaDiv.addEventListener("click", () => {
        fetch(`${BASE_PATH}/api/biblioteca/llibre/${llibreId}`, {
            method: "POST"
        })
            .then(res => {
                if (res.status === 201) {
                    window.location.reload();
                } else {
                    reject();
                }
            })
            .catch(() => {
                alert("Alguna cosa ha fallat i no s'ha pogut afehir el llibra a la biblioteca.");
            })
    });
}