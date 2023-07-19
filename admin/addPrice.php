<?php
    include('../config.php');
    $serErr = $priceErr = '';

    if(isset($_POST['addPrice'])){
        $service = $_POST['service'];
        $price = $_POST['price'];

        if(empty($service)){
            $serErr = 'empty service';
        } else{

            $check = "SELECT * FROM prices WHERE name=:service";
            $check_prep = $con->prepare($check);
            $check_prep->bindParam(':service', $service);
            $check_prep->execute();
            $check_data = $check_prep->fetch();
    
            if($check_data){
                $serErr = "Exists";
            } else{
                $serErr = '';
            }
        } 

        if(empty($price)){
            $priceErr = 'empty price';
        } else {
            $priceErr = '';
        }

        $errors = [$serErr, $priceErr];

        if($serErr == '' && $priceErr == ''){
            echo json_encode($errors);
        } else{
            echo json_encode($errors);
        }

    }


    if(isset($_POST['insertPrice'])){
        $service = $_POST['service'];
        $price = $_POST['price'];

        
        $insert = "INSERT INTO prices(name, price) VALUES(:service, :price)";
        $prep = $con->prepare($insert);
        $prep->bindParam(':service', $service);
        $prep->bindParam(':price', $price);
        $prep->execute();

        echo "Inserted";
    }
?>