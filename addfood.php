<?php
include('db.php');
include('sessionwithlogout.php');

$count = $_POST["count"];
$foodData = explode($_POST["food"]);
$foodId = $foodData[0];
$foodName = $foodData[1];
$foodCalories = $foodData[2];

$userId = $_SESSION["user_id"];
$date = date('Y-m-d H:i:s');

$sql = "INSERT INTO Food_Intake (date, user_id, foods_id, count) VALUES (" . $date . "," . $userId . "," . $foodId . "," . $count . ")";

if ($connection->query($sql) === TRUE) {
    echo "Yay";
} else {
    echo "Error: " . $connections->error;
}
?>
