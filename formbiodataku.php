<?php
session_start();
require 'koneksi.php'; 

$query_sql = "SELECT * FROM biodata_users"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = htmlspecialchars($_POST['fullname']);
    $email = htmlspecialchars($_POST['email']);
    $nim = htmlspecialchars($_POST['nim']);
    $gender = htmlspecialchars($_POST['gender']);
    $birth_place = htmlspecialchars($_POST['birth_place']);
    $birth_date = htmlspecialchars($_POST['birth_date']);
    $hobby = htmlspecialchars($_POST['hobby']);
    $program_study = htmlspecialchars($_POST['program_study']);
    $faculty = htmlspecialchars($_POST['faculty']);

    $conn->close();
} else {
    header("Location: biodataku.html");
    exit();
}
?>
