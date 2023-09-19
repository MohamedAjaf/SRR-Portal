
<?php
session_start();

if (isset($_POST['email']) && isset($_POST['pass'])) {
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    $conn = mysqli_connect("localhost", "root", "", "srr");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $stmt = $conn->prepare("SELECT * FROM login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if ($pass == $row['pass']) {
            $_SESSION['email'] = $row['email'];
            $_SESSION['email'] = $email;
            header("Location: home.php");
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
<html>
<head>
    <title>Login-SRR</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta charset="UTF-8">

    
</head>

<body>
    
    <div class="box"> 
        <img src="sec-logo.png" class="user">
        <h1>Student Login</h1>
        <h4>------Only for Students------</h4>


        <form name="myform"  action="login.php" method="POST" >

            <p>Email</p>
            <input class="small" type="email" name="email" placeholder="sec20cs173@sairamtap.edu.in" required 
            value="<?php echo isset($_SESSION['error']) ? $_POST['email'] : ''; ?>">

            <p>Password</p>
            <input type="password" name="pass" placeholder="Enter Password" required>

            <input type="submit" name="" value="Login">

            <br><br>    
            <a class="a2" href="slogin.php">Staff login</a>
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
