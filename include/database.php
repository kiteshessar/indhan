<?php
$mysqli = new mysqli("localhost","ugel_ro_user","k7@yO46Fk*","ugel_ro");

// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}
?>