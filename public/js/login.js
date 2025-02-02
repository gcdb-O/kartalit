const formLogin = document.getElementById('formLogin');

formLogin.addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = new FormData(formLogin);

    fetch('./api/login', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = './';
            } else {
                alert(data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
});