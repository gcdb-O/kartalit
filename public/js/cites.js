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