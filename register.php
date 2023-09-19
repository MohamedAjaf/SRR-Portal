<?php
$uname = $_POST['uname'];
$email  = $_POST['email'];
$pass = $_POST['pass'];
$cpass = $_POST['cpass'];

// Check if the password match
if ($pass != $cpass) {
    echo '<script>';
            echo 'alert("Password does not match");';
            echo 'window.location.href = "register.html";';
            echo '</script>';
    
}
else if (!empty($uname) && !empty($email) && !empty($pass) && !empty($cpass))
{
    $host = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "srr";

    // Create connection
    $conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);

    if (mysqli_connect_error()){
        die('Connect Error ('. mysqli_connect_errno() .') ' . mysqli_connect_error());
    }
    
    else{
        $SELECT = "SELECT email From register Where email = ? Limit 1";
        $INSERT = "INSERT Into register (uname , email , pass , cpass) values (?,?,?,?)";

        // Prepare statement
        $stmt = $conn->prepare($SELECT);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $rnum = $stmt->num_rows;
        $stmt->close();
        
        // Check if email already exists
        if ($rnum == 0) {
            $stmt = $conn->prepare($INSERT);
            $stmt->bind_param("ssss", $uname, $email, $pass, $cpass);
            $stmt->execute();
            echo '<script>';
            echo 'alert("Registered Sucessfully");';
            echo 'window.location.href = "login.html";';
            echo '</script>';
            $stmt->close();
        } else {
            echo '<script>';
            echo 'alert("Someone already registered using this email.Go back and try with another email");';
            echo 'window.location.href = "login.html";';
            echo '</script>';
        }
        $conn->close();
    }
}
else {
    echo '<script>';
            echo 'alert("All fields are required");';
            echo 'window.location.href = "login.html";';
            echo '</script>';
}
?>
