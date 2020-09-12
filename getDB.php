<?php
$user = 'root';
$pass = '';
$db = 'weather_test';

$conn = mysqli_connect('localhost', $user, $pass, $db);
if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
}
$city = $_POST['city_name'];
$check = "SELECT * FROM city WHERE c_city_name = ('$city')";
$rs = mysqli_query($conn,$check);
$data = mysqli_fetch_array($rs, MYSQLI_NUM);

if(is_null($data)) {
    echo "none";
}else{  
    $query = mysqli_query($conn, "SELECT c_id FROM city where c_city_name = ('$city')");
    $row = mysqli_fetch_assoc($query);       
    $city_id = $row['c_id'];
    $sql = mysqli_query($conn, "SELECT * FROM city JOIN temp ON city.c_id = temp.t_city_id JOIN wind ON city.c_id = wind.w_city_id WHERE city.c_id = ('$city_id')");
    $result = mysqli_fetch_assoc($sql); 
    echo json_encode($result);
}
mysqli_close($conn);

?>
