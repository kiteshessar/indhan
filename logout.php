<?php
session_start();

unset($_SESSION['mobile_otp']);

unset($_SESSION['userlogin']);

header("Location: otp_login.php");

?>