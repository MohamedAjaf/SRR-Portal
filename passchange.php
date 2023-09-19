    <?php
    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "srr";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $_SESSION['email'];
    $notifications = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $oldPassword = $_POST['opass'];
        $newPassword = $_POST['npass'];
        $confirmPassword = $_POST['cpass'];

        // Fetch the old password from the database
        $query = "SELECT pass FROM login WHERE email = '$email'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $storedPassword = $row['pass'];

            // Verify old password
            if ($storedPassword==$oldPassword) {

                if($newPassword==$confirmPassword){

                    if($storedPassword != $confirmPassword){
                
                        // Update the password in the database
                        $updateQuery = "UPDATE login SET pass = '$confirmPassword' WHERE email = '$email'";
                        if ($conn->query($updateQuery) === TRUE) {
                            $notification = "Password changed successfully.";
                            
                        } else {
                            $notifications = "Error updating password: " . $conn->error;
                        }
                    }else{
                        $notifications="This Password is already used.";
                    }    
                }else{
                    $notifications= "The Confirm Password doesn't match.";
                }
            } else {
                $notifications = "Old password is INCORRECT.";
            }
        } else {
            $notifications = "Connection Failure,Contact ADMIN.";
        }
    }
    ?>

<!DOCTYPE html>
<html>
<head>
    <title>Change password</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="passchange.css">

    <script>
        window.onload = function() {
            setTimeout(function() {
                var errorMessage = document.getElementById('notifications'); 
                if (errorMessage) {
                    errorMessage.style.display = 'none';
                }
            }, 3500); 
        };

        function goback() {
            window.location.href = "stuprofile.php";
        }
    </script>
</head>
<body>
    <div class="navbar">    
        <h1 class="inhead">Change Password</h1>
    </div>
    <form name="passchange" action="passchange.php" method="post">
        <div class="c">
        
            <div class="details">
                
                <div class="c1">
                    <p>Old Password</p> 
                    <p>New Password</p>
                    <p>Confirm Password</p>
                </div>
                <div class="c2">
                    <input id="inp" type="password" class="opass" name="opass" required autofocus>
                    <input id="inp" type="password" class="npass" name="npass" required>
                    <input id="inp" type="password" class="cpass" name="cpass" required>
                </div>
            </div>

            <p id="checkbox">
                <input type="checkbox" id="myCheckbox" name="myCheckbox" required>
                <i>I am fully aware of changing my account password.</i>
            </p>
            <ul>
                <input id="submit" type="submit" value="Change Password">
                <button id="submit" onclick="goback()">Back</button>
            </ul>
            <?php
            if (isset($notification)) {
                echo '<p style="color: green; text-align:center" id="notifications">' . $notification . '</p>';
                unset($notification);
            } elseif (isset($notifications)) {
                echo '<p style="color: red; text-align:center" id="notifications">' . $notifications . '</p>';
                unset($notifications);
            }
            ?>

        </div>

    </form>
</body>
</html>