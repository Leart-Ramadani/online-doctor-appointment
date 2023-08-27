document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('myChart');

    const getAppointmentsData = () => {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '../admin/dashboardLogic.php',
                type: 'POST',
                data: {
                    action: 'getAppointmentsData'
                },
                success: response => {
                    response = JSON.parse(response);
                    resolve(response);
                },
                error: (xhr, status, error) => {
                    console.log("AJAX Error: " + error);
                    reject(error);
                }
            });
        });
    }

    // Fetch the appointment data and then initialize the chart
    getAppointmentsData().then(data => {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [
                    'Transfered',
                    'Booked',
                    'Completed',
                    'In progres',
                    'Canceled'
                ],
                datasets: [{
                    label: 'nr. of appointments',
                    data: [
                        data.Transfered,
                        data.Booked,
                        data.Completed,
                        data.Progres,
                        data.Canceled
                    ],
                    borderWidth: 1,
                    hoverOffset: 4
                }]
            }
        });
    }).catch(error => {
        // Handle any errors that occurred during data retrieval
        console.error(error);
    });

});


const formatDate = inputDate => {
    const parts = inputDate.split('-'); // Split the input date by hyphens
    const formattedDate = `${parts[1]}/${parts[2]}/${parts[0]}`; // Rearrange the parts in the desired format
    return formattedDate;
}

const formatTime = inputTime => {
    const parts = inputTime.split(':');
    const formatedTime = `${parts[0]}:${parts[1]}`;
    return formatedTime;
}





const getId = value => {
    const id = value;
    const tableBody = document.querySelector('.docTableBody');

    const doctorWork = document.querySelector('.doctorWork');
    const workNotFound = document.querySelector('.workNotFound');
    const loader = document.querySelector('.loaderWrapper');

    const appDash_id = document.querySelector('.appDash_id');
    const dashDoctor = document.querySelector('.dashDoctor');
    const dashDepartament = document.querySelector('.dashDepartament');
    const dash_personal_id = document.querySelector('.dash_personal_id');
    const dashPatient = document.querySelector('.dashPatient');
    const dashDate = document.querySelector('.dashDate');
    const dashTime = document.querySelector('.dashTime');
    const dashService = document.querySelector('.dashService');
    const dashPrice = document.querySelector('.dashPrice');
    const dashDiagnose = document.querySelector('.dashDiagnose');
    const dashPrescription = document.querySelector('.dashPrescription');


    loader.classList.remove('d-none');
    workNotFound.classList.add('d-none');
    doctorWork.classList.add('d-none');

    $.ajax({
        url: '../admin/dashboardLogic.php',
        type: 'POST',
        data: {
            action: 'getDocWork',
            id: id
        },
        success: response => {
            response = JSON.parse(response);
            if (response.length == 0) {
                setTimeout(() => {

                    loader.classList.add('d-none');
                    doctorWork.classList.add('d-none');
                    workNotFound.classList.remove('d-none');
                    document.querySelector('.workNotFound > h3').innerText = 'Data not found.';
                }, 80)
            } else {
                setTimeout(() => {

                    loader.classList.add('d-none');
                    doctorWork.classList.remove('d-none');
                    workNotFound.classList.add('d-none');
                }, 80);

                tableBody.innerHTML = '';
                response.forEach(element => {
                    const row = document.createElement('tr');
                    row.setAttribute('data-bs-toggle', 'modal');
                    row.setAttribute('data-bs-target', '#appDetDashboard');

                    const patient = document.createElement('td');
                    const personal_id = document.createElement('td');
                    const date = document.createElement('td');
                    const time = document.createElement('td');



                    row.id = element.id;
                    row.addEventListener('click', () => {



                        $.ajax({
                            url: '../admin/dashboardLogic.php',
                            type: 'POST',
                            data: {
                                action: 'popUpApp',
                                id: element.id
                            },
                            success: appointmentResponse => {
                                appointmentResponse = JSON.parse(appointmentResponse);
                                appDash_id.innerText = appointmentResponse.app_id;
                                dashDoctor.value = appointmentResponse.Doctor;
                                dashDepartament.value = appointmentResponse.Departament;
                                dash_personal_id.value = appointmentResponse.Personal_ID;
                                dashPatient.value = appointmentResponse.Patient;
                                dashDate.value = appointmentResponse.Date;
                                dashTime.value = appointmentResponse.Time;
                                dashService.value = appointmentResponse.Service;
                                dashPrice.value = appointmentResponse.Price;
                                dashDiagnose.value = appointmentResponse.Diagnose;
                                dashPrescription.value = appointmentResponse.Prescription;
                            },
                            error: (xhr, status, error) => {
                                console.log("AJAX Error: " + error);
                            }
                        });
                    });
                    patient.innerText = element.pacienti;
                    personal_id.innerText = element.numri_personal;
                    const formattedDate = formatDate(element.data);
                    date.innerText = formattedDate;
                    const formatedTime = formatTime(element.ora)
                    time.innerText = formatedTime;

                    row.append(patient, personal_id, date, time);

                    tableBody.append(row);
                });
            }
        }
    });

}