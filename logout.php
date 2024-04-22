<?php
include 'includes/log.php';
logout();                             // Call logout() to terminate session
header('Location: login.php');         // Redirect to home page