<?php
    $user = 'root';
    $pass = '';
    $db = 'palidhje';
    $host = 'localhost';

    try{
        $pdo = new PDO("mysql:host=$host; dbname=$db;", $user, $pass);
    }catch(Exception $e){
        echo 'Error: ' . $e;
    }
?>


<?php
    // $sql = "SELECT u.id, u.name, u.lastName, u.asdas, ut.name AS userType FROM user  u JOIN userType ut ON u.userType = ut.id WHERE userType= 2;";
    // $prep = $pdo->prepare($sql);
    // $prep->execute();
    // $data = $prep->fetchAll();
    
    // foreach($data as $data){
    //     echo $data['id'] . ' ' . $data['userType'] . ' ' . $data['name'] . ' '. $data['lastName'] .' '. $data['asdas'];
    //     echo "<br>";
    // }

    if(isset($_POST['submit'])){

        $name = $_POST['name'];
        $lastName = $_POST['lastName'];
        $asdas = $_POST['asdas'];
        $sql = "INSERT INTO user(userType, name, lastName, asdas) VALUES(2, '$name', '$lastName', '$asdas')";
        $prep = $pdo->prepare($sql);
        $prep->execute();
    }



?>

<?php
include('./config.php');

$prep = $con->prepare("SELECT * FROM departamentet");
$prep->execute();
$data = $prep->fetchAll();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="test.php" method="POST">
        <input type="text" placeholder="name" name="name">
        <input type="text" placeholder="lastName" name="lastName">
        <select name="asdas" id="">
            <?php foreach($data as $data): ?>
            <option value="<?= $data['departamenti'] ?>"><?= $data['departamenti'] ?></option>
            <?php endforeach; ?>
        </select>
        <input type="number" placeholder="userType" name="userType">
        <input type="submit" placeholder="submit" name="submit" value="submit">
    </form>
</body>
</html>