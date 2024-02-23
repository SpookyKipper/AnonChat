<?php


$conf_json = json_decode(file_get_contents("./config.json",1),1);



$db_name = $conf_json["db_name"];

$db_user = $conf_json["db_user"];

$db_pass = $conf_json["db_pass"];

$db_host = $conf_json["db_host"];





function clean($string)

{

  $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.



  return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

}



$cid = clean($_POST['cid']);

$message = base64_encode($_POST['message']);

$date = time();



$sql = "SELECT * FROM `conversations` WHERE id = '$cid'";

$conn = new mysqli($db_host, $db_user, $db_pass,  $db_name);

if ($conn->connect_error) {

  die("Connection failed: " . $conn->connect_error);

}

$replies;

$result = $conn->query($sql);

if ($result->num_rows == 1) {

    $array = $result->fetch_array();

  $replies = json_decode($array["replies"], 1);

} else {

  die("An error occurred. Error Code: SCS_VIEW_ERR");

}



$new_reply_array = array(

    "usr"=> "user",

    "msg"=>$message,

    "date"=>$date

);



array_push($replies, $new_reply_array);

$json = json_encode($replies);

$sql = "UPDATE `conversations` SET `replies` = '$json' WHERE `conversations`.`id` = '$cid';";



$conn->query($sql);

$conn->close();



header("Location: /view/?cid=$cid");