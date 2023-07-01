const tab = document.querySelectorAll('.tab');
const prevBtn = document.querySelector('.prev');
const nextBtn = document.querySelector('.next');
const regForm = document.getElementById('registerForm');

let tabNumber = 0;

const showTab = n => {
    tab[n].style.display = 'block';

    if (n == 0) {
        prevBtn.style.display = 'none';
    } else {
        prevBtn.style.display = 'block';
    }

    if (n == tab.length - 1) {
        nextBtn.innerHTML = 'Register';
        nextBtn.classList.remove('w-25');
        nextBtn.classList.add('w-50');
    } else {
        nextBtn.innerHTML = 'Next';
        nextBtn.classList.remove('w-50');
        nextBtn.classList.add('w-25');
    }
}

showTab(tabNumber);

const namme = document.querySelector('.name');
const lastName = document.querySelector('.lastName');
const personal_ID = document.querySelector('.personal_ID')
const gender = document.querySelector('.gender');
const email = document.querySelector('.email');
const birthday = document.querySelector('.birthday');
const phone = document.querySelector('.phone');
const adress = document.querySelector('.adress');
const username = document.querySelector('.username');
const password = document.querySelector('.password');
const confirmPass = document.querySelector('.confirmPass');

const nameError = document.querySelector('.nameErrorr');
const lastNameErr = document.querySelector('.lastNameErr');
const personal_id_err = document.querySelector('.personal_id_err');
const genderErr = document.querySelector('.genderErr');

const validate = () => {
    if (tabNumber == 0) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '../patientSide/validateForm.php',
                type: 'POST',
                data: {
                    set: true,
                    id: 1,
                    name: namme.value,
                    lastName: lastName.value,
                    personal_ID: personal_ID.value,
                    gender: gender.value
                },
                success: function (response) {
                    response = JSON.parse(response);
                    let nameValid = true;
                    let surnameValid = true;
                    let personalNrValid = true;
                    let genderValid = true;
                    for (i = 0; i < response.length; i++) {
                        // Validate the name input
                        if (response[i] == "*Name must be filled!") {
                            nameValid = false;
                            namme.classList.add('is-invalid');
                            nameError.innerHTML = "*Name must be filled!";
                        } else if (response[i] == "*Only alphabetical letters are allowed!") {
                            nameValid = false;
                            namme.classList.add('is-invalid');
                            nameError.innerHTML = "*Only alphabetical letters are allowed!";
                        }

                        // Validate the last name input
                        if (response[i] == "*Last name must be filled!") {
                            surnameValid = false;
                            lastName.classList.add('is-invalid');
                            lastNameErr.innerHTML = "*Last name must be filled!";
                        } else if (response[i] == "*Only alphabetical letters are allowed!LastName") {
                            surnameValid = false;
                            lastName.classList.add('is-invalid');
                            lastNameErr.innerHTML = "*Only alphabetical letters are allowed!";
                        }


                        // Validate the personal id input
                        if (response[i] == "*Personal ID must be filled!") {
                            personalNrValid = false;
                            personal_ID.classList.add('is-invalid');
                            personal_id_err.innerHTML = "*Personal ID must be filled!";
                        } else if (response[i] == "*Only numbers are allowed!Personal_id") {
                            personalNrValid = false;
                            personal_ID.classList.add('is-invalid');
                            personal_id_err.innerHTML = "*Only numbers are allowed!";
                        } else if (response[i] == "*Personal ID must be 10 characters!") {
                            personalNrValid = false;
                            personal_ID.classList.add('is-invalid');
                            personal_id_err.innerHTML = "*Personal ID must be 10 characters!";
                        } else if (response[i] == "*An account already exists using this ID") {
                            personalNrValid = false;
                            personal_ID.classList.add('is-invalid');
                            personal_id_err.innerHTML = "*An account already exists using this ID";
                        } 

                        // Validate the gender input
                        if (response[i] == "*You must select the gender.") {
                            genderValid = false;
                            gender.classList.add('is-invalid');
                            genderErr.innerHTML = "*You must select the gender.";
                        } 


                    }
                    if (nameValid) {
                        namme.classList.remove('is-invalid');
                        nameError.innerHTML = "";
                    } 
                    if(surnameValid){
                        lastName.classList.remove('is-invalid');
                        lastNameErr.innerHTML = "";
                    }
                    if (personalNrValid) {
                        personal_ID.classList.remove('is-invalid');
                        personal_id_err.innerHTML = "";
                    }
                    if (genderValid) {
                        gender.classList.remove('is-invalid');
                        genderErr.innerHTML = "";
                    }

                    if(nameValid && surnameValid && personalNrValid && genderValid){
                        resolve(true);
                    } else{
                        resolve(false);
                    }
                },
                error: function (xhr, status, error) {
                    console.log("AJAX Error: " + error);
                    reject(error); // Reject the promise with the error message
                }
            });
        });
    }
};

const nextTab = () => {
    validate().then(function (isValid) {
        if (isValid) {
            tab[tabNumber].style.display = 'none';
            tabNumber++;
            showTab(tabNumber);
        }
    }).catch(function (error) {
        console.log("Error: " + error);
    });
};

nextBtn.addEventListener('click', nextTab);

const prevTab = () => {
    tab[tabNumber].style.display = 'none';
    tabNumber--;

    showTab(tabNumber);
}

prevBtn.addEventListener('click', prevTab);
