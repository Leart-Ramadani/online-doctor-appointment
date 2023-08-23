const addButton = document.querySelector('.addDoc');
const backWrapper = document.querySelector('.back_wrapper');
const popDocAdd = document.querySelector('.popDocAdd');
const closeDocAdd = document.querySelector('.closePopAdd')
const addForm = document.querySelector('.addForm');


const docName = document.querySelector('.name');
const departament = document.querySelector('.departament')
const gender = document.querySelector('.gender');
const email = document.querySelector('.email');
const photo = document.querySelector('.photo');
const phone = document.querySelector('.phone');
const username = document.querySelector('.username');
const password = document.querySelector('.password');


const nameErr = document.querySelector('.nameError');
const departamentErr = document.querySelector('.departamentError');
const genderErr = document.querySelector('.genderError');
const emailErr = document.querySelector('.emailError');
const photoErr = document.querySelector('.photoError');
const phoneErr = document.querySelector('.phoneError');
const usernameErr = document.querySelector('.usernameError');
const passwordErr = document.querySelector('.passwordError');

const registerBtn = document.querySelector('.register');

const popAddDoc = () => {
    backWrapper.style.display = 'block';
    popDocAdd.style.display = 'block';
}
addButton.addEventListener('click', popAddDoc);

const closeAddDoc = () => {
    docName.value = '';
    departament.value = '';
    gender.value = '';
    email.value = '';
    photo.value = '';
    phone.value = '';
    username.value = '';
    password.value = '';

    docName.classList.remove('is-invalid');
    nameErr.innerHTML = "";
    departament.classList.remove('is-invalid');
    departamentErr.innerHTML = "";
    gender.classList.remove('is-invalid');
    genderErr.innerHTML = "";
    email.classList.remove('is-invalid');
    emailErr.innerHTML = "";
    photo.classList.remove('is-invalid');
    photoErr.innerHTML = "";
    phone.classList.remove('is-invalid');
    phoneErr.innerHTML = "";
    username.classList.remove('is-invalid');
    usernameErr.innerHTML = "";
    password.classList.remove('is-invalid');
    passwordErr.innerHTML = "";


    backWrapper.style.display = 'none';
    popDocAdd.style.display = 'none';
}
closeDocAdd.addEventListener('click', closeAddDoc);


