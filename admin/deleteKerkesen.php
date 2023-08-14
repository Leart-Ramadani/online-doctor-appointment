<?php
    include('../config.php');
    require_once('../emailData.php');

    $id = $_GET['id'];

    $sql = "SELECT * FROM kerkesatanulimit WHERE id=:id";
    $stm = $con->prepare($sql);
    $stm->bindParam(':id', $id);
    $stm->execute();
    $data = $stm->fetch();


    $gender_sql = "SELECT gender FROM users WHERE userType=1 AND personal_id=:personal_id";
    $gender_prep = $con->prepare($gender_sql);
    $gender_prep->bindParam(':personal_id', $data['numri_personal']);
    $gender_prep->execute();
    $gender_data = $gender_prep->fetch();

    if($gender_data['gender'] == 'Mashkull'){
        $gjinia = 'I nderuar z.';
    } else{
        $gjinia = 'E nderuar znj.';
    }

    require("../patientSide/PHPMailer-master/src/Exception.php");
    require("../patientSide/PHPMailer-master/src/PHPMailer.php");
    require("../patientSide/PHPMailer-master/src/SMTP.php");

    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    $mail = new PHPMailer(true);

    try {

        $mail->SMTPDebug = 0;                                       //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = SITE_EMAIL;            //SMTP username
        $mail->Password   = SITE_PASSWORD;                             //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('no@reply.com', 'terminet-online.com');
        $mail->addAddress($data['email'], $data['pacienti']);                           //Add a recipient


        //Content
        $mail->isHTML(true);           


        $mail->Subject = 'Kerkesa per anulimin e terminit';
        $mail->Body    = "
                    $gjinia{$data['pacienti']},
                    <br>
                    Kërkesa juaj për anulimin e terminit me datë:{$data['data']}, në orën:{$data['ora']}, 
                    për arsyen se: '{$data['arsyeja_anulimit']}' nuk është aprovuar.
                    <br>
                    Jeni te obliguar që të shkoni në këtë termin. <br>
                    Mosardhja juaj do të ndëshkohet me përjashtimin nga sistemi-termineve-online.com
                    <br> <br>
                    Me respekt, <br>
                    sistemi-termineve-online.com
                    ";

        $mail->send();

        $del_kerkesa_sql = "DELETE FROM kerkesatanulimit WHERE id=:id";
        $del_kerkesa_prep = $con->prepare($del_kerkesa_sql);
        $del_kerkesa_prep->bindParam(':id', $id);

        if ($del_kerkesa_prep->execute()) {
            echo "<script>
                alert('Kerkesa nuk u miratua.');
                window.location.replace('kerkesatAnulimit.php');
            </script>";
        }

    exit();

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
?>