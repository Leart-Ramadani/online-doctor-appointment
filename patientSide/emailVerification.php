<?php
include('../config.php');

if(!isset($_SESSION['verify'])){
    header("Location: signup.php");
}

$sql = "SELECT verificated FROM patient_table WHERE username = :username";
$prep = $con->prepare($sql);
$prep->bindParam(':username', $_SESSION['verify']);
$prep->execute();
$data = $prep->fetch();

if($data['verificated'] == true){
    echo "<script>
        alert('Llogaria juaj eshte verifikuar!');
        window.location.replace('../index.php');
        </script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikoni</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="shortcut icon" href="../photos/icon-hospital.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

</head>

<body style="background-color: #081C15;">
    <main class="mainVeriCode">
        <form autocomplete="off" class="veri_form">
            <h1>Verifikoni llogarine tuaj</h1>
            <p>Shkruani kodin qe je kemi derguar ne emailin tuaj!</p>
            <div>
                <input class="veri_code" type="text" maxlength="1" placeholder="0" id="otp1">
                <input class="veri_code" type="text" maxlength="1" placeholder="0" id="otp2">
                <input class="veri_code" type="text" maxlength="1" placeholder="0" id="otp3">
                <input class="veri_code" type="text" maxlength="1" placeholder="0" id="otp4">
                <input class="veri_code" type="text" maxlength="1" placeholder="0" id="otp5">
                <input class="veri_code" type="text" maxlength="1" placeholder="0" id="otp6">
            </div>
            <button type="button" class="verify" id="verify">Verifiko</button>
            <a class="countdown"><span class="restart"> Dergo kodin perseri:</span> <span class="timer"> </span></a>
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

            if(currentTime >= endTime2){
                document.querySelector(".restart").style.opacity = 1;
                resend.href = "./resendCode.php";
            }

            if (currentTime >= endTime) {
                document.querySelector(".restart").style.opacity = 1;
                clearInterval(timer);
                resend.href = "./resendCode.php";
                verifyBtn.disabled = true;
                veri_code.forEach(code => {
                    code.disabled = true;
                    code.style.borderColor = '';
                });
                
                alert("Kodi ka skaduar. Ju lutemi klikoni linkun per te derguar kodin perseri për të verifikuar llogarinë tua");
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

    <script src="../js/verificate.js"></script>
    

</body>

</html>