const AddDoctor = () => {
    // Create a new FormData object
    let formData = new FormData();
    formData.append('file', photo.files[0]);

    // Append other form data to the FormData object
    formData.append('set', true);
    formData.append('docName', docName.value);
    formData.append('departament', departament.value);
    formData.append('gender', gender.value);
    formData.append('email', email.value);
    formData.append('phone', phone.value);
    formData.append('username', username.value);
    formData.append('password', password.value);

    // Make the AJAX request
    $.ajax({
        url: '../admin/addDoctor.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: response => {
            console.log(response);
            response = JSON.parse(response);
            let nameValid = true;
            let departamentValid = true;
            let genderValid = true;
            let emailValid = true;
            let phoneValid = true;
            let photoValid = true;
            let usernameValid = true;
            let passwordValid = true;

            for (i = 0; i < response.length; i++) {
                // Check name
                if (response[i] == "*Emri duhet plotesuar.") {
                    nameValid = false;
                    docName.classList.add('is-invalid');
                    nameErr.innerHTML = "*Emri duhet plotesuar.";
                } else if (response[i] == "*Nuk lejohen karaktere tjera perveq shkronjave.") {
                    nameValid = false;
                    docName.classList.add('is-invalid');
                    nameErr.innerHTML = "*Nuk lejohen karaktere tjera perveq shkronjave.";
                }

                // Check departament
                if (response[i] == "*Ju duhet te zgjedhni nje departament.") {
                    departamentValid = false;
                    departament.classList.add('is-invalid');
                    departamentErr.innerHTML = "*Ju duhet te zgjedhni nje departament.";
                }

                // Check gender
                if (response[i] == "*Gjinia duhet zgjedhur") {
                    genderValid = false;
                    gender.classList.add('is-invalid');
                    genderErr.innerHTML = "*Gjinia duhet zgjedhur";
                }

                // Check email
                if (response[i] == "*Emaili duhet plotesuar.") {
                    emailValid = false;
                    email.classList.add('is-invalid');
                    emailErr.innerHTML = "*Emaili duhet plotesuar.";
                } else if (response[i] == "*Ky email nuk eshte valid.") {
                    emailValid = false;
                    email.classList.add('is-invalid');
                    emailErr.innerHTML = "*Ky email nuk eshte valid.";
                }

                // Check phone 
                if (response[i] == "*Numri i telefonit duhet vendosur.") {
                    phoneValid = false;
                    phone.classList.add('is-invalid');
                    phoneErr.innerHTML = "*Numri i telefonit duhet vendosur.";
                } else if (response[i] == "*Ky numer i telefonit nuk eshte valid.") {
                    phoneValid = false;
                    phone.classList.add('is-invalid');
                    phoneErr.innerHTML = "*Ky numer i telefonit nuk eshte valid.";
                }

                // Check photo
                if (response[i] == "*Duhet te shtoni nje foto te personit ne fjale.") {
                    photoValid = false;
                    photo.classList.add('is-invalid');
                    photoErr.innerHTML = "*Duhet te shtoni nje foto te personit ne fjale.";
                } else if (response[i] == "*Ky file eshte shume i madh.") {
                    photoValid = false;
                    photo.classList.add('is-invalid');
                    photoErr.innerHTML = "*Ky file eshte shume i madh.";
                } else if (response[i] == "*Ky format nuk eshte valid. Formatet e lejuara(jpg, jpeg, png, gif, webp).") {
                    photoValid = false;
                    photo.classList.add('is-invalid');
                    photoErr.innerHTML = "*Ky format nuk eshte valid. Formatet e lejuara(jpg, jpeg, png, gif, webp).";
                } else if (response[i] == "*Eshte shfaqur nje gabim i panjohur!") {
                    photoValid = false;
                    photo.classList.add('is-invalid');
                    photoErr.innerHTML = "*Eshte shfaqur nje gabim i panjohur!";
                }

                // Check username
                if (response[i] == "*Username duhet plotesuar.") {
                    usernameValid = false;
                    username.classList.add('is-invalid');
                    usernameErr.innerHTML = "*Username duhet plotesuar.";
                }

                // Check password
                if (response[i] == "*Fjalkalimi duhet plotesuar.") {
                    passwordValid = false;
                    password.classList.add('is-invalid');
                    passwordErr.innerHTML = "*Fjalkalimi duhet plotesuar.";
                }

                // Check if its valid
                if (response[i] == "inserted") {
                    popDocAdd.style.width = '400px';
                    popDocAdd.style.height = '270px';

                    popDocAdd.innerHTML = "<h3>Ju lutem prisni...</h3> <br> <div class='loader'></div>";
                    setTimeout(() => {
                        window.location.replace('../admin/doktoret.php');
                    }, 900);
                }
            }

            if (nameValid) {
                nameValid = true;
                docName.classList.remove('is-invalid');
                nameErr.innerHTML = "";
            }

            if (departamentValid) {
                departamentValid = true;
                departament.classList.remove('is-invalid');
                departamentErr.innerHTML = "";
            }

            if (genderValid) {
                genderValid = true;
                gender.classList.remove('is-invalid');
                genderErr.innerHTML = "";
            }

            if (emailValid) {
                emailValid = true;
                email.classList.remove('is-invalid');
                emailErr.innerHTML = "";
            }

            if (phoneValid) {
                phoneValid = true;
                phone.classList.remove('is-invalid');
                phoneErr.innerHTML = "";
            }

            if (photoValid) {
                photoValid = true;
                photo.classList.remove('is-invalid');
                photoErr.innerHTML = "";
            }

            if (usernameValid) {
                usernameValid = true;
                username.classList.remove('is-invalid');
                usernameErr.innerHTML = "";
            }

            if (passwordValid) {
                passwordValid = true;
                password.classList.remove('is-invalid');
                passwordErr.innerHTML = "";
            }

        },
        error: function (xhr, status, error) {
            console.log('Error: ' + error);
        }
    });
};

registerBtn.addEventListener('click', AddDoctor);