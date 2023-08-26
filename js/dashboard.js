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


const getId = value => {
    const id = value;
    const tableBody = document.querySelector('.docTableBody');

    const doctorWork = document.querySelector('.doctorWork');
    const workNotFound = document.querySelector('.workNotFound');
    const loader = document.querySelector('.loaderWrapper');
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
                loader.classList.add('d-none');
                doctorWork.classList.add('d-none');
                workNotFound.classList.remove('d-none');
                document.querySelector('.workNotFound > h3').innerText = 'Data not found.';
            } else {
                loader.classList.add('d-none');
                doctorWork.classList.remove('d-none');
                workNotFound.classList.add('d-none');

                tableBody.innerHTML = '';
                response.forEach(element => {
                    const row = document.createElement('tr');

                    const patient = document.createElement('td');
                    const personal_id = document.createElement('td');
                    const date = document.createElement('td');
                    const time = document.createElement('td');
                    
                    

                    patient.innerText = element.pacienti;
                    personal_id.innerText = element.numri_personal;
                    date.innerText = element.data,
                    time.innerText = element.ora;

                    row.append(patient, personal_id, date, time);

                    tableBody.append(row);
                });
            }
        }
    });

}

