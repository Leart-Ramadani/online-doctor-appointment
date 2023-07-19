<?php
    include('../config.php');

    if(isset($_GET['editService']) && $_GET['editService'] == true){
        $id = $_GET['idEdit'];
        $_SESSION['idEdit'] = $id;


        $sql = "SELECT * FROM prices WHERE id=:id";
        $prep = $con->prepare($sql);
        $prep->bindParam(':id', $id);
        $prep->execute();
        $data = $prep->fetch();

        echo json_encode($data);
    }


    $priceErr = $serviceErr = '';
    if(isset($_POST['checkData'])){
        $id = $_SESSION['idEdit'];
        $service = $_POST['editService'];
        $price = $_POST['editPrice'];

        if(empty($service)){
            $serviceErr = 'empty service';
        } else{    
            $serviceErr = '';
        }

        if(empty($price)){
            $priceErr = 'empty price';
        } else{
            $priceErr = '';
        }

        $errors = [$serviceErr, $priceErr];

        if($priceErr == '' && $serviceErr == ''){
            $update = "UPDATE prices SET name=:service, price=:price WHERE id=:id";
            $prep_update = $con->prepare($update);
            $prep_update->bindParam(':id', $id);
            $prep_update->bindParam(':service', $service);
            $prep_update->bindParam(':price', $price);
            $prep_update->execute();

            unset($_SESSION['idEdit']);

            $errors[] = 'inserted';
            echo json_encode($errors);
        } else{
            echo json_encode($errors);
        }
    }
?>