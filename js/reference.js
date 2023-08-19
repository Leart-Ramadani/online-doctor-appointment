const doctor = document.querySelector('.doctor');
const avail_date = document.querySelector('.avail_date');
const avail_select = document.querySelector('.avail_select');
const appointmentTimeWrapper = document.querySelector('.appointmentTimeWrapper');
const appointmentTime = document.querySelector('.appointmentTime');


doctor.addEventListener('change', () => {
    if(doctor.value.length != 0){
        avail_date.classList.remove('d-none');
        $.ajax({
            url: '../doctorSide/referenceLogic.php',
            type: 'POST',
            data: {
                action: 'getDates',
                doctor: doctor.value
            },
            success: response => {
                response = JSON.parse(response);
                avail_select.innerHTML = '<option value="">Choose available date</option>';
                response.forEach(date => {
                    let optionElement = document.createElement('option');
                    optionElement.textContent = date;
                    avail_select.appendChild(optionElement);
                });
            }
        });
    } else{
        avail_date.classList.add('d-none');
        appointmentTimeWrapper.classList.add('d-none');
    }
});

avail_select.addEventListener('change', () => {
    if(avail_select.value.length != 0){ 
        appointmentTimeWrapper.classList.remove('d-none');

        $.ajax({
            url: '../doctorSide/referenceLogic.php',
            type: 'POST',
            data: {
                action: 'getTimes',
                doctor: doctor.value,
                date: avail_select.value
            },
            success: response => {
                appointmentTime.innerHTML = response;
            }
        });
    } else{
        appointmentTimeWrapper.classList.add('d-none');
    }
});

const getValue = value => {
    let selectedTime = value;

    $.ajax({
        url: '../doctorSide/referenceLogic.php',
        type: 'POST',
        data: {
            action: 'showAppointment',
            doctor: doctor.value,
            date: avail_select.value,
            time: selectedTime
        }, 
        success: response => {
            
        }
    });
    // const bookAppointment = () => {
    //     bookBtn.disabled = 'true';
    //     closeModal.classList.add('disabled');
    //     closeModal1.classList.add('disabled');
    //     bookBtn.innerHTML = "<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span> Loading...";

    //     $.ajax({
    //         url: '../doctorSide/referenceLogic.php',
    //         type: 'POST',
    //         data: {
    //             action: 'bookAppointment',
    //             doctor: doctor.value,
    //             date: avail_select.value,
    //             time: selectedTime
    //         }, 
    //         success: response => {
                
    //         }

    //     });
    // }
}

const bookingBody = document.querySelector('.bookingBody');
const bookBtn = document.querySelector('.bookBtn');
const closeModal = document.querySelector('.closeModal');
const closeModal1 = document.querySelector('.closeModal1');



bookBtn.addEventListener('click', bookAppointment);