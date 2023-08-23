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
                    label: '# of appointments',
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