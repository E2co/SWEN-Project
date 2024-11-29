<?php
    session_start();
    session_destroy();
    header('Location: Edit_registry_principal.html');
    exit();
?>