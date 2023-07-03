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

    if (n == tab.length - 2) {
        nextBtn.innerHTML = 'Regjistrohu';
        nextBtn.classList.remove('w-25');
        nextBtn.classList.add('w-50');
    } else {
        nextBtn.innerHTML = 'Para';
        nextBtn.classList.remove('w-50');
        nextBtn.classList.add('w-25');
    }

    if (n == (tab.length - 1)) {
        document.querySelector('.register').innerHTML = 'Ju lutemi prisni...'
        document.querySelector('.loginLink').style.display = 'none';
        nextBtn.style.display = 'none'
        prevBtn.style.display = 'none'
    }

}

showTab(tabNumber);

// inputs of the register forms
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

// first tab error spans
const nameError = document.querySelector('.nameErrorr');
const lastNameErr = document.querySelector('.lastNameErr');
const personal_id_err = document.querySelector('.personal_id_err');
const genderErr = document.querySelector('.genderErr');

// second tab error spans
const emailErr = document.querySelector('.emailErr');
const birthdayErr = document.querySelector('.birthdayErr');
const phoneErr = document.querySelector('.phoneErr');
const adressErr = document.querySelector('.adressErr');

// third tab error spans
const usernameErr = document.querySelector('.usernameErr');
const passwordErr = document.querySelector('.passwordErr');
const confirmPassErr = document.querySelector('.confirmPassErr');


