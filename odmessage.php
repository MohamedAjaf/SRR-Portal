<?php
session_start();
if (isset($_SESSION['success_message'])) {
    echo $_SESSION['success_message'];
    unset($_SESSION['success_message']);
} elseif (isset($_SESSION['error_message'])) {
    echo $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}
?>
