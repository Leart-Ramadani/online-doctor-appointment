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


const personalID = document.querySelector('.personal_id');
// Modal data
const appointmentId = document.querySelector('.appointmentId');
const patientName = document.querySelector('.patient');
const patientID = document.querySelector('.patientID');
const app_doctor = document.querySelector('.app_doctor');
const app_departament = document.querySelector('.app_departament');
const app_date = document.querySelector('.app_date');
const app_time = document.querySelector('.app_time');

const bookingBody = document.querySelector('.bookingBody');
const bookBtn = document.querySelector('.bookBtn');
const closeModal = document.querySelector('.closeModal');
const closeModal1 = document.querySelector('.closeModal1');


const getValue = value => {
    let selectedTime = value;

    $.ajax({
        url: '../doctorSide/referenceLogic.php',
        type: 'POST',
        data: {
            action: 'showAppointment',
            doctor: doctor.value,
            date: avail_select.value,
            time: selectedTime,
            personal_id: personalID.innerText
        }, 
        success: response => {
            response = JSON.parse(response);

            patientName.innerText = response.Patient;
            patientID.innerText = response.PersonalID;
            app_doctor.innerText = response.Doctor;
            app_departament.innerText = response.Departament;
            app_date.innerText = response.Date;
            app_time.innerText = response.Time;

        }
    });


    const bookAppointment = () => {
        bookBtn.disabled = 'true';
        closeModal.classList.add('disabled');
        closeModal1.classList.add('disabled');
        bookBtn.innerHTML = "<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span> Loading...";

        $.ajax({
            url: '../doctorSide/referenceLogic.php',
            type: 'POST',
            data: {
                action: 'bookAppointment',
                doctor: doctor.value,
                date: avail_select.value,
                time: selectedTime,
                personal_id: personalID.innerText,
                appointmentId: appointmentId.innerText
            }, 
            success: response => {
                if(response.includes("booked")){
                    window.location.replace('../admin/references.php');
                } else{

                }
            }

        });
    }

    bookBtn.addEventListener('click', bookAppointment);
}





