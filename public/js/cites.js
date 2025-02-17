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