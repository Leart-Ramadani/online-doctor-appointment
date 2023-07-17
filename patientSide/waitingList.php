<?php
    include('../config.php');

    if(isset($_POST['waitList']) && $_POST['waitList'] == true){
        $id = $_SESSION['id_ofApp'];
        $time = $_POST['time'];

        $schedule_sql = "SELECT * FROM orari WHERE id='$id'";
        $schedule_prep = $con->prepare($schedule_sql);
        $schedule_prep->execute();
        $schedule_data = $schedule_prep->fetch();

        $doctor = $schedule_data['doktori'];
        $departament = $schedule_data['departamenti'];
        $date = $schedule_data['data'];

        $appointment_sql = "SELECT * FROM terminet WHERE doktori=:doktori AND departamenti=:departamenti AND data=:data AND ora=:ora AND statusi='Booked'";
        $appointment_prep = $con->prepare($appointment_sql);
        $appointment_prep->bindParam(':doktori', $doctor);
        $appointment_prep->bindParam(':departamenti', $departament);
        $appointment_prep->bindParam(':data', $date);
        $appointment_prep->bindParam(':ora', $time);
        $appointment_prep->execute();
        $appointment_data = $appointment_prep->fetch();

        if($appointment_data){

            $check_sql = "SELECT * FROM waiting_list WHERE doctor=:doctor AND departament=:departament AND date=:date AND time=:time AND personal_id=:personal_id";
            $check_prep = $con->prepare($check_sql);
            $check_prep->bindParam(':doctor', $appointment_data['doktori']);
            $check_prep->bindParam(':departament', $appointment_data['departamenti']);
            $check_prep->bindParam(':date', $appointment_data['data']);
            $check_prep->bindParam(':time', $appointment_data['ora']);
            $check_prep->bindParam(':personal_id', $_SESSION['numri_personal']);
            $check_prep->execute();
            $check_data = $check_prep->fetch();
            
            if(!$check_data){
                $sql = "INSERT INTO waiting_list(doctor, departament, date, time, personal_id, apointment_id) VALUES(:doctor, :departament, :date, :time, :personal_id, :apointment_id)";
                $prep = $con->prepare($sql);
                $prep->bindParam(':doctor', $appointment_data['doktori']);
                $prep->bindParam(':departament', $appointment_data['departamenti']);
                $prep->bindParam(':date', $appointment_data['data']);
                $prep->bindParam(':time', $appointment_data['ora']);
                $prep->bindParam(':personal_id', $_SESSION['numri_personal']);
                $prep->bindParam(':apointment_id', $appointment_data['id']);
                $prep->execute();

                unset($_SESSION['id_ofApp']);
                echo "Success";
            } else{
                unset($_SESSION['id_ofApp']);
                echo "Exists";
            }

        } else {
            unset($_SESSION['id_ofApp']);
            echo "Error";
        }
    }



    if(isset($_POST['count']) && $_POST['count'] == true){
        $time = $_POST['time'];
        $id = $_SESSION['id_ofApp'];

        $schedule_sql = "SELECT * FROM orari WHERE id='$id'";
        $schedule_prep = $con->prepare($schedule_sql);
        $schedule_prep->execute();
        $schedule_data = $schedule_prep->fetch();

        $doctor = $schedule_data['doktori'];
        $departament = $schedule_data['departamenti'];
        $date = $schedule_data['data'];

        $count = "SELECT COUNT(*)  AS totalCount FROM waiting_list WHERE doctor=:doctor AND departament=:departament AND date=:date AND time=:time";
        $count_prep = $con->prepare($count);
        $count_prep->bindParam(':doctor', $doctor);
        $count_prep->bindParam(':departament', $departament);
        $count_prep->bindParam(':date', $date);
        $count_prep->bindParam(':time', $time);
        $count_prep->execute();
        $countData = $count_prep->fetch();

        echo $countData['totalCount'];

    }
?>