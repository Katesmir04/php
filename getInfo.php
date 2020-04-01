<?php
header('Content-type: application/json');
$host = 'mysql.hostinger.ru'; // адрес сервера 
$database = 'u119004082_os'; // имя базы данных
$user = 'u119004082_baks'; // имя пользователя
$password = '142174'; // пароль

$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
mysqli_set_charset($link, "utf8");

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

if(isset($data['getInfo'])){
  $temp = $data['temp'];
  $age = $data['age'];
    getInfo($link, $temp, $age);
}

function getInfo($link, $temp, $age){
    $query = "SELECT * FROM `baby_answer` WHERE $temp > `temp_min` AND $temp <= `temp_max` AND $age <= `age_max` AND $age >= `age_min`";

    $result = mysqli_query($link,$query);
    $count = mysqli_num_rows($result);
    if($result && $count > 0){
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $options = $row['options'];
        }
    }
    $pieces = explode(",", $options);
    foreach($pieces as $item){
        $query = "SELECT * FROM `kleidung` WHERE `id`=".$item;
        $result = mysqli_query($link,$query);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $array[] = $row;
        }
    }
    
    if($result && $count > 0){
        $answer_server = array(
            'message' => true,
            'result' => $array
        );
    }else{
         $answer_server = array(
            'message' => false
        );
    }
    echo json_encode($answer_server);
}

mysqli_close($link);
?>