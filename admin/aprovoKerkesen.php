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

        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = SITE_EMAIL;            //SMTP username
        $mail->Password   = SITE_PASSWORD;    
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;         


        $mail->setFrom('no@reply.com', 'sistemi-termineve-online.com');
        $mail->addAddress($data['email'], $data['pacienti']); 


        //Content
        $mail->isHTML(true);        


        $mail->Subject = 'Anulimi i terminit';
        $mail->Body    = "<p style='font-size: 16px; color: black;'>
                    $gjinia{$data['pacienti']},
                    <br> <br>
                    Kërkesa juaj për anulimin e terminit me datë:{$data['data']}, në orën:{$data['ora']}, 
                    për arsyen se: '{$data['arsyeja_anulimit']}' është aprovuar.
                    <br><br>
                    Me respekt, <br>
                    sistemi-termineve-online.com
                    </p>";

        $mail->send();

        $del_sql = "DELETE FROM terminet WHERE doktori=:doktori AND 
            pacienti=:pacienti AND 
            numri_personal=:numri_personal AND
            data=:data AND ora=:ora";
        $del_prep = $con->prepare($del_sql);
        $del_prep->bindParam(':doktori', $data['doktori']);
        $del_prep->bindParam(':pacienti', $data['pacienti']);
        $del_prep->bindParam(':numri_personal', $data['numri_personal']);
        $del_prep->bindParam(':data', $data['data']);
        $del_prep->bindParam(':ora', $data['ora']);

        if($del_prep->execute()){

            $z_sql = "SELECT * FROM orari WHERE doktori=:doktori AND data=:data";
            $z_prep = $con->prepare($z_sql);
            $z_prep->bindParam(':doktori', $data['doktori']);
            $z_prep->bindParam(':data', $data['data']);
            $z_prep->execute();
            $row = $z_prep->fetch();
        


            $del_kerkesa_sql = "DELETE FROM kerkesatanulimit WHERE id=:id";
            $del_kerkesa_prep = $con->prepare($del_kerkesa_sql);
            $del_kerkesa_prep->bindParam(':id', $id);
            $del_kerkesa_prep->execute();


            $time = new DateTime($row['zene_deri']);
            $time->modify("-{$row['kohezgjatja']}minutes");        
            $time_format = $time->format("H:i:s");

            $update_sql = "UPDATE orari SET zene_deri=:zene_deri WHERE doktori=:doktori AND data=:data";
            $update_prep = $con->prepare($update_sql);
            $update_prep->bindParam(':doktori', $data['doktori']);
            $update_prep->bindParam(':data', $data['data']);
            $update_prep->bindParam(':zene_deri', $time_format);
            $update_prep->execute();

            echo "<script>
                    alert('Kerkesa u aprovua.');
                    window.location.replace('kerkesatAnulimit.php');
                </script>";
        }

        exit();


    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
?>