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

service.addEventListener('change', () => {
    if(service.value == 'Reference'){
        diagnoseWrapper.classList.add('d-none');
        departament.classList.remove('d-none');
    } else{
        diagnoseWrapper.classList.remove('d-none');
        departament.classList.add('d-none');
    }
});