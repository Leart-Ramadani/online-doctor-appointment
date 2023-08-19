<?php
    include '../config.php';
    include '../emailData.php';

    if (isset($_POST['action']) && $_POST['action'] == 'getDates') {
        $doctor = $_POST['doctor'];
    
        $avail_sql = "SELECT data FROM orari WHERE doktori=:doctor";
        $avail_prep = $con->prepare($avail_sql);
        $avail_prep->bindParam(':doctor', $doctor);
        $avail_prep->execute();
        $avail_data = $avail_prep->fetchAll(PDO::FETCH_ASSOC);
    
        $response = [];
    
        foreach ($avail_data as $availability) {
            $response[] = $availability['data'];
        }
    
        echo json_encode($response);
    }
    

    if (isset($_POST['action']) && $_POST['action'] == 'getTimes') {
        $doctor = $_POST['doctor'];
        $date = $_POST['date'];
    
        $time_sql = "SELECT * FROM orari WHERE doktori=:doctor AND data=:date";
        $time_prep = $con->prepare($time_sql);
        $time_prep->bindParam(':doctor', $doctor);
        $time_prep->bindParam(':date', $date);
        $time_prep->execute();
        $time_data = $time_prep->fetch(PDO::FETCH_ASSOC);
    

        $result = array();
        $currentTime = strtotime($time_data['nga_ora']);
        $endTime = strtotime($time_data['deri_oren']);

        while ($currentTime < $endTime) {
            $result[] = date('H:i', $currentTime);
            $currentTime = strtotime('+' . $time_data['kohezgjatja'] . ' minutes', $currentTime);
        }

        $appointments = '';
        foreach ($result as $time) {
            $checkApp = "SELECT * FROM terminet WHERE (statusi='Booked' OR statusi='In progres') AND doktori=:doktori AND data=:data AND ora=:ora";
            $appPrep = $con->prepare($checkApp);
            $appPrep->bindParam(':doktori', $time_data['doktori']);
            $appPrep->bindParam(':data', $time_data['data']);
            $appPrep->bindParam(':ora', $time);
            $appPrep->execute();
            $appData = $appPrep->fetch();


            $com = "SELECT * FROM terminet WHERE statusi='Completed' AND doktori=:doktori AND data=:data AND ora=:ora";
            $com_prep = $con->prepare($com);
            $com_prep->bindParam(':doktori', $time_data['doktori']);
            $com_prep->bindParam(':data', $time_data['data']);
            $com_prep->bindParam(':ora', $time);
            $com_prep->execute();
            $comData = $com_prep->fetch();

            if($appData){
                $appointments .= "<button class='btn btn-primary disabled' style='width: 75px;'  title='This appointment is booked'>{$time}</button>";
            } else if($comData) {
                $appointments .= "<button class='btn btn-primary disabled' style='width: 75px;' title='This appointment is completed'>{$time}</button>";
            } else{
                $appointments .= "<button class='btn btn-primary' style='width: 75px;' value='{$time}' onclick='getValue(this.value)'  data-bs-toggle='modal' data-bs-target='#referenceBooking'>{$time}</button>";
            }

        }
    
        echo $appointments;
    }
    

    if (isset($_POST['action']) && $_POST['action'] == 'showAppointment') {
        $doctor = $_POST['doctor'];
        $date = $_POST['date'];
        $time = $_POST['time'];

        
    }
    
?>