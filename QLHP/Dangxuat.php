<?php
session_start();
session_unset();
session_destroy();
// $_SESSION["user"] = NULL;
header("Location:index.html");
exit();
?>
