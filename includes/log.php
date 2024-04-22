<?php
session_start();
$logged_in = $_SESSION['logged_in'] ?? false;

function login()
{
    session_regenerate_id(true);
    $_SESSION['logged_in'] = true;
}

function logout()
{
    $_SESSION = [];

    session_destroy();
}

function require_login($logged_in)
{
    if ($logged_in == false){
        header('Location: login.php');
        exit;
    }
}