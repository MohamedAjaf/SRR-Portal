<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "srr";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    $_SESSION['error_message'] = "Connection failed: " . $conn->connect_error;
    header('Location: leave.html');
    exit();
}

// Retrieve the email from the session
$email = $_SESSION['email'];
$notifications = '';

$nol = $_POST['nol'];
$nod = $_POST['nod'];
$fd = $_POST['fd'];
$dd = $_POST['dd'];
$proof = $_POST['proof'];
$es = $_POST['es'];

// Retrieve mentormail and coordmail from maindetails table
$sql_subquery = "SELECT name, secid, regno, dept, year, sec, mentormail, coordmail FROM maindetails WHERE email = '$email'";
$result_subquery = $conn->query($sql_subquery);

if ($result_subquery->num_rows > 0) {
    $row_subquery = $result_subquery->fetch_assoc();
    $name = $row_subquery['name'];
    $uname = $row_subquery['secid'];
    $regno = $row_subquery['regno'];
    $branch = $row_subquery['dept'];
    $year = $row_subquery['year'];
    $sec = $row_subquery['sec'];
    $mentormail = $row_subquery['mentormail'];
    $coordmail = $row_subquery['coordmail'];

    // Calculate the "to date" (td) in the same format as $fd
    $fd_parts = explode('-', $fd);
    $fd_formatted = $fd_parts[2] . '-' . $fd_parts[1] . '-' . $fd_parts[0]; // Convert to 'yyyy-mm-dd'

    // Calculate the "to date" ($td) by adding the number of days to $fd
    $timestamp_fd = strtotime($fd_formatted);
    $timestamp_td = strtotime("+$nod days", $timestamp_fd);
    $timestamp_td = strtotime("-1 day", $timestamp_td); // Subtract 1 day
    $td = date('d-m-Y', $timestamp_td); // Convert back to 'dd-mm-yyyy' format


    // Date check
    $sql_check = "SELECT * FROM applyleave WHERE uname = '$uname' AND fd = '$fd' AND nol = '$nol'";
    $result_check = $conn->query($sql_check);
    if ($result_check->num_rows > 0) {
        $_SESSION['error_message'] = "You have already applied leave in the entered dates!!!";
        header('Location: leave.html');    
        exit();
    }

    // Insert into applyod table
    $sql = "INSERT INTO applyleave (name, uname, regno, branch, year, sec, nol, nod, fd, td, dd, proof, es, smark, mmark, cmark, hmark, mail, mentormail, coordmail)
    VALUES ('$name', '$uname', '$regno', '$branch', '$year', '$sec', '$nol', '$nod', '$fd', STR_TO_DATE('$td', '%d-%m-%Y'), '$dd', '$proof', '$es', true, 'false', 'false', 'false', '$email', '$mentormail', '$coordmail')";


    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "Your Leave is applied successfully. Please wait for the approval.";
        header('Location: leave.html');
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . $sql . "<br>" . $conn->error;
        header('Location: leave.html');
        exit();
    }
}

?>

    