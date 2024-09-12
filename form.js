document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('userForm');
    const errorDiv = document.getElementById('error');

    form.addEventListener('submit', function(event){
        event.preventDefault();
        errorDiv.innerHTML = '';
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const course = document.querySelector('input[name="course"]:checked');
        const photo = document.getElementById('photo').files[0];

        const errors = [];
        if(name === '') {
            errors.push('A név megadása kötelező!');
        }

        const emailPattern = /^[a-zA-Z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,4}$/;
        if(!emailPattern.test(email)) {
            errors.push('Hibás e-mail cím!');
        }

        if (phone.length < 6) {
            errors.push('Hibás telefonszám!');
        }

        if (!course) {
            errors.push('Kérem válasszon képzést!');
        }

        if(errors.length > 0){
            errorDiv.innerHTML = errors.join('<br>');
        }
        else{
            const formData = new FormData();
            formData.append('name', name);
            formData.append('email', email);
            formData.append('phone', phone);
            formData.append('course', course.value);
            if(photo) {
                formData.append('photo', photo);
            }
            console.log(formData);
        
            fetch("form.php", {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                errorDiv.innerHTML = data;
            })
            .catch(error => {
                console.error('Hiba:', error);
                errorDiv.innerHTML = 'Hiba';
                error.message
            });
        }

    })
});