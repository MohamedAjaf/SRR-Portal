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
$notifications = ''; // Variable to store the notifications
$notifications1 = '';

// Prepare and execute the SQL query to retrieve the name
$stmt = $conn->prepare("SELECT name FROM slogin WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Check if the query was successful
if ($result->num_rows > 0) {
    // Fetch data from the row
    $row = $result->fetch_assoc();
    $name = $row["name"];
}

// Close the prepared statement
$stmt->close();

// Prepare and execute the SQL query to retrieve the notifications
$stmt1 = $conn->prepare("SELECT name, uname, fd FROM applyod WHERE mentormail = ? AND smark = true AND mmark = 0 AND decline != 'decline'");
$stmt1->bind_param("s", $email);
$stmt1->execute();
$result1 = $stmt1->get_result();

// Check if the query was successful
if ($result1->num_rows > 0) {
    while ($row1 = $result1->fetch_assoc()) {
        $sname = $row1["name"];
        $uname = $row1["uname"];
        $sd = $row1["fd"];

        // Concatenate the notification message
        $notifications .= "<p><a id='a' href='aod.php?uname=$uname&fd=$sd'>Your Mentee <strong>$sname ($uname)</strong> has applied OD on the date <strong>$sd</strong>.</a></p><br>";
    }
}

$stmt2 = $conn->prepare("SELECT name, uname, fd FROM applyleave WHERE mentormail = ? AND smark = true AND mmark = 0 AND decline != 'decline'");
$stmt2->bind_param("s", $email);
$stmt2->execute();
$result2 = $stmt2->get_result();

// Check if the query was successful
if ($result2->num_rows > 0) {
    while ($row1 = $result2->fetch_assoc()) {
        $sname = $row2["name"];
        $uname = $row2["uname"];
        $sd = $row2["fd"];

        // Concatenate the notification message
        $notifications1= "<p><a id='a' href='aod.php?uname=$uname&fd=$sd'>Your Mentee <strong>$sname ($uname)</strong> has applied leave on the date <strong>$sd</strong>.</a></p><br>";
    }
}


if ($result1->num_rows == 0) {
    $notifications= "<p>No OD Applications.</p>";
}

if ($result2->num_rows == 0) {
    $notifications1= "<p>No Leave Applications.</p>";
}

// Close the prepared statement and the database connection
$stmt1->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Staff-Home-SRR</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="staffhome.css">
</head>
<body>
    <div class="navbar">    
        <img src="srr1.png" class="logo">   
        <ul>
            <li> <a href="shome.php">Home</a> </li> 
            <li> <a href="Profile.html">Profile</a> </li>
        </ul>
    </div>
    <div id="details">
        <h1>Welcome <?php echo $name; ?></h1>
    </div>
    
    <div class="c">
        <h2 class="ch1">OD Notifications</h2>
        <div class="c1">
            <?php echo $notifications; ?>
        </div>

        <h2 class="ch1">Leave Notifications</h2>
        <div class="c1">
            <?php echo $notifications1; ?>
        </div>
    </div>
</body>
</html>
