<?php

require_once __DIR__ . '/../validations/session.php';

if (isset($_SESSION['userID']) || isset($_COOKIE['userID'])) {
    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/projeto_sir/pages/secure';
    header('Location: ' . $home_url);
}
