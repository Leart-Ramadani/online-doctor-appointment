<?php
include('../config.php');

if (!isset($_SESSION['verify'])) {
    header("Location: signup.php");
}

$sql = "SELECT verificated FROM users WHERE username = :username";
$prep = $con->prepare($sql);
$prep->bindParam(':username', $_SESSION['verify']);
$prep->execute();
$data = $prep->fetch();

if ($data['verificated'] == true) {
    echo "<script>
        alert('Your account has been verified!');
        window.location.replace('../patientSide/rezervoTermin.php');
        </script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificate</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <link rel="shortcut icon" href="../photos/icon-hospital.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

</head>

<body style="background-color: #f5f5f5;">
    <main class="mainVeriCode">
        <form autocomplete="off" class="veri_form">
            <div class="veri-form-wrapper d-flex flex-column align-items-center">
                <h1>Verify your account</h1>
                <p>Write down the code that was sent in your email!</p>
                <div>
                    <input class="veri_code" type="text" maxlength="1" placeholder="0" id="otp1">
                    <input class="veri_code" type="text" maxlength="1" placeholder="0" id="otp2">
                    <input class="veri_code" type="text" maxlength="1" placeholder="0" id="otp3">
                    <input class="veri_code" type="text" maxlength="1" placeholder="0" id="otp4">
                    <input class="veri_code" type="text" maxlength="1" placeholder="0" id="otp5">
                    <input class="veri_code" type="text" maxlength="1" placeholder="0" id="otp6">
                </div>
                <div class="verifyBtnWrapper">
                    <button class="btn btn-primary w-100 mt-2 verify" id="verify" type="button">
                        <span class="spinner-border spinner-border-sm d-none btnLoader" role="status" aria-hidden="true"></span>
                        <span class="btnText">Verify</span>
                    </button>
                </div>
                <a class="countdown"><span class="restart"> Resend code:</span> <span class="timer"> </span></a>
            </div>
            <div class="d-none loaderWrapper">
                <div class="loader"></div>
                <h3 class="h3 mt-3">Sending new code...</h3>
            </div>
        </form>
    </main>

    <script>
        const timeLimit = 150000;
        const timeLimit2 = 10000;

        const startTime = Date.now();

        const endTime = startTime + timeLimit;
        const endTime2 = startTime + timeLimit2;

        let resend = document.querySelector('.countdown');

        let codeValid = true;

        function displayTimeRemaining() {
            const currentTime = Date.now();

            const timeRemaining = endTime - currentTime;

            const secondsRemaining = Math.round(timeRemaining / 1000);

            let minutes = Math.floor(secondsRemaining / 60);

            let seconds = secondsRemaining % 60;

            if (minutes < 10) {
                minutes = 0 + "" + minutes;
            }

            if (seconds < 10) {
                seconds = 0 + "" + seconds;
            }

            document.querySelector(".timer").innerHTML = `${minutes}:${seconds}`;

            let verifyBtn = document.querySelector('#verify');
            let veri_code = document.querySelectorAll('.veri_code');

            if (currentTime >= endTime2) {
                document.querySelector(".restart").style.opacity = 1;
                resend.href = "./resendCode.php";
            }
            resend.addEventListener('click', () => {
                document.querySelector('.veri-form-wrapper').classList.add('d-none')
                document.querySelector('.loaderWrapper').classList.remove('d-none');
            })

            if (currentTime >= endTime) {
                document.querySelector(".restart").style.opacity = 1;
                clearInterval(timer);
                resend.href = "./resendCode.php";
                verifyBtn.disabled = true;
                veri_code.forEach(code => {
                    code.disabled = true;
                    code.style.borderColor = '';
                });

                alert("Code has expired. Please click on the resend code link to resend the code!");
            } else {
                verifyBtn.disabled = false;
                veri_code.forEach(code => {
                    code.disabled = false;
                });
            }
        }

        const timer = setInterval(displayTimeRemaining, 1000);

        window.onload = displayTimeRemaining;
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="../js/verificate.js"></script>

</body>

</html>