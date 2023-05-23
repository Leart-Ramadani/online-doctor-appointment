<?php 
    include('../config.php');

    $email = $_GET['email'];
    $verificate = 'true';
    $sql = "UPDATE patient_table SET verification_status=:verification_status WHERE email=:email";
    $prep = $con->prepare($sql);
    $prep->bindParam(':email', $email);
    $prep->bindParam(':verification_status', $verificate);
    if($prep->execute()){
        echo "<script>
                alert('Llogaria juaj eshte verifikuar me sukses.');
                window.location.replace('login.php');
            </script>";
    }



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikimi i llogarise</title>
</head>
<body>

</body>
</html>