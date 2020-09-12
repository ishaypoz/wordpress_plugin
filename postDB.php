<?php
$user = 'root';
$pass = '';
$db = 'weather_test';

$conn = mysqli_connect('localhost', $user, $pass, $db);
if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
}

$city = $_POST['city_name'];
$temp_min = $_POST['temp_min'];
$temp_max = $_POST['temp_max'];
$date_time = $_POST['date_time'];
$wind_speed = $_POST['wind'];

$check = "SELECT * FROM city WHERE c_city_name = ('$city')";
$rs = mysqli_query($conn,$check);
$data = mysqli_fetch_array($rs, MYSQLI_NUM);

if(is_null($data)) {
    $sql = "INSERT INTO city (c_city_name) VALUES ('$city')";
    mysqli_query($conn, $sql) or die(mysqli_error($conn));
    
    $query = mysqli_query($conn, "SELECT c_id FROM city where c_city_name = ('$city')");
    $row = mysqli_fetch_assoc($query);       
    $city_id = $row['c_id'];

    $sql = "INSERT INTO temp (t_temp_max,t_temp_min,t_date_time, t_city_id) VALUES ('$temp_max','$temp_min','$date_time','$city_id')";
    mysqli_query($conn, $sql) or die(mysqli_error($conn)); 
    $sql = "INSERT INTO wind (w_speed, w_city_id) VALUES ('$wind_speed', '$city_id')";
    mysqli_query($conn, $sql) or die(mysqli_error($conn));

    echo "added";
}else{
    $query = mysqli_query($conn, "SELECT c_id FROM city where c_city_name = ('$city')");
    $row = mysqli_fetch_assoc($query);       
    $city_id = $row['c_id'];
    
    $sql = "UPDATE temp SET t_temp_max = '$temp_max', t_temp_min = '$temp_min', t_date_time = '$date_time' WHERE t_city_id = ('$city_id')";
    mysqli_query($conn, $sql) or die(mysqli_error($conn)); 
    $sql = "UPDATE wind SET w_speed = '$wind_speed' WHERE w_city_id = ('$city_id')";
    mysqli_query($conn, $sql) or die(mysqli_error($conn));
    
    echo "updated";
}
mysqli_close($conn);
?>
