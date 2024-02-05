<?php
session_start();
require_once __DIR__ . '/../repositories/userRepository.php';

function isAuthenticated()
{
    return isset($_SESSION['userID']) ? true : false;
}

function user()
{
    if (isAuthenticated()) {
        return getById($_SESSION['userID']);
    } else {
        return false;
    }
}

function userId()
{
    return  $_SESSION['userID'];
}

function administrator()
{
    $user = user();
    return $user['isadmin'] ? true : false;
}
?>