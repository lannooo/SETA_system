<?php
    session_start();
    $_SESSION["username"] = NULL;
    $_SESSION["userName"] = NULL;
    header("location: index.php");
?>