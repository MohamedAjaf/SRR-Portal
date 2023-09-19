<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "srr";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the email from the session
$email = $_SESSION['email'];
$notifications = '';

// Logout functionality
// if (isset($_GET['logout'])) {
//     // Destroy all session variables
//     session_unset();

//     // Destroy the session
//     session_destroy();

//     // Redirect to the login page
//     header("Location: login.php");
//     exit();
// }

// Prepare and execute the SQL query with the email parameter
$stmt = $conn->prepare("SELECT name, secid, year, dept, sec,regno,mentor,coord FROM maindetails WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Check if the query was successful
if ($result->num_rows > 0) {
    // Fetch data from the row
    $row = $result->fetch_assoc();
    $name = $row["name"];
    $secid = $row["secid"];
    $year = $row["year"];
    $dept = $row["dept"];
    $sec = $row["sec"];
    $reg_no = $row["regno"];
    $mentor = $row["mentor"];
    $coord = $row["coord"];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Profile</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="stuprofile.css">
</head>
<body>
    <div class="navbar">    
        <img src="srr1.png" class="logo">   
        <ul>
            <li><a href="home.php">Home</a></li> 
            <li><a href="od.html">Apply OD</a></li>
            <li><a href="leave.html">Apply leave</a></li>
            <li><a href="srr.html">Student Report</a></li>
            <li><a href="stuprofile.php">Profile</a></li>
        </ul>
    </div>

    <div class="c">
        <h1 class="inhead">Student Profile</h1>
        <div class="details">
            <div class="c1">
                <p>Name</p>
                <p>SEC ID</p>
                <p>Register No</p>
                <p>Mentor</p>
                <p>Co Ordinator</p>
            </div>
            <div class="c2">
                <p><?php echo $name ?> </p>
                <p><?php echo $secid ?> </p>
                <p><?php echo $reg_no?></p>
                <p><?php echo $mentor?></p>
                <p><?php echo $coord?></p>
            </div>
            <div class="c3">
                <a href="passchange.php" class="button">Change password</a><br>
                <a href="reports.php" class="button">Submit Report</a>
                <a href="login.php" class="logbutton">Log Out</a> <!-- Logout functionality -->
            </div>
        </div>
    </div>
</body>
</html>
