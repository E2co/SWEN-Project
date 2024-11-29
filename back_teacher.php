<?php
    session_start();
    session_destroy();
    header('Location: Teacher_dashboard.html');
    exit();
?>