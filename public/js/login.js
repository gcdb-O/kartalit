const formLogin = document.getElementById('formLogin');

formLogin.addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = new FormData(formLogin);

    fetch('./api/login', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (response.status === 204) {
                window.location.href = './';
                return;
            }
            return response.json()
        })
        .then(data => alert(data.message))
        .catch(error => {
            console.error('Error:', error);
        });
});