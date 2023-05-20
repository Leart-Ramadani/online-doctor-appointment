// Pop up window for view doctor proflie
const showPopButtons = document.querySelectorAll('.showPop');
const popDoc = document.getElementById('popDoc');
const popDocInfo = document.getElementById('popDoc_info');
const popClose = document.querySelector('.close');

const showHideWindow = () => {
    showPopButtons.forEach((button) => {
        button.addEventListener('click', () => {
            popDocInfo.classList.add('popWin');
            popDoc.classList.add('popWin');
        });
    });

    popClose.addEventListener('click', () => {
        popDocInfo.classList.remove('popWin');
        popDoc.classList.remove('popWin');
    });
};

showHideWindow();


// Send ajax request to display the doctor data
$(document).ready(function () {
    $('.showPop').on('click', function (r) {
        r.preventDefault();

        let idShow = $(this).closest('tr').find('.idShow').text();

        $.ajax({
            type: "POST",
            url: "showDocInfo.php",
            data: {
                popShow: true,
                idShow: idShow
            },
            success: function (response) {
                $('.doc_wrapper').html(response);
            }
        });
    });
});
