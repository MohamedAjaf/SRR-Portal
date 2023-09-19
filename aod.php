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

// Retrieve the uname and fd from the URL parameters
$uname = $_GET['uname'];
$sd = $_GET['fd'];

// Initialize the declineReason variable
$declineReason = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the submit button was pressed
    if (isset($_POST["submit"])) {
        // Update the database with the file name and mmark value
        $stmt1 = $conn->prepare("UPDATE applyod SET mmark = 1 WHERE uname = ? AND fd = ?");
        $stmt1->bind_param("ss", $uname, $sd);
        $stmt1->execute();
        header("Location: shome.php");
    } }
if ($_POST["decline"]) {
        // Capture the decline reason
        $declineReason = $_POST['reason'];
        // Update the database with decline information and reason
        $stmt = $conn->prepare("UPDATE applyod SET decline ='decline', reason = ? WHERE uname = ? AND fd = ?");
        $stmt->bind_param("sss", $declineReason, $uname, $sd);
        $stmt->execute();
        header("Location: shome.php");
    }


// Prepare and execute the SQL query to retrieve the details
$stmt = $conn->prepare("SELECT * FROM applyod WHERE uname = ? AND fd = ?");
$stmt->bind_param("ss", $uname, $sd);
$stmt->execute();
$result = $stmt->get_result();

// Check if the query was successful
if ($result->num_rows > 0) {
    // Fetch data from the row
    $row = $result->fetch_assoc();
    $name = $row["name"];
    $uname = $row["uname"];
    $regno = $row["regno"];
    $branch = $row["branch"];
    $year = $row["year"];
    $sec = $row["sec"];
    $noo = $row["noo"];
    $nod = $row["nod"];
    $fd = $row["fd"];
    $td = $row["td"];
    $dd = $row["dd"];
    $proof = $row["proof"];
    $es = $row["es"];
}

// Close the prepared statement and the database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Approve OD-SRR</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="staff-odacceptance.css">
</head>
<body>
    <div class="navbar">    
        <img src="srr1.png" class="logo">   
        <ul>
            <li> <a href="shome.php">Home</a> </li> 
            <li> <a href="aod.html">OD Approval</a> </li>
            <li> <a href="aleave.html">Leave Approval</a></li>
            <li> <a href="stuprofile.php">Profile</a> </li>
        </ul>
    </div> 
    
    <div class="c">
        <h1 class="ch1">ON DUTY</h1>
        <h2 class="ch1">Application</h2>
        <div class="c1">
            <p id="p1">NAME:         </p><p id="p2"><?php echo $name; ?></p><br><br>
            <p id="p1">SEC ID:       </p><p id="p2"><?php echo $uname; ?></p><br><br>
            <p id="p1">Reg.No:       </p><p id="p2"><?php echo $regno; ?></p><br><br>
            <p id="p1">Deciplene:    </p><p id="p2"><?php echo $branch; ?></p><br><br>
            <p id="p1">Year:         </p><p id="p2"><?php echo $year; ?></p><br><br>
            <p id="p1">Section:      </p><p id="p2"><?php echo $sec; ?></p><br><br>
            <p id="p1">Nature of OD: </p><p id="p2"><?php echo $noo; ?></p><br><br>
            <p id="p1">No.of Days:   </p><p id="p2"><?php echo $nod; ?></p><br><br>
            <p id="p1">Start Date:   </p><p id="p2"><?php echo $fd; ?></p><br><br>
            <p id="p1">End Date:     </p><p id="p2"><?php echo $td; ?></p><br><br>
            <p id="p1">Description:  </p><p id="p2"><?php echo $dd; ?></p><br><br>
            <form method="POST" enctype="multipart/form-data">
                <input type="checkbox" id="myCheckbox" name="myCheckbox" required>
                <p id="checkbox">I have verified all the details and I'm moving this application to the Class Coordinator.</p><br>

                <!-- Add the hidden input field here to capture the decline reason -->
                <input type="hidden" id="reasonField" name="reason" value="<?php echo $declineReason; ?>">

                <input name="submit" class="button" type="submit" value="SUBMIT">
                <input name="decline" class="button1" type="button" value="DECLINE" id="declineButton">
            </form>
        </div>
    </div>

    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <p>Enter your reason to decline the OD</p>
            <textarea id="declineReason" class="dd" name="reason" placeholder="Enter your reason here" required></textarea>
            <p>Are you sure you want to decline this OD Application?</p>
            <!-- Change the button name to "decline1" -->
            <button name="decline" id="confirmButton" type="submit">Yes</button>
            <button id="cancelButton">No</button>
        </div>
    </div>

    <script>
        // Function to show the modal
        function showModal() {
            document.getElementById("confirmModal").style.display = "block";
        }

        // Function to hide the modal
        function hideModal() {
            document.getElementById("confirmModal").style.display = "none";
        }

        // Event listener for the "DECLINE" button
        document.getElementById("declineButton").addEventListener("click", showModal);

        // Event listener for the "Yes" button in the modal
        document.getElementById("confirmButton").addEventListener("click", function() {
            var declineReason = document.getElementById("declineReason").value; // Get the decline reason from the textarea
            hideModal();
            // Update the hidden input field with the captured reason
            document.getElementById("reasonField").value = declineReason;
            // Submit the form when confirming the decline
            document.forms[0].submit();
        }); 

        // Event listener for the "No" button in the modal
        document.getElementById("cancelButton").addEventListener("click", hideModal);
    </script>
</body>
</html>
