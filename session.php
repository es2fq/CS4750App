<?php
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
include('db.php');
// Selecting Database
session_start();// Starting Session
$loggedin = false;
if (!isset($_SESSION["email"]) || !isset($_SESSION["first_name"]) || !isset($_SESSION["last_name"])){
  $loggedin = false;
} else {
  $loggedin = true;
  // Storing Session
  $user_check=$_SESSION['email'];
  // SQL Query To Fetch Complete Information Of User
  $login_session_name = $_SESSION['first_name'];
  if (!isset($login_session_name)) {
    $loggedin = false;
  }
}
if(!$loggedin){
    mysqli_close($connection); // Closing Connection
    session_unset();
}
$_SESSION['loggedin'] = $loggedin;
?>
