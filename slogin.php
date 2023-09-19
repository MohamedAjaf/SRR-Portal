<?php
session_start();

if (isset($_POST['email']) && isset($_POST['pass'])) {
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    // Create connection
    $conn = mysqli_connect("localhost", "root", "", "srr");

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare statement
    $stmt = $conn->prepare("SELECT * FROM slogin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify password
        if ($pass == $row['pass']) {
            $_SESSION['email'] = $row['email'];
            $_SESSION['email'] = $email;
            header("Location: shome.php");
            exit();
        } else {
            $_SESSION['error'] = "Incorrect password. Please try again.";
        }
    } else {
        $_SESSION['error'] = "Incorrect Email ID. Please try again.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<head>
<title>Staff Login-SRR</title>
<link rel="stylesheet" type="text/css" href="style.css">
<meta charset="UTF-8">
    </head>
    

<body>

    <div class="box"> 

    <img src="sec-logo.png" class="user">

        <h1>Staff Login</h1>
        <h4>------Only for Staff------</h4>

        <form name="myform"  action="slogin.php" method="POST" >

            <p>Email</p>
            <input class="small" type="email" name="email" placeholder="bharathy.cse@sairam.edu.in" required
            value="<?php echo isset($_SESSION['error']) ? $_POST['email'] : ''; ?>">

            <p>Password</p>
            <input type="password" name="pass" placeholder="Enter Password" required>


            <input type="submit" name="" value="Login">

            <br><br>
            <a class="a2" href="login.php">Student login</a>
            <br><br>
            <?php
        if (isset($_SESSION['error'])) {
            echo '<p style="color: red;" id="error-message">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>
        </form>
    </div>

</body>
<script>
    
    window.onload = function() {
        setTimeout(function() {
            var errorMessage = document.getElementById('error-message');
            if (errorMessage) {
                errorMessage.style.display = 'none';
            }
        }, 3500); 
    };
</script>
</html>
