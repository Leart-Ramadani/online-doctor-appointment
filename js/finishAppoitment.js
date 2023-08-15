const diagnoseSelected = document.querySelector('.diagnose-selected');
const diagnoseInp = document.querySelector('.diagnose-input');
const diagnoseContent = document.querySelector('.diagnose-content');
const diagnoseOptions = document.querySelectorAll('.options  li');
const options = document.querySelector('.options');
const searchDiagnose = document.querySelector('.searchDiagnose');



diagnoseSelected.addEventListener('click', () => {
    diagnoseContent.classList.toggle('diagnose-active');
});

document.querySelector('.diagnose-options').addEventListener('click', event => {
    const target = event.target;
    if (target.tagName === 'LI') {
        diagnoseInp.value = target.textContent;
        diagnoseContent.classList.remove('diagnose-active');
    }
});


searchDiagnose.addEventListener('keyup', () => {
    if (searchDiagnose.value.length >= 2) {
        let filter, li, i, textValue;
        filter = searchDiagnose.value.toUpperCase();
        li = options.getElementsByTagName('li');
        for (i = 0; i < li.length; i++) {
            liCount = li[i];
            textValue = liCount.textContent || liCount.innerText;
            if (textValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = '';
            } else {
                li[i].style.display = 'none';
            }
        }

        $.ajax({
            url: 'icd_code.php',
            type: 'POST',
            data: {
                filter: filter
            },
            success: response => {
                response = JSON.parse(response);
                if (response == 'not found') {
                    options.innerText = "Code like this doesn't exists in our system";
                } else {

                    options.innerHTML = '';
                    response.forEach(code => {
                        const createLi = document.createElement('li');
                        createLi.textContent = code;
                        options.appendChild(createLi);
                    });
                }
            }
        })
    } else if (searchDiagnose.value.length < 2) {
        li = options.getElementsByTagName('li');
        for (i = 0; i < li.length; i++) {
            li[i].style.display = 'none';
        }
    }


});


const service = document.querySelector('.service');
const diagnoseWrapper = document.querySelector('.diagnose');
const departament = document.querySelector('.departament');

let departamentActive = false;
service.addEventListener('change', () => {
    if(service.value == 'Reference'){
        departamentActive = true;
        diagnoseWrapper.classList.add('d-none');
        departament.classList.remove('d-none');
    } else{
        departamentActive = false;
        diagnoseWrapper.classList.remove('d-none');
        departament.classList.add('d-none');
    }
});



const finishAppointment_btn = document.querySelector('.finishAppointment_btn');
const departamentInp = document.querySelector('.departamentInp');
const prescription = document.querySelector('.prescription');
const appID = document.querySelector('.app-ID');

const serviceErr = document.querySelector('.serviceErr');
const diagnoseErr = document.querySelector('.diagnoseErr');
const prescriptionErr = document.querySelector('.prescriptionErr');
const departamentErr = document.querySelector('.departamentErr');

const finshAppointment = () => {
    if(!departamentActive){
        $.ajax({
            url: '../doctorSide/finishLogic.php',
            type: 'POST',
            data:{
                id: appID.textContent,
                diagnose: diagnoseInp.value,
                service: service.value,
                prescription: prescription.value
            },
            success: response => {
                response = JSON.parse(response);
                let valid = true;
                for(let i=0; i < response.length; i++){
                    if (response.includes('*Service must be selected')){
                        service.classList.add('is-invalid');
                        serviceErr.textContent = '*Service must be selected';
                        valid = false;
                    } else{
                        service.classList.remove('is-invalid');
                        serviceErr.textContent = '';
                        valid = true;
                    }

                    if (response.includes('*Diagnose must be filled.')){
                        diagnoseInp.classList.add('is-invalid');
                        diagnoseErr.textContent = '*Diagnose must be filled.';
                        valid = false;
                    } else{
                        diagnoseInp.classList.remove('is-invalid');
                        diagnoseErr.textContent = '';
                        valid = true;
                    }

                    if (response.includes('*Prescription must be filled.')){
                        prescription.classList.add('is-invalid');
                        prescriptionErr.textContent = '*Prescription must be filled.';
                        valid = false;
                    } else{
                        prescription.classList.remove('is-invalid');
                        prescriptionErr.textContent = '';
                        valid = true;
                    }
                }
                if(valid){
                    finishAppointment_btn.classList.add('disabled');
                    window.location.replace('../doctorSide/terminet.php');
                }
            }
        });
    } else{
        $.ajax({
            url: '../doctorSide/finishLogic.php',
            type: 'POST',
            data: {
                id: appID.textContent,
                departament: departamentInp.value,
                service: service.value,
                prescription: prescription.value
            },
            success: response => {
                response = JSON.parse(response);
                let valid = true;
                for (let i = 0; i < response.length; i++) {
                    if (response.includes('*Service must be selected')) {
                        service.classList.add('is-invalid');
                        serviceErr.textContent = '*Service must be selected';
                        valid = false;
                    } else {
                        service.classList.remove('is-invalid');
                        serviceErr.textContent = '';
                        valid = true;
                    }

                    if (response.includes('*Departament must be selected')) {
                        departamentInp.classList.add('is-invalid');
                        departamentErr.textContent = '*Departament must be selected';
                        valid = false;
                    } else {
                        departamentInp.classList.remove('is-invalid');
                        departamentErr.textContent = '';
                        valid = true;
                    }

                    if (response.includes('*Prescription must be filled.')) {
                        prescription.classList.add('is-invalid');
                        prescriptionErr.textContent = '*Prescription must be filled.';
                        valid = false;
                    } else {
                        prescription.classList.remove('is-invalid');
                        prescriptionErr.textContent = '';
                        valid = true;
                    }
                }
                if (valid) {
                    finishAppointment_btn.classList.add('disabled');
                    window.location.replace('../doctorSide/terminet.php');
                }
            }
        });
    }
}

finishAppointment_btn.addEventListener('click', finshAppointment);