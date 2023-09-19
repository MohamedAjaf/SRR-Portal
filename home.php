<?php
session_start();

// if (!isset($_SESSION['email'])) {
//     // Redirect to login page if email is not set in session
//     header('Location: login.html');
//     exit();
// }

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

// Prepare and execute the SQL query with the email parameter
$stmt = $conn->prepare("SELECT name, secid, year, dept, sec FROM maindetails WHERE email = ?");
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
}

// Close the prepared statement and the database connection
$stmt->close();

$stmt1 = $conn->prepare("SELECT name, fd FROM applyod WHERE mail = ? AND decline = 'decline' AND close != 'close'");
$stmt1->bind_param("s", $email);
$stmt1->execute();
$result1 = $stmt1->get_result();

// Check if the query was successful
if ($result1->num_rows > 0) {
    while ($row1 = $result1->fetch_assoc()) {
        $sname = $row1["name"];
        $sd = $row1["fd"];

        // Concatenate the notification message
        $notifications .= "<p id='b'>The OD You applied on the date <strong>$sd</strong> is been declined.</p>
                           <form method='post'>
                           <button class='nbutton' type='submit' name='close'>Clikck here for Reason</button>
                          </form>";
    }
}

if ($result1->num_rows == 0) {
    $notifications .= "<p>NO NOTIFICATIONS</p>";
}

$stmt1->close();

if (isset($_POST['close'])) {
    $stmt2 = $conn->prepare("UPDATE applyod SET close = 'close' WHERE mail = ? AND decline='decline'");
    $stmt2->bind_param("s", $email);
    $stmt2->execute();
    $stmt2->close();
}

$conn->close();


// Remove the email from the session
// unset($_SESSION['email']);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Home-SRR</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <div class="navbar">    
        <img src="srr1.png" class="logo">   
        <ul>
            <li> <a href="home.php">Home</a> </li> 
            <li> <a href="od.html">Apply OD</a> </li>
            <li> <a href="leave.html">Apply leave</a></li>
            <li> <a href="srr.html">Student Report</a></li>
            <li> <a href="stuprofile.php">Profile</a> </li>
        </ul>
    </div>
    <div id="details">
        <h1>Welcome <?php echo $name; ?>  -  <?php echo $secid; ?></h1>
        <h1><?php echo $year; ?>  -  <?php echo $dept; ?>  -  <?php echo $sec; ?></h1>
    </div>  
    
    <div class="buttons">
        <a href="od.html" class="button">Apply OD</a>
        <a href="leave.html" class="button">Apply Leave</a>
        <a href="page3.html" class="button">Student Report</a>
    </div>

    <div class="c">
        <h2 class="ch1">Notifications</h2>
        <div class="c1">
            <?php echo $notifications; ?>
        </div>
    </div>
</body>
</html>