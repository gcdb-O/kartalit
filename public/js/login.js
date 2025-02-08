const formLogin = document.getElementById('formLogin');

formLogin.addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = new FormData(formLogin);

    fetch(`${BASE_PATH}/api/auth/login`, {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (response.status === 204) {
                window.location.replace('./');
                return;
            }
            return response.json()
        })
        .then(data => alert(data.message))
        .catch(error => {
            console.error('Error:', error);
        });
});