// the functions that validates the form
const validate = () => {
    // The first tab validation
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
                            nameError.innerHTML = "*Emri duhet plotesuar!";
                        } else if (response[i] == "*Only alphabetical letters are allowed!") {
                            nameValid = false;
                            namme.classList.add('is-invalid');
                            nameError.innerHTML = "*Nuk lejohen karaktere tjera perveq shkronjave!";
                        }

                        // Validate the last name input
                        if (response[i] == "*Last name must be filled!") {
                            surnameValid = false;
                            lastName.classList.add('is-invalid');
                            lastNameErr.innerHTML = "*Mbiemri duhet plotesuar!";
                        } else if (response[i] == "*Only alphabetical letters are allowed!LastName") {
                            surnameValid = false;
                            lastName.classList.add('is-invalid');
                            lastNameErr.innerHTML = "*Nuk lejohen karaktere tjera perveq shkronjave!";
                        }


                        // Validate the personal id input
                        if (response[i] == "*Personal ID must be filled!") {
                            personalNrValid = false;
                            personal_ID.classList.add('is-invalid');
                            personal_id_err.innerHTML = "*Numri personal duhet plotesuar!";
                        } else if (response[i] == "*Only numbers are allowed!Personal_id") {
                            personalNrValid = false;
                            personal_ID.classList.add('is-invalid');
                            personal_id_err.innerHTML = "*Nuk lejohen karaktere tjera perveq numrave!";
                        } else if (response[i] == "*Personal ID must be 10 characters!") {
                            personalNrValid = false;
                            personal_ID.classList.add('is-invalid');
                            personal_id_err.innerHTML = "Numri personal duhet te jete 10 karaktere!";
                        } else if (response[i] == "*An account already exists using this ID") {
                            personalNrValid = false;
                            personal_ID.classList.add('is-invalid');
                            personal_id_err.innerHTML = "*Nje llogari eshte e hapur me kete numer personal!";
                        } 

                        // Validate the gender input
                        if (response[i] == "*You must select the gender.") {
                            genderValid = false;
                            gender.classList.add('is-invalid');
                            genderErr.innerHTML = "*Zgjedhni gjinin!";
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


    // Valdiate the second tab
    if (tabNumber == 1) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '../patientSide/validateForm.php',
                type: 'POST',
                data: {
                    set: true,
                    id: 2,
                    email: email.value,
                    birthday: birthday.value,
                    phone: phone.value,
                    adress: adress.value
                },
                success: function (response) {
                    response = JSON.parse(response);
                    let emailValid = true;
                    let birthdayValid = true;
                    let phoneValid = true;
                    let addressValid = true;
                    for (i = 0; i < response.length; i++) {
                        // Validate the name input
                        if (response[i] == "*Email must be filled!") {
                            emailValid = false;
                            email.classList.add('is-invalid');
                            emailErr.innerHTML = "*Email duhet plotesuar!";
                        } else if (response[i] == "*The given email is invalid!") {
                            emailValid = false;
                            email.classList.add('is-invalid');
                            emailErr.innerHTML = "*Ky email eshte i pavlefshem!";
                        } else if (response[i] == "*An account already exists using this email!") {
                            emailValid = false;
                            email.classList.add('is-invalid');
                            emailErr.innerHTML = "*Nje llogari eshte e hapur me kete email!";
                        }

                        // Validate the last name input
                        if (response[i] == "*Birthday must be filled.") {
                            birthdayValid = false;
                            birthday.classList.add('is-invalid');
                            birthdayErr.innerHTML = "*Ju duhet te shkruani ditelindjen.";
                        } else if (response[i] == "*You must be 18 years or older.") {
                            birthdayValid = false;
                            birthday.classList.add('is-invalid');
                            birthdayErr.innerHTML = "*Ti duhet te jesh mbi 18 vjet per te hapur nje llogari.";
                        }


                        // Validate the personal id input
                        if (response[i] == "*Phone number must be filled!") {
                            phoneValid = false;
                            phone.classList.add('is-invalid');
                            phoneErr.innerHTML = "*Numri i telefonit duhet plotesuar!";
                        } else if (response[i] == "*The given phone number is invalid!") {
                            phoneValid = false;
                            phone.classList.add('is-invalid');
                            phoneErr.innerHTML = "*The given phone number is invalid!";
                        } else if (response[i] == "*An account already exists using this phone number!") {
                            phoneValid = false;
                            phone.classList.add('is-invalid');
                            phoneErr.innerHTML = "*Nje llogari eshte e hapur me kete numer telefoni!";
                        }

                        // Validate the gender input
                        if (response[i] == "*Adress must be filled!") {
                            addressValid = false;
                            adress.classList.add('is-invalid');
                            adressErr.innerHTML = "*Adresa duhet plotesuar!";
                        }


                    }
                    if (emailValid) {
                        email.classList.remove('is-invalid');
                        emailErr.innerHTML = "";
                    }
                    if (birthdayValid) {
                        birthday.classList.remove('is-invalid');
                        birthdayErr.innerHTML = "";
                    }
                    if (phoneValid) {
                        phone.classList.remove('is-invalid');
                        phoneErr.innerHTML = "";
                    }
                    if (addressValid) {
                        adress.classList.remove('is-invalid');
                        adressErr.innerHTML = "";
                    }

                    if (emailValid && birthdayValid && phoneValid && addressValid) {
                        resolve(true);
                    } else {
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


    // Valdiate the third tab
    if (tabNumber == 2) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '../patientSide/validateForm.php',
                type: 'POST',
                data: {
                    set: true,
                    id: 3,
                    username: username.value,
                    password: password.value,
                    confirmPass: confirmPass.value
                },
                success: function (response) {
                    response = JSON.parse(response);
                    let usernameValid = true;
                    let passwordValid = true;
                    let confirmPassValid = true;

                    for (i = 0; i < response.length; i++) {
                        // Validate the username input
                        if (response[i] == "*Username must be filled!") {
                            usernameValid = false;
                            username.classList.add('is-invalid');
                            usernameErr.innerHTML = "*Username duhet plotesuar!";
                        } else if (response[i] == "*An account already exists using this username!") {
                            usernameValid = false;
                            email.classList.add('is-invalid');
                            emailErr.innerHTML = "*Nje llogari eshte e hapur me kete username!";
                        }

                        // Validate the password input
                        if (response[i] == "*Password must be filled!") {
                            passwordValid = false;
                            password.classList.add('is-invalid');
                            passwordErr.innerHTML = "*Fjalkalimi duhet plotesuar!";
                        } 


                        // Validate the confirm password input
                        if (response[i] == "*You must confirm your password!") {
                            confirmPassValid = false;
                            confirmPass.classList.add('is-invalid');
                            confirmPassErr.innerHTML = "*Ju duhet te konfirmoni fjalkalimin!";
                        } else if (response[i] == "*Password doesn't match!") {
                            confirmPassValid = false;
                            confirmPass.classList.add('is-invalid');
                            confirmPassErr.innerHTML = "*Fjalkalimet nuk perputhen!";
                        }




                    }
                    if (usernameValid) {
                        username.classList.remove('is-invalid');
                        usernameErr.innerHTML = "";
                    }
                    if (passwordValid) {
                        password.classList.remove('is-invalid');
                        passwordErr.innerHTML = "";
                    }
                    if (confirmPassValid) {
                        confirmPass.classList.remove('is-invalid');
                        confirmPassErr.innerHTML = "";
                    }


                    if (usernameValid && passwordValid && confirmPassValid) {
                        resolve(true);
                    } else {
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
        if (!isValid) {
            return false;
        }

        tab[tabNumber].style.display = 'none';
        tabNumber++;

        if(tabNumber == tab.length - 1){
            $.ajax({
                url: '../patientSide/validateForm.php',
                type: 'POST',
                data: {
                    set: true,
                    id: 4,
                    name: namme.value,
                    lastName: lastName.value,
                    personal_ID: personal_ID.value,
                    gender: gender.value,
                    email: email.value,
                    birthday: birthday.value,
                    phone: phone.value,
                    adress: adress.value,
                    username: username.value,
                    password: password.value,
                    confirmPass: confirmPass.value,
                },
                success: function (response) {  
                    console.log(response);
                    if (response == "Registerd"){
                        window.location.replace('../patientSide/emailVerification.php');
                    } else if (response == "Something went wrong"){
                        alert('Probleme me rrjetin ose serverin');
                    }

                },
                error: function (xhr, status, error) {
                    console.log("AJAX Error: " + error);
                }
            });
        }

        showTab(tabNumber);


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
