<?php
session_start();

unset($_SESSION['mobile_otp']);

unset($_SESSION['userlogin']);

unset($_SESSION['ro_details']);


header("Location: otp_login.php");


?